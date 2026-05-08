<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdersRequest;
use App\Models\ImportedProduct;
use App\Models\SourcingCountry;
use App\Models\DestinationCountry;
use App\Models\User;
use App\Models\PaymentOption;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\ChatThread;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class AgentController extends Controller
{
    // This function return data of agent in Dashboard agent : DONE
    public function dashboard()
    {
        $agentID = Auth::id();
        
        $requestsArrived = OrdersRequest::where('agentID', $agentID)->count();
        
        $requestsQuoted = OrdersRequest::where('agentID', $agentID)
            ->where('statusRequest', 'quoted')
            ->count();
        
        $requestsPendingQuoting = OrdersRequest::where('agentID', $agentID)
            ->where('statusRequest', 'quoting')
            ->count();
        
        $totalOrdersPaid = Payment::where('status', 'approved')
            ->whereHas('ordersrequests', function ($query) use ($agentID) {
                $query->where('agentID', $agentID);
            })
            ->count();
        
        $totalOrdersWaitingPayment = $requestsQuoted -  $totalOrdersPaid;

        // Orders Waiting for Shipping: Imported products with status 'null' or '-'
        $ordersWaitingForShipping = ImportedProduct::whereHas('ordersrequests', function ($query) use ($agentID) {
            $query->where('agentID', $agentID)
                  ->whereHas('payments', function ($paymentQuery) {
                      $paymentQuery->where('status', 'approved'); // Ensure the request is paid
                  });
            })->where(function ($query) {
                $query->whereNull('statusProduct')
                    ->orWhere('statusProduct', '-');
            })->count();
        
            
        
        $totalOrdersArrived = ImportedProduct::where('statusProduct', 'delivered')
            ->whereHas('ordersrequests', function ($query) use ($agentID) {
                $query->where('agentID', $agentID);
            })
            ->count();
    
        $shippedOrders = $totalOrdersPaid - $ordersWaitingForShipping - $totalOrdersArrived;
            // Fetch sourcing countries for the agent
        $sourcing_countries = DB::table('agent_sourcing')
        ->join('sourcing_countries', 'agent_sourcing.sourcing_country_id', '=', 'sourcing_countries.id')
        ->where('agent_sourcing.agent_id', $agentID)
        ->pluck('sourcing_countries.country_name')
        ->toArray();

        // Fetch destination countries for the agent
        $destination_countries = DB::table('agent_destinations')
            ->join('destination_countries', 'agent_destinations.destination_country_id', '=', 'destination_countries.id')
            ->where('agent_destinations.agent_id', $agentID)
            ->pluck('destination_countries.country_name')
            ->toArray();

        $notifications = Auth::user()->notifications()->get();

        return view('auth.agent.dashboard', compact(
                                                'requestsArrived',
                                                'requestsQuoted',
                                                'requestsPendingQuoting',
                                                'totalOrdersPaid',
                                                'totalOrdersWaitingPayment',
                                                'ordersWaitingForShipping',
                                                'shippedOrders',
                                                'sourcing_countries',
                                                'destination_countries',
                                                'totalOrdersArrived',
                                                'notifications'));
        
    }

    // This function return table of product requested by seller in request section : DONE
    public function productRequests()
    {
        return view('auth.agent.requests');
    }
    // This function filter data using panel search inserted in blade with ajax : DONE
    public function filteredProductRequests(Request $request)
    {
        try {
            $agentId = auth()->id();

            $query = OrdersRequest::with(['importedproducts', 'payments', 'seller'])
                ->where('agentID', $agentId)
                ->when($request->input('date'), function ($query, $date) {
                    $query->whereDate('created_at', $date);
                })
                ->when($request->input('country_from'), function ($query, $countryFrom) {
                    $query->where('countryFrom', 'like', "%{$countryFrom}%");
                })
                ->when($request->input('country_to'), function ($query, $countryTo) {
                    $query->where('countryTo', 'like', "%{$countryTo}%");
                })
                ->when($request->input('status'), function ($query, $status) {
                    $query->where(function ($q) use ($status) {
                        $q->where('statusRequest', 'like', "%{$status}%")
                            ->orWhereHas('payments', function ($q) use ($status) {
                                $q->where('status', 'like', "%{$status}%");
                            });
                    });
                });

            // Total records without pagination
            $totalRecords = $query->count();

            // Paginate the results
            $data = $query->skip($request->input('start', 0))
                        ->take($request->input('length', 10))
                        ->get()
                        ->map(function ($row) {
                            return [
                                'request_id' => $row->id,
                                'created_at' => $row->created_at->isoFormat('L'),
                                'updated_at' => $row->updated_at->isoFormat('L'),
                                'seller' => $row->seller ? $row->seller->name . '<br><small class="text-muted">' . $row->seller->email . '</small>' : '-',
                                'product_name' => $row->importedproducts->pluck('productName')->implode(', '),
                                'quantity' => $row->importedproducts->sum('qte'),
                                'country_from' => $row->countryFrom,
                                'country_to' => $row->countryTo,
                                'request_status' => $row->statusRequest,
                                'payment_status' => $row->payments->isNotEmpty() ? $row->payments->first()->status : '-',
                                'view_url' => url('/agent/requests/' . $row->id)
                            ];
                        })->toArray();

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords, // Update if you have a separate filtered count
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching agent requests: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }
    }

    // This function show details of requested product made by seller : DONE
    public function followUpProductRequest($id)
    {
        // Fetch the order request with its associated imported products and payments
        $orderRequest = OrdersRequest::with(['importedproducts', 'payments', 'seller'])
            ->findOrFail($id);

        $payment = $orderRequest->payments->first();

        // Check if the payment is 'confirmed'
        $isPaid = $payment && $payment->status === 'approved';
        $Quoted = $orderRequest->statusRequest === 'quoted';

        // Pass Chating Sys
        $chatThread = ChatThread::with('messages')
            ->where('order_request_id', $id)
            ->first();

        $chatMessages = $chatThread ? $chatThread->messages : [];


        // Pass the data to the view
        return view('auth.agent.viewRequest', compact('orderRequest', 'isPaid', 'Quoted', 'payment','chatMessages'));
    }

    

    public function dispatching($id){
            // Retrieve the order request and imported product by ID
        $orderRequest = OrdersRequest::with('importedproducts')->findOrFail($id);
        $importedProduct = $orderRequest->importedproducts->first(); // Assuming there's only one imported product per request

        // Pass data to the view
        return view('auth.agent.dispatch', compact('orderRequest','importedProduct'));
    }
    public function dispatch(Request $request, $id){
        
        $validated = $request->validate([
            'carrier' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'shipping_status' => 'required',
        ]);
    
        try {
            $orderRequest = OrdersRequest::findOrFail($id);
            $importedProduct = $orderRequest->importedproducts->first();
            if ($importedProduct) {
                $importedProduct->update([
                    'carrier' => $validated['carrier'],
                    'trackingNumber' => $validated['tracking_number'],
                    'statusProduct' => $validated['shipping_status'],
                ]);
            }

            $m_seller = User::find($orderRequest->sellerID);
            $this->sendNotificationToSeller($m_seller,$orderRequest->id,$orderRequest->requestNO,'product_status_updated');
            $this->sendNotificationToAdmin($orderRequest->id,$orderRequest->requestNO);
            return redirect()->route('agent.orders')
                             ->with('success', 'Order marked as shipped successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while dispatching the order.');
        }
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

    public function quote(Request $request, $id)
    {
        $validated = $request->validate([
            'unit_price'       => 'required|numeric|min:0.01',
            'measurement_type' => 'required|in:weight,cbm',
            'weight'           => 'nullable|numeric|min:0',
            'cbm'              => 'nullable|numeric|min:0',
            'note'             => 'nullable|string',
        ]);

        if ($validated['measurement_type'] === 'weight' && empty($validated['weight'])) {
            return back()->withErrors(['weight' => __('pages.weight_required')])->withInput();
        }
        if ($validated['measurement_type'] === 'cbm' && empty($validated['cbm'])) {
            return back()->withErrors(['cbm' => __('pages.cbm_required')])->withInput();
        }

        $orderRequest    = OrdersRequest::findOrFail($id);
        $importedProduct = $orderRequest->importedproducts->first();

        if ($importedProduct) {
            $unitPrice  = $validated['unit_price'];
            $totalPrice = $unitPrice * $importedProduct->qte;

            $importedProduct->unitPrice        = $unitPrice;
            $importedProduct->totalPrice       = $totalPrice;
            $importedProduct->agentNote        = $validated['note'] ?? null;
            $importedProduct->measurement_type = $validated['measurement_type'];
            $importedProduct->weight           = $validated['measurement_type'] === 'weight' ? $validated['weight'] : null;
            $importedProduct->cbm              = $validated['measurement_type'] === 'cbm'    ? $validated['cbm']    : null;
            $importedProduct->save();
        }

        // Mark the order request as quoted
        $orderRequest->statusRequest = 'quoted';
        $orderRequest->save();

        $m_seller = User::find($orderRequest->sellerID);
        $this->sendNotificationToSeller($m_seller,$orderRequest->id,$orderRequest->requestNO,'request_quoted');

        return redirect()->route('agent.followUpProductRequest', ['id' => $id])
        ->with('success', 'Quotation submitted successfully.');
    }

    public function orders()
    {
        return view('auth.agent.orders');
    }

    public function filteredOrders(Request $request)
    {
        try {
            $agentId = auth()->id();

            $query = OrdersRequest::with(['importedproducts', 'payments'])
                ->where('agentID', $agentId)
                ->whereHas('payments', function ($q) {
                    $q->where('status', 'approved');
                })
                ->when($request->input('date'), function ($query, $date) {
                    $query->whereDate('created_at', $date);
                })
                ->when($request->input('status'), function ($query, $status) {
                    $query->whereHas('importedproducts', function ($q) use ($status) {
                        $q->where('statusProduct', $status);
                    });
                });

            // Total records without pagination
            $totalRecords = $query->count();

            // Paginate the results
            $data = $query->skip($request->input('start', 0))
                        ->take($request->input('length', 10))
                        ->get()
                        ->map(function ($row) {
                            $importedProduct = $row->importedproducts->first();

                            return [
                                'created_at'    => $row->created_at->isoFormat('L'),
                                'request_no'    => $row->requestNO,
                                'product_name'  => $importedProduct->productName,
                                'product_url'   => $importedProduct->productURL,
                                'product_image' => $importedProduct->productImage
                                                   ? asset('storage/' . $importedProduct->productImage)
                                                   : null,
                                'qte' => $importedProduct->qte,
                                'unitPrice' => $importedProduct->unitPrice + 0,
                                'totalPrice' => $importedProduct->totalPrice + 0,
                                'weight' => $importedProduct->measurement_type === 'cbm' && $importedProduct->cbm
                                    ? ($importedProduct->cbm + 0) . ' m³'
                                    : ($importedProduct->weight ? ($importedProduct->weight + 0) . ' kg' : '-'),
                                'trackingNumber' => $importedProduct->trackingNumber ?? '-',
                                'carrier' => $importedProduct->carrier ?? '-',
                                'statusProduct' => $importedProduct->statusProduct,
                                'dispatch_button' => '<a href="' . url('/agent/orders/dispatching/' . $row->id) . '" class="btn btn-primary">Dispatch Now</a>',
                            ];
                        })->toArray();

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords, // Update if you have a separate filtered count
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching agent orders: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }

    }

    // Send Notification To Seller
    public function sendNotificationToSeller($seller, $requestID, $requestNO, $sbjct): void
    {
        $link = route('seller.followUpProductRequest', ['id' => $requestID]);

        $key = match ($sbjct) {
            'request_quoted'         => 'request_quoted_seller',
            'product_status_updated' => 'product_updated_seller',
            default                  => null,
        };

        if ($key === null) return;

        NotificationService::notify($seller, $requestID, $key, ['request_no' => $requestNO], $link);
    }

    // Send Notification To Admin
    public function sendNotificationToAdmin($requestID, $requestNO): void
    {
        $admin = User::where('role', '1')->first();
        if (!$admin) return;

        NotificationService::notify(
            $admin,
            $requestID,
            'product_updated_admin',
            ['request_no' => $requestNO],
            route('admin.followUpProductRequest', ['id' => $requestID]),
            ['db']
        );
    }

    // Seller Management
    public function sellers()
    {
        $sellers = User::where('role', 3)->orderBy('created_at', 'desc')->get();
        return view('auth.agent.sellers', compact('sellers'));
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
            $seller->password = Hash::make($request->new_password);
        }

        $seller->save();

        return redirect()->back()->with('success', __('pages.seller_updated'));
    }

    public function addRequestForSeller()
    {
        $sellers              = User::where('role', 3)->where('status', 'active')->orderBy('name')->get();
        $sourcingCountries    = SourcingCountry::all();
        $destinationCountries = DestinationCountry::all();
        return view('auth.agent.addRequestForSeller', compact('sellers', 'sourcingCountries', 'destinationCountries'));
    }

    public function storeRequestForSeller(Request $request)
    {
        $request->validate([
            'seller_id'       => 'required|exists:users,id',
            'product_name'    => 'required|string|max:255',
            'product_url'     => 'nullable|url',
            'product_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category'        => 'nullable|string',
            'quantity'        => 'required|integer|min:1',
            'countryTo'       => 'required|exists:destination_countries,id',
            'countryFrom'     => 'required|exists:sourcing_countries,id',
            'note'            => 'nullable|string|max:2000',
            'shipping_method' => 'required|in:Air freight,Ocean freight',
        ]);

        if (!$request->filled('product_url') && !$request->hasFile('product_image')) {
            return redirect()->back()->withErrors(['product_url' => __('pages.url_or_image_required')])->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $countryToName   = DestinationCountry::findOrFail($request->countryTo)->country_name;
                $countryFromName = SourcingCountry::findOrFail($request->countryFrom)->country_name;

                $orderRequest                 = new OrdersRequest();
                $orderRequest->sellerID       = $request->seller_id;
                $orderRequest->agentID        = Auth::id();
                $orderRequest->requestNO      = uniqid();
                $orderRequest->statusRequest  = 'quoting';
                $orderRequest->countryFrom    = $countryFromName;
                $orderRequest->countryTo      = $countryToName;
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
            });

            return redirect()->route('agent.productRequests')->with('success', __('pages.request_submitted_for_seller'));
        } catch (\Exception $e) {
            Log::error('Error in storeRequestForSeller (agent): ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
