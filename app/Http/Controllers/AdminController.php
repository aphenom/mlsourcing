<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdersRequest;
use App\Models\ImportedProduct;
use App\Models\SourcingCountry;
use App\Models\DestinationCountry;
use App\Models\AgentDestination;
use App\Models\AgentSourcing;
use App\Models\User;
use App\Models\PaymentOption;
use App\Models\Payment;
use App\Models\ChatThread;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Services\NotificationService;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    // Dashboard Admin : admin.dashboard
    public function dashboard(Request $request){
        $totalSellers = User::where('role', '3')->count();
        $totalAgents = User::where('role', '2')->count();
        $totalRequests = OrdersRequest::count();
        $totalQuotingRequests = OrdersRequest::where('statusRequest', 'quoting')->count();
        $totalQuotedRequests = OrdersRequest::where('statusRequest', 'quoted')->count();
        $totalOrdersPaid = OrdersRequest::whereHas('payments', function ($query) {
            $query->where('status', 'approved');
        })->count();
        $totalOrdersAwaitingPayment = $totalQuotedRequests - $totalOrdersPaid;

        $totalOrdersShipped = OrdersRequest::whereHas('importedproducts', function ($query) {
            $query->whereIn('statusProduct', ['shipped', 'in transit', 'preparing']);
        })->count();

        $totalOrdersDelivered = OrdersRequest::whereHas('importedproducts', function ($query) {
            $query->where('statusProduct', 'delivered');
        })->count();

        $totalOrdersAwaitingShipping = $totalOrdersPaid - $totalOrdersShipped - $totalOrdersDelivered;

        $totalAmountPaid = Payment::where('status', 'approved')->sum('amount');

        // Financial KPIs with optional period filter
        $periodStart = $request->input('period_start');
        $periodEnd   = $request->input('period_end');

        $paidOrdersQuery = OrdersRequest::with(['importedproducts'])
            ->whereHas('payments', function ($q) {
                $q->where('status', 'approved');
            })
            ->when($periodStart, fn($q) => $q->whereDate('created_at', '>=', $periodStart))
            ->when($periodEnd,   fn($q) => $q->whereDate('created_at', '<=', $periodEnd));

        $paidOrders = $paidOrdersQuery->get();

        $financialCA         = 0;
        $financialPurchase   = 0;
        $financialCommission = 0;
        $financialTransit    = 0;

        foreach ($paidOrders as $order) {
            $prod = $order->importedproducts->first();
            if ($prod) {
                $clientTotal          = $prod->client_total_price ?? ($prod->totalPrice ?? 0);
                $financialCA         += $clientTotal + ($order->commission_amount ?? 0) + ($order->transit_client_amount ?? 0);
                $financialPurchase   += ($prod->purchase_price ?? 0) * $prod->qte;
                $financialCommission += $order->commission_amount ?? 0;
                $financialTransit    += $order->transit_internal_margin ?? 0;
            }
        }

        $financialProductProfit = $financialCA - $financialCommission - ($paidOrders->sum(fn($o) => $o->transit_client_amount ?? 0)) - $financialPurchase;
        $financialGlobalProfit  = $financialCA - $financialPurchase - ($paidOrders->sum(fn($o) => $o->transit_client_amount ?? 0) - $financialTransit);

        $sourcingCountriesList = SourcingCountry::all();
        $destinationCountriesList = DestinationCountry::all();
        $notifications = Auth::user()->notifications()->get();

        return view('auth.admin.dashboard', compact(
            'totalSellers',
            'totalAgents',
            'totalRequests',
            'totalQuotingRequests',
            'totalQuotedRequests',
            'totalOrdersPaid',
            'totalOrdersAwaitingPayment',
            'totalOrdersShipped',
            'totalOrdersDelivered',
            'totalOrdersAwaitingShipping',
            'totalAmountPaid',
            'sourcingCountriesList',
            'destinationCountriesList',
            'notifications',
            'periodStart',
            'periodEnd',
            'financialCA',
            'financialPurchase',
            'financialCommission',
            'financialTransit',
            'financialGlobalProfit'
        ));
    }
    // Currency management
    public function currencies()
    {
        $currencies = \App\Models\Currency::orderBy('code')->get();
        $history    = DB::table('currency_rate_history')->orderByDesc('changed_at')->limit(20)->get();
        return view('auth.admin.currencies', compact('currencies', 'history'));
    }

    public function updateCurrencyRate(Request $request, string $code)
    {
        $request->validate(['fcfa_per_unit' => 'required|numeric|min:0.000001']);
        $currency = \App\Models\Currency::where('code', $code)->firstOrFail();

        DB::table('currency_rate_history')->insert([
            'code'          => $code,
            'fcfa_per_unit' => $currency->fcfa_per_unit,
            'changed_by'    => Auth::id(),
            'source'        => 'manual',
            'changed_at'    => now(),
        ]);

        $currency->update([
            'fcfa_per_unit'   => $request->fcfa_per_unit,
            'rate_updated_at' => now(),
        ]);
        \App\Models\Currency::forgetCache();

        return back()->with('success', 'Taux ' . $code . ' mis à jour.');
    }

    public function syncCurrencyRates()
    {
        $exitCode = \Illuminate\Support\Facades\Artisan::call('currency:update-rates');
        $msg      = $exitCode === 0 ? 'Taux synchronisés depuis l\'API.' : 'Échec de la synchronisation — taux conservés.';
        return back()->with($exitCode === 0 ? 'success' : 'error', $msg);
    }

    // View Configuration : admin.configuration
    public function configuration(){

        $agents = User::where('role', '2')->get();
        $comptables = User::where('role', '4')->get();
        $sourcingCountries = SourcingCountry::all();
        $destinationCountries = DestinationCountry::all();
        $paymentOptions = PaymentOption::all();

        return view('auth.admin.configuration', compact('agents', 'comptables', 'paymentOptions','sourcingCountries', 'destinationCountries'));

    }
    // Delete Sourcing Country : 
    public function deleteSourcingCountry($sourcingCountryId){

        $country = SourcingCountry::findOrFail($sourcingCountryId);
        $isUsed = OrdersRequest::where('countryFrom',$country->country_name)->exists();

        // Check if this country is assigned to any requests
        if ($isUsed) {
            return redirect()->back()->withErrors(['msg' => 'This sourcing country cannot be deleted because it is assigned to one or more requests.']);
        }

        $country->delete();

        return redirect()->back()->with('success', 'Sourcing country deleted successfully.');

    }
    // Delete Destination Country
    public function deleteDestinationCountry($destinationCountryId)
    {
        $country = DestinationCountry::findOrFail($destinationCountryId);
        $isUsed = OrdersRequest::where('countryTo',$country->country_name)->exists();

        // Check if the country is linked to any agent
        if ($isUsed) {
            return redirect()->back()->withErrors(['msg' => 'This destination country cannot be deleted because it is assigned to one or more requests.']);
        }

        $country->delete();

        return redirect()->back()->with('success', 'Destination country deleted successfully.');
    }
    // Add Sourcing Country 
    public function addSourcingCountry(Request $request) {

        // Validate the input
        $request->validate([
            'sourcing_country_code' => [
                'required', 
                'string',
                Rule::unique('sourcing_countries', 'country_code')->whereNull('deleted_at'),
            ],
            'sourcing_country_name' => [
                'required',
                'string',
                Rule::unique('sourcing_countries', 'country_name')->whereNull('deleted_at'),
            ],
        ]); 
    
        // Check if a soft-deleted country exists with the same code or name
        $trashedCountryByCode = SourcingCountry::withTrashed()
            ->where('country_code', $request->sourcing_country_code)
            ->first();
    
        $trashedCountryByName = SourcingCountry::withTrashed()
            ->where('country_name', $request->sourcing_country_name)
            ->first();
    
        // If a trashed country with the same code or name exists, restore it
        if ($trashedCountryByCode) {
            $trashedCountryByCode->restore();
            $trashedCountryByCode->update([
                'country_code' => $request->sourcing_country_code,
                'country_name' => $request->sourcing_country_name,
            ]);
        } elseif ($trashedCountryByName) {
            $trashedCountryByName->restore();
            $trashedCountryByName->update([
                'country_code' => $request->sourcing_country_code,
                'country_name' => $request->sourcing_country_name,
            ]);
        } else {
            // If no trashed country exists, create a new one
            SourcingCountry::create([
                'country_code' => $request->sourcing_country_code,
                'country_name' => $request->sourcing_country_name,
            ]);
        }
        return redirect()->back()->with('success', 'Sourcing country added successfully.');
    }
    // Add Destination Country
    public function addDestinationCountry(Request $request)
    {
            // Validate the input
            $request->validate([
                'destination_country_code' => [
                    'required',
                    'string',
                    Rule::unique('destination_countries', 'country_code')->whereNull('deleted_at'),
                ],
                'destination_country_name' => [
                    'required',
                    'string',
                    Rule::unique('destination_countries', 'country_name')->whereNull('deleted_at'),
                ],
            ]);
    
            // Check if a soft-deleted country exists with the same code or name
            $trashedCountryByCode = DestinationCountry::withTrashed()
                ->where('country_code', $request->destination_country_code)
                ->first();
    
            $trashedCountryByName = DestinationCountry::withTrashed()
                ->where('country_name', $request->destination_country_name)
                ->first();
    
            // If a trashed country with the same code or name exists, restore it
            if ($trashedCountryByCode) {
                $trashedCountryByCode->restore();
                $trashedCountryByCode->update([
                    'country_code' => $request->destination_country_code,
                    'country_name' => $request->destination_country_name,
                ]);
            } elseif ($trashedCountryByName) {
                $trashedCountryByName->restore();
                $trashedCountryByName->update([
                    'country_code' => $request->destination_country_code,
                    'country_name' => $request->destination_country_name,
                ]);
            } else {
                // If no trashed country exists, create a new one
                DestinationCountry::create([
                    'country_code' => $request->destination_country_code,
                    'country_name' => $request->destination_country_name,
                ]);
            }
    
            return redirect()->back()->with('success', 'Destination country added successfully.');
    }
    // Delete Payment Option
    public function deletePaymentOption($paymentOptionId){
        $paymentOp = PaymentOption::findOrFail($paymentOptionId);
        $isUsed = Payment::where('paymentMethod',$paymentOptionId)->exists();

        // Check if this country is assigned to any requests
        if ($isUsed) {
            return redirect()->back()->withErrors(['msg' => 'This Payment Option cannot be deleted because it is assigned to one or more payments.']);
        }

        $paymentOp->delete();

        return redirect()->back()->with('success', 'Payment Option deleted successfully.');
    }
    // Add Payment Option
    public function addPaymentOption(Request $req)
    {
        // Validate incoming request
        $req->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Image validation
            'keys' => 'required|array',
            'values' => 'required|array',
            'keys.*' => 'string|max:255', // Each key must be a string
            'values.*' => 'string|max:255', // Each value must be a string
        ]);

        $imagePath = $req->file('image')->store('payment_options', 'public');


        // Prepare details as an associative array
        $details = [];
        foreach ($req->keys as $index => $key) {
            $details[$key] = $req->values[$index];
        }

        // Create a new payment option in the database
        $paymentOption = new PaymentOption();
        $paymentOption->name = $req->name;
        $paymentOption->image = $imagePath;
        $paymentOption->details = json_encode($details);
        $paymentOption->save();
          

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Payment option added successfully.');
    }
    // Store Agent
    public function storeAgent(Request $request)
    {
        // Validate the request
        $request->validate([
            'agent_name' => 'required|string|max:255',
            'agent_email' => 'required|string|email|max:255|unique:users,email',
            'agent_phone' => 'required|string',
            'address' => 'required|string',
            'company_name' => 'required|string',
            'company_information' => 'required|string',
            'sourcing_country' => 'required|exists:sourcing_countries,id',
            'destination_countries' => 'required|array',
            'destination_countries.*' => 'exists:destination_countries,id',
        ]);
    
        // Create the agent with a default password and role
        $agent = new User();
        $agent->name = $request->agent_name;
        $agent->email = $request->agent_email;
        $agent->phone_number = $request->agent_phone;
        $agent->address = $request->address;
        $agent->user_type = 'company';
        $agent->company_name = $request->company_name;
        $agent->company_information = $request->company_information;
        $agent->password = Hash::make('test');
        $agent->role = 2;
        $agent->email_verified_at = now();
        $agent->save();



        // Attach the agent's sourcing country
        $agent->sourcingCountries()->attach($request->sourcing_country);

        // Attach the agent's destination countries
        $agent->destinationCountries()->attach($request->destination_countries);
        return redirect()->route('admin.configuration')->with('success', 'Agent added successfully.');
    }

    public function deleteAgent($id)
    {
        $agent = User::findOrFail($id);
        $isUsed = OrdersRequest::where('agentID',$agent->id)->exists();
        if($isUsed){
            return redirect()->back()->withErrors(['msg' => 'This Agent cannot be deleted because it is assigned to one or more requests.']);
        }
        $agent->delete();
        return redirect()->back()->with('success', 'Agent deleted successfully.');
    }

    public function storeComptable(Request $request)
    {
        $request->validate([
            'comptable_name'  => 'required|string|max:255',
            'comptable_email' => 'required|string|email|max:255|unique:users,email',
            'comptable_phone' => 'required|string',
        ]);

        $plainPassword = Str::random(10);

        $comptable = new User();
        $comptable->name               = $request->comptable_name;
        $comptable->email              = $request->comptable_email;
        $comptable->phone_number       = $request->comptable_phone;
        $comptable->address            = '';
        $comptable->user_type          = 'particular';
        $comptable->password           = Hash::make($plainPassword);
        $comptable->role               = 4;
        $comptable->status             = 'active';
        $comptable->email_verified_at  = now();
        $comptable->save();

        NotificationService::sendWelcomeMail($comptable->email, $comptable->name, $comptable->email, $plainPassword);

        return redirect()->route('admin.configuration')->with('success', __('pages.comptable_created'));
    }

    public function deleteComptable($id)
    {
        $comptable = User::where('id', $id)->where('role', 4)->firstOrFail();
        $comptable->delete();
        return redirect()->back()->with('success', __('pages.comptable_deleted'));
    }

    public function unlinkDestinationCountry($agentId, $destinationCountryId)
    {
        $agent = User::findOrFail($agentId);
        $agent->destinationCountries()->detach($destinationCountryId);

        return redirect()->back()->with('success', 'Destination country unlinked successfully.');
    }

    public function linkSourcingCountry(Request $request, $agentId)
    {
        $request->validate([
            'sourcing_country_id' => 'required|exists:sourcing_countries,id',
        ]);

        $agent = User::findOrFail($agentId);
        $sourcingCountryId = $request->input('sourcing_country_id');

        // Attach the sourcing country to the agent
        $agent->sourcingCountries()->sync([$sourcingCountryId]);

        return redirect()->back()->with('success', 'Sourcing country linked successfully.');
    }

    public function linkDestinationCountries(Request $request)
    {
        // Validate the input
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'destination_countries' => 'required|array',
            'destination_countries.*' => 'exists:destination_countries,id',
        ]);

        // Find the agent
        $agent = User::findOrFail($request->agent_id);

        // Get the current destination countries for the agent
        $currentDestinationCountries = $agent->destinationCountries->pluck('id')->toArray();

        // Filter out countries that are already linked
        $newDestinationCountries = array_diff($request->destination_countries, $currentDestinationCountries);

        // Attach only the new destination countries
        if (!empty($newDestinationCountries)) {
            $agent->destinationCountries()->syncWithoutDetaching($newDestinationCountries);
        }

        return redirect()->back()->with('success', 'Destination countries linked successfully.');
    }

    public function productRequests()
    {
        $agents = User::where('role', '2')->get();
        $sourcingCountries = SourcingCountry::all();
        $destinationCountries = DestinationCountry::all();

        return view('auth.admin.requests',compact('agents','sourcingCountries','destinationCountries'));
    }

    public function filteredProductRequests(Request $request)
    {
        try {
            $query = OrdersRequest::with(['importedproducts', 'payments', 'seller'])
                ->when($request->input('date_from') && $request->input('date_to'), function ($query) use ($request) {
                    $query->whereBetween('created_at', [$request->input('date_from'), $request->input('date_to')]);
                })
                ->when($request->input('agent_id'), function ($query, $agentId) {
                    $query->where('agentID', $agentId);
                })
                ->when($request->input('sourcing_country_name'), function ($query, $countryFrom) {
                    if ($countryFrom === '') {
                        // Handle case where 'All Countries' is selected
                    } else {
                        $query->where('countryFrom', 'like', "%{$countryFrom}%");
                    }
                })
                ->when($request->input('destination_country_name'), function ($query, $countryTo) {
                    if ($countryTo === '') {
                        // Handle case where 'All Countries' is selected
                    } else {
                        $query->where('countryTo', 'like', "%{$countryTo}%");
                    }
                })
                ->when($request->input('status'), function ($query, $status) {
                    if ($status !== '') {
                        $query->where('statusRequest', 'like', "%{$status}%");
                    }
                });
    
            // Total records without pagination
            $totalRecords = $query->count();
    
            // Paginate the results
            $data = $query->skip($request->input('start', 0))
                          ->take($request->input('length', 10))
                          ->get()
                          ->map(function ($row) {
                                $agentName = User::where('id', $row->agentID)
                                    ->where('role', 2) // Ensure the user is an agent
                                    ->value('name');
    
                                return [
                                    'request_id' => $row->requestNO,
                                    'created_at' => $row->created_at->isoFormat('L LTS'),
                                    'updated_at' => $row->updated_at->isoFormat('L LTS'),
                                    'seller' => $row->seller ? $row->seller->name . '<br><small class="text-muted">' . $row->seller->email . '</small>' : '-',
                                    'agent' => $agentName ?? '-',
                                    'product_name' => $row->importedproducts->pluck('productName')->implode(', '),
                                    'quantity' => $row->importedproducts->sum('qte'),
                                    'country_from' => $row->countryFrom,
                                    'country_to' => $row->countryTo,
                                    'request_status' => $row->statusRequest,
                                    'payment_status' => $row->payments->first()->status ?? '-',
                                    'view_url' => url('/admin/requests/' . $row->id),
                                    'delete_url' => route('admin.deleteRequest', $row->id)
                                ];
                            })->toArray();
    
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords, // Update if you have a separate filtered count
                'data' => $data
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error fetching product requests: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }
    }

    public function followUpProductRequest($id)
    {
        // Fetch the order request with its associated imported products and payments
        $orderRequest = OrdersRequest::with(['importedproducts', 'payments', 'seller'])
            ->findOrFail($id);

        $payment = $orderRequest->payments->first();

        // Pass Chating Sys
        $chatThread = ChatThread::with('messages')
            ->where('order_request_id', $id)
            ->first();

        $chatMessages = $chatThread ? $chatThread->messages : [];



        // Pass the data to the view
        return view('auth.admin.viewRequest', compact('orderRequest', 'payment','chatMessages'));

    }
    
    public function updateQuantity(Request $request, $id)
    {
        $request->validate(['qte' => 'required|integer|min:1']);

        $orderRequest = OrdersRequest::findOrFail($id);

        if ($orderRequest->statusRequest !== 'quoting') {
            return back()->withErrors(['msg' => __('pages.quantity_locked')]);
        }

        $product = $orderRequest->importedproducts()->first();

        $product->qte = $request->qte;
        if ($product->unitPrice) {
            $product->totalPrice = $product->unitPrice * $request->qte;
        }
        $product->save();

        return back()->with('success', __('pages.quantity_updated'));
    }

    public function approvePayment($paymentID)
    {
        // Find the request and payment
        $payment = Payment::findOrFail($paymentID);

        $payment->status = 'approved';
        $payment->save();
        
        $this->sendPaymentStatusNotificationToSeller($paymentID,1);
        $this->sendPaymentStatusNotificationToAgent($paymentID,1);
        // Optionally, redirect or provide feedback
        return redirect()->back()->with('success', 'Payment approved successfully.');
    }

    public function disapprovePayment($paymentID)
    {
        // Find the request and payment
        $payment = Payment::findOrFail($paymentID);

        $payment->status = 'disapproved';
        $payment->save();
        
        $this->sendPaymentStatusNotificationToSeller($paymentID,0);
        $this->sendPaymentStatusNotificationToAgent($paymentID,0);

        // Optionally, redirect or provide feedback
        return redirect()->back()->with('success', 'Payment disapproved successfully.');
    }

    public function orders()
    {
        $agents = User::where('role', '2')->get();
        $sourcingCountries = SourcingCountry::all();
        $destinationCountries = DestinationCountry::all();

        return view('auth.admin.orders',compact('agents','sourcingCountries','destinationCountries'));
    }

    public function filteredAdminOrders(Request $request)
    {
        try {
            $query = OrdersRequest::with(['importedproducts', 'payments'])
                ->whereHas('payments', function ($q) {
                    $q->where('status', 'approved');
                })
                ->when($request->input('agent_id'), function ($query, $agentId) {
                    $query->where('agentID', $agentId);
                })
                ->when($request->input('sourcing_country_name'), function ($query, $countryFrom) {
                    $query->where('countryFrom', 'like', "%{$countryFrom}%");
                })
                ->when($request->input('destination_country_name'), function ($query, $countryTo) {
                    $query->where('countryTo', 'like', "%{$countryTo}%");
                })
                ->when($request->input('date_from') && $request->input('date_to'), function ($query) use ($request) {
                    $query->whereBetween('created_at', [$request->input('date_from'), $request->input('date_to')]);
                });
            // Total records without pagination
            $totalRecords = $query->count();

            // Paginate the results
            $data = $query->skip($request->input('start', 0))
                        ->take($request->input('length', 10))
                        ->get()
                        ->map(function ($row) {
                            $importedProduct = $row->importedproducts->first();

                            $agentName = User::where('id', $row->agentID)
                                            ->where('role', 2)
                                            ->value('name');

                            return [
                                'created_at'       => $row->created_at->isoFormat('L LTS'),
                                'agent'            => $agentName,
                                'request_no'       => $row->requestNO,
                                'product_name'     => $importedProduct->productName,
                                'product_url'      => $importedProduct->productURL,
                                'product_image'    => $importedProduct->productImage
                                                     ? asset('storage/' . $importedProduct->productImage)
                                                     : null,
                                'sourcing_country' => $row->countryFrom,
                                'destination_country' => $row->countryTo,
                                'qte' => $importedProduct->qte,
                                'unitPrice' => format_currency($importedProduct->unitPrice),
                                'totalPrice' => format_currency($importedProduct->totalPrice),
                                'weight' => $importedProduct->measurement_type === 'cbm' && $importedProduct->cbm
                                    ? ($importedProduct->cbm + 0) . ' m³'
                                    : ($importedProduct->weight ? ($importedProduct->weight + 0) . ' kg' : '-'),
                                'trackingNumber' => $importedProduct->trackingNumber ?? '-',
                                'carrier' => $importedProduct->carrier ?? '-',
                                'statusProduct' => $importedProduct->statusProduct,
                                'delete_url' => route('admin.deleteOrder', $importedProduct->id)
                            ];
                        })->toArray();

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords, // Update if you have a separate filtered count
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching admin orders: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }
    }


    public function payments(){
        $sellers = User::where('role', '3')->get();
        return view('auth.admin.payment',compact('sellers'));
    }

    public function paymentsData(Request $request)
    {
        try {
            // Build the query
            $query = Payment::with('ordersrequests')
                ->join('users', 'payments.sellerID', '=', 'users.id')
                ->when($request->input('status'), function ($query, $status) {
                    return $query->where('payments.status', 'like', "%{$status}%");
                })
                ->when($request->input('sellerID'), function ($query, $sellerID) {
                    return $query->where('payments.sellerID', $sellerID);
                })
                ->select(
                    'payments.*',
                    'payments.id as payment_id',
                    'payments.created_at as payment_created_at',
                    'users.id as seller_id',
                    'users.code as seller_code',
                    'users.name as seller_name'
                );
        
            // Total records without pagination
            $totalRecords = $query->count();
        
            // Paginate the results
            $data = $query->skip($request->input('start', 0))
                        ->take($request->input('length', 10))
                        ->get()
                        ->map(function ($payment) {
                            $screenshotUrl = asset('storage/' . $payment->screenshot);
                            $actionURL1 = '-';
                            $actionURL2 = '-';
    
                            if ($payment->status === 'pending') {
                                $actionURL1 = url('/admin/requests/approve-payment/' . $payment->payment_id);
                                $actionURL2 = url('/admin/requests/disapprove-payment/' . $payment->payment_id);
                            } elseif ($payment->status === 'approved') {
                                $actionURL2 = url('/admin/requests/disapprove-payment/' . $payment->payment_id);
                            } elseif ($payment->status === 'disapproved') {
                                $actionURL1 = url('/admin/requests/approve-payment/' . $payment->payment_id);
                            }
    
                            return [
                                'payment_id' => $payment->code,
                                'created_at' => \Carbon\Carbon::parse($payment->payment_created_at)->isoFormat('L LTS'),
                                'request_no' => $payment->ordersrequests->requestNO,
                                'seller_id' => $payment->seller_code,
                                'seller_name' => $payment->seller_name,
                                'amount' => format_currency($payment->amount),
                                'payment_option' => $payment->paymentMethod,
                                'screenshot' => '<a class="badge btn bg-gradient-dark" href="' . $screenshotUrl . '" target="_blank">View Document</a>',
                                'status' => $payment->status,
                                'approve' => $actionURL1,
                                'disapprove' => $actionURL2,
                                'delete_url' => route('admin.deletePayment', $payment->payment_id)
                            ];
                        })->toArray();
        
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords, // Update if you have a separate filtered count
                'data' => $data
            ]);
        
        } catch (\Exception $e) {
            Log::error('Error fetching payments data: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }
    }
    
    
    private function sendPaymentStatusNotificationToSeller(int $paymentId, int $isApproved): void
    {
        $payment = Payment::find($paymentId);
        $seller  = User::find($payment->ordersrequests->sellerID);
        if (!$seller) return;

        $key  = $isApproved === 1 ? 'payment_approved_seller' : 'payment_rejected_seller';
        $link = route('seller.followUpProductRequest', ['id' => $payment->requestID]);

        NotificationService::notify($seller, $payment->requestID, $key, ['payment_id' => $paymentId], $link);
    }

    private function sendPaymentStatusNotificationToAgent(int $paymentId, int $isApproved): void
    {
        $payment = Payment::find($paymentId);
        $agent   = User::find($payment->ordersrequests->agentID);
        if (!$agent) return;

        $key  = $isApproved === 1 ? 'payment_approved_agent' : 'payment_rejected_agent';
        $link = route('agent.followUpProductRequest', ['id' => $payment->requestID]);

        NotificationService::notify($agent, $payment->requestID, $key, ['payment_id' => $paymentId], $link, ['db', 'mail', 'sms']);
    }

    // Seller Management
    public function sellers()
    {
        $sellers = User::where('role', 3)->orderBy('created_at', 'desc')->get();
        return view('auth.admin.sellers', compact('sellers'));
    }

    public function storeSeller(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'phone_number'        => 'required|string|max:20',
            'address'             => 'required|string',
            'user_type'           => 'required|in:particular,company',
            'company_name'        => 'required_if:user_type,company|nullable|string|max:255',
            'company_information' => 'nullable|string|max:1000',
        ]);

        $plainPassword = Str::random(12);

        $seller = new User();
        $seller->name                = $request->name;
        $seller->email               = $request->email;
        $seller->phone_number        = $request->phone_number;
        $seller->address             = $request->address;
        $seller->user_type           = $request->user_type;
        $seller->company_name        = $request->user_type === 'company' ? $request->company_name : null;
        $seller->company_information = $request->user_type === 'company' ? $request->company_information : null;
        $seller->password            = Hash::make($plainPassword);
        $seller->role                = 3;
        $seller->status              = 'active';
        $seller->email_verified_at   = now();
        $seller->save();

        NotificationService::sendWelcomeMail($seller->email, $seller->name, $seller->email, $plainPassword);

        return redirect()->back()->with('success', __('pages.seller_created'));
    }

    public function activateSeller($id)
    {
        $seller = User::where('id', $id)->where('role', 3)->firstOrFail();
        $seller->status = 'active';
        $seller->save();

        NotificationService::notify($seller, null, 'account_activated', ['name' => $seller->name], route('login'), ['mail', 'sms']);

        return redirect()->back()->with('success', __('pages.seller_activated'));
    }

    public function blockSeller($id)
    {
        $seller = User::where('id', $id)->where('role', 3)->firstOrFail();
        $seller->status = 'blocked';
        $seller->save();
        return redirect()->back()->with('success', __('pages.seller_blocked'));
    }

    public function unblockSeller($id)
    {
        $seller = User::where('id', $id)->where('role', 3)->firstOrFail();
        $seller->status = 'active';
        $seller->save();

        NotificationService::notify($seller, null, 'account_unblocked', ['name' => $seller->name], route('login'), ['mail', 'sms']);

        return redirect()->back()->with('success', __('pages.seller_unblocked'));
    }

    public function updateSeller(Request $request, $id)
    {
        $seller = User::where('id', $id)->where('role', 3)->firstOrFail();

        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $seller->id,
            'phone_number'        => 'nullable|string|max:30',
            'address'             => 'nullable|string|max:500',
            'user_type'           => 'required|in:particular,company',
            'company_name'        => 'nullable|string|max:255',
            'company_information' => 'nullable|string|max:1000',
            'new_password'        => 'nullable|min:8|confirmed',
        ]);

        $seller->fill($request->only([
            'name', 'email', 'phone_number', 'address',
            'user_type', 'company_name', 'company_information',
        ]));

        if ($request->filled('new_password')) {
            $seller->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $seller->save();

        return redirect()->back()->with('success', __('pages.seller_updated'));
    }

    public function addRequestForSeller()
    {
        $sellers             = User::where('role', 3)->where('status', 'active')->orderBy('name')->get();
        $sourcingCountries   = SourcingCountry::all();
        $destinationCountries = DestinationCountry::all();
        return view('auth.admin.addRequestForSeller', compact('sellers', 'sourcingCountries', 'destinationCountries'));
    }

    public function storeRequestForSeller(Request $request)
    {
        $request->validate([
            'seller_id'      => 'required|exists:users,id',
            'product_name'   => 'required|string|max:255',
            'product_url'    => 'nullable|url',
            'product_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category'       => 'nullable|string',
            'quantity'       => 'required|integer|min:1',
            'countryTo'      => 'required|exists:destination_countries,id',
            'countryFrom'    => 'required|exists:sourcing_countries,id',
            'note'           => 'nullable|string|max:2000',
            'shipping_method'=> 'required|in:Air freight,Ocean freight',
        ]);

        if (!$request->filled('product_url') && !$request->hasFile('product_image')) {
            return redirect()->back()->withErrors(['product_url' => __('pages.url_or_image_required')])->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $countryToName   = DestinationCountry::findOrFail($request->countryTo)->country_name;
                $countryFromName = SourcingCountry::findOrFail($request->countryFrom)->country_name;
                $agentId         = $this->assignAgent($request->countryFrom, $request->countryTo);

                $orderRequest                = new OrdersRequest();
                $orderRequest->sellerID      = $request->seller_id;
                $orderRequest->agentID       = $agentId;
                // requestNO auto-generated by OrdersRequest::boot()
                $orderRequest->statusRequest = 'quoting';
                $orderRequest->countryFrom   = $countryFromName;
                $orderRequest->countryTo     = $countryToName;
                $orderRequest->ShippingMethod = $request->shipping_method;
                $orderRequest->save();

                $productImage = null;
                if ($request->hasFile('product_image')) {
                    $file         = $request->file('product_image');
                    $filename     = 'product_' . $orderRequest->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $productImage = $file->storeAs('product_images', $filename, 'public');
                }

                $importedProduct                       = new ImportedProduct();
                $importedProduct->requestID            = $orderRequest->id;
                $importedProduct->productName          = $request->product_name;
                $importedProduct->productURL           = $request->filled('product_url') ? $request->product_url : null;
                $importedProduct->productImage         = $productImage;
                $importedProduct->productCategory      = $request->category;
                $importedProduct->qte                  = $request->quantity;
                $importedProduct->unitPrice            = 0;
                $importedProduct->totalPrice           = 0;
                $importedProduct->productSpecification = $request->note;
                $importedProduct->statusProduct        = '-';
                $importedProduct->save();

                $allMatchingAgents = $this->getMatchingAgents($request->countryFrom, $request->countryTo);
                foreach ($allMatchingAgents as $agent) {
                    NotificationService::notify(
                        $agent,
                        $orderRequest->id,
                        'new_request_agent',
                        [],
                        route('agent.followUpProductRequest', ['id' => $orderRequest->id])
                    );
                }

                // Always notify the seller that a request was created for them
                $seller = User::find($orderRequest->sellerID);
                if ($seller) {
                    NotificationService::notify(
                        $seller,
                        $orderRequest->id,
                        'new_request_seller',
                        [],
                        route('seller.followUpProductRequest', ['id' => $orderRequest->id])
                    );
                }
            });

            return redirect()->route('admin.productRequests')->with('success', __('pages.request_submitted_for_seller'));
        } catch (\Exception $e) {
            Log::error('Error in storeRequestForSeller (admin): ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function deleteRequest($id)
    {
        $order = OrdersRequest::findOrFail($id);
        $order->importedproducts()->delete();
        $order->payments()->delete();
        $order->delete();
        return response()->json(['success' => true]);
    }

    public function deleteOrder($id)
    {
        ImportedProduct::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function deletePayment($id)
    {
        Payment::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    private function getMatchingAgents(int $countryFromId, int $countryToId)
    {
        return User::where('role', 2)
            ->where('status', 'active')
            ->whereHas('sourcingCountries', fn($q) => $q->where('sourcing_countries.id', $countryFromId))
            ->whereHas('destinationCountries', fn($q) => $q->where('destination_countries.id', $countryToId))
            ->get();
    }

    private function assignAgent($countryFrom, $countryTo)
    {
        $availableAgents = $this->getMatchingAgents($countryFrom, $countryTo);

        if ($availableAgents->count() === 1) {
            return $availableAgents->first()->id;
        } elseif ($availableAgents->count() > 1) {
            return $this->dispatchAgent($availableAgents);
        }
        return null;
    }

    private function dispatchAgent($agents)
    {
        return DB::transaction(function () use ($agents) {
            $workloads = $agents->mapWithKeys(function ($agent) {
                return [$agent->id => OrdersRequest::where('agentID', $agent->id)->count()];
            });
            $minWorkload      = $workloads->min();
            $leastBusyAgents  = $workloads->filter(fn($w) => $w === $minWorkload);
            return $leastBusyAgents->keys()->first();
        });
    }
}