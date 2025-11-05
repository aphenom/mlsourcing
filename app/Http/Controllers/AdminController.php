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
use App\Notifications\UserNotification;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\SmsNotification;
use Illuminate\Support\Facades\Notification;


class AdminController extends Controller
{
    // Dashboard Admin : admin.dashboard
    public function dashboard(){
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
            'notifications'
        ));

    }
    // View Configuration : admin.configuration
    public function configuration(){

        $agents = User::where('role', '2')->get();
        $sourcingCountries = SourcingCountry::all();
        $destinationCountries = DestinationCountry::all();
        $paymentOptions = PaymentOption::all();

        return view('auth.admin.configuration', compact('agents', 'paymentOptions','sourcingCountries', 'destinationCountries'));

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
            $query = OrdersRequest::with(['importedproducts', 'payments'])
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
                                    'created_at' => $row->created_at->format('Y-m-d'),
                                    'updated_at' => $row->updated_at->format('Y-m-d'),
                                    'agent' => $agentName ?? '-',
                                    'product_name' => $row->importedproducts->pluck('productName')->implode(', '),
                                    'quantity' => $row->importedproducts->sum('qte'),
                                    'country_from' => $row->countryFrom,
                                    'country_to' => $row->countryTo,
                                    'request_status' => $row->statusRequest,
                                    'payment_status' => $row->payments->first()->status ?? '-',
                                    'view_url' => url('/admin/requests/' . $row->id)
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
        $orderRequest = OrdersRequest::with(['importedproducts', 'payments'])
            ->findOrFail($id); // Fetch the request or fail if not found

        // Fetch the first (and only) payment
        $payment = $orderRequest->payments->first();
        
        // Pass Chating Sys
        $chatThread = ChatThread::with('messages')
            ->where('order_request_id', $id)
            ->first();

        $chatMessages = $chatThread ? $chatThread->messages : [];



        // Pass the data to the view
        return view('auth.admin.viewRequest', compact('orderRequest', 'payment','chatMessages'));

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
                                'created_at' => $row->created_at->format('Y-m-d'),
                                'agent' => $agentName,
                                'request_no' => $row->requestNO,
                                'product_name' => $importedProduct->productName,
                                'product_url' => $importedProduct->productURL,
                                'sourcing_country' => $row->countryFrom,
                                'destination_country' => $row->countryTo,
                                'qte' => $importedProduct->qte,
                                'unitPrice' => $importedProduct->unitPrice,
                                'totalPrice' => $importedProduct->totalPrice,
                                'weight' => $importedProduct->weight,
                                'trackingNumber' => $importedProduct->trackingNumber ?? '-',
                                'carrier' => $importedProduct->carrier ?? '-',
                                'statusProduct' => $importedProduct->statusProduct
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
                                'payment_id' => $payment->payment_id,
                                'created_at' => $payment->payment_created_at,
                                'request_no' => $payment->ordersrequests->requestNO,
                                'seller_id' => $payment->seller_id,
                                'seller_name' => $payment->seller_name,
                                'amount' => $payment->amount,
                                'payment_option' => $payment->paymentMethod,
                                'screenshot' => '<a class="badge btn bg-gradient-dark" href="' . $screenshotUrl . '" target="_blank">View Document</a>',
                                'status' => $payment->status,
                                'approve' => $actionURL1,
                                'disapprove' => $actionURL2
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
    
    
    public function sendPaymentStatusNotificationToSeller($paymentId, $isApproved)
    {
        // Retrieve the Payment instance
        $m_payment = Payment::find($paymentId);
        $m_requestID = $m_payment->requestID;
        $m_sellerID = $m_payment->ordersrequests->sellerID;
        $m_seller = User::find($m_sellerID);
    
        $m_subject = '';
        $m_message = '';
        
        // Prepare subject and message based on approval status
        if ($isApproved === 1) {
            $m_subject = 'Payment Approved';
            $m_message = 'Congratulations, your payment with ID: ' . $paymentId . ' has been approved.';
        } else {
            $m_subject = 'Payment Rejected';
            $m_message = 'Your payment with ID: ' . $paymentId . ' has been rejected - See why.';
        }

        $m_link = route('seller.followUpProductRequest', ['id' => $m_requestID]);
        $sms = $m_subject.' : '.$m_message;
        $this->sendNotification($m_seller,$sms);
        $this->sendMailNotificationToSeller($m_seller,$m_subject,$m_message,$m_link);
        // Send the notification to the seller
        $m_seller->notify(new UserNotification(
            $m_requestID,
            $m_subject,
            $m_message,
            $m_link
        ));
    }

    public function sendMailNotificationToSeller($seller,$subject,$message,$link){
        $sellerMail = $seller->email;
        Mail::to($sellerMail)->send(new NotificationMail(
                    $subject,
                    $message,
                    $link
            ));
    }
    public function sendNotification($seller,$message)
    {
        $recipients = [$seller->phone_number];
        Notification::route('sms', $recipients)->notify(new SmsNotification($recipients, $message));
    }
    public function sendPaymentStatusNotificationToAgent($paymentId, $isApproved)
    {
        // Retrieve the Payment instance
        $m_payment = Payment::find($paymentId);
        $m_requestID = $m_payment->requestID;
        $m_agentID = $m_payment->ordersrequests->agentID;
        $m_agent = User::find($m_agentID);
    
        $m_subject = '';
        $m_message = '';
        
        // Prepare subject and message based on approval status
        if ($isApproved === 1) {
            $m_subject = 'Payment Approved';
            $m_message = 'Payment with ID: ' . $paymentId . ' has been approved. Please ship the product';
        } else {
            $m_subject = 'Payment Rejected';
            $m_message = 'Payment with ID: ' . $paymentId . ' has been rejected.';
        }
    
        // Send the notification to the agent
        $m_agent->notify(new UserNotification(
            $m_requestID,
            $m_subject,
            $m_message,
            route('agent.followUpProductRequest', ['id' => $m_requestID]),
        ));
    }

}