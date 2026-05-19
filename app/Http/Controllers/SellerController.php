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
use Illuminate\Support\Facades\Mail;
use App\Mail\ReclamationMail;
use App\Services\NotificationService;


class SellerController extends Controller
{
    // This function return data of seller in Dashboard seller : DONE
    public function dashboard()
    {
        $sellerID = Auth::id();
        
        // Get total number of requests
        $totalRequests = OrdersRequest::where('sellerID', $sellerID)->count();

        // Pending Requests: Requests related to seller that contain quoting
        $pendingRequests = OrdersRequest::where('sellerID', $sellerID)
                            ->where('statusRequest', 'quoting') // Assuming 'quoting' is the status for pending requests
                            ->count();
        $quotedRequests = $totalRequests - $pendingRequests;
        
        // Get total number of products paid
        $paidProducts = ImportedProduct::whereHas('ordersrequests', function ($query) use ($sellerID) {
            $query->where('sellerID', $sellerID);
        })->whereHas('ordersrequests.payments', function ($query) {
            $query->where('status', 'approved');
        })->count();

        // Awaiting Payment
        $awaitingPayment = $totalRequests-$pendingRequests-$paidProducts;

        // Orders Waiting for Shipping: Imported products with status 'null' or '-'
        $awaitingShipping = ImportedProduct::whereHas('ordersrequests', function ($query) use ($sellerID) {
            $query->where('sellerID', $sellerID)
                  ->whereHas('payments', function ($paymentQuery) {
                      $paymentQuery->where('status', 'approved'); // Ensure the request is paid
                  });
            })->where(function ($query) {
                $query->whereNull('statusProduct')
                    ->orWhere('statusProduct', '-');
            })->count();
        
        
        // Shipped Orders: Products with status 'preparing', 'in transit', or 'shipped'
        $shippedOrders = ImportedProduct::whereHas('ordersrequests', function ($query) use ($sellerID) {
                            $query->where('sellerID', $sellerID);
                        })->whereIn('statusProduct', ['preparing', 'in transit', 'shipped'])
                        ->count();

        // Arrived Products: Products with status 'delivered'
        $arrivedProducts = ImportedProduct::whereHas('ordersrequests', function ($query) use ($sellerID) {
                            $query->where('sellerID', $sellerID);
                        })->where('statusProduct', 'delivered')
                            ->count();
        
        
                            
        // Get total payment amount
        $paymentsMade = Payment::where('sellerID', $sellerID)->where('status', 'approved')->sum('amount');

        // Notification
        $notifications = Auth::user()->notifications()->get();

        return view('auth.seller.dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'quotedRequests',
            'paidProducts',
            'awaitingPayment',
            'awaitingShipping',
            'shippedOrders',
            'arrivedProducts',
            'paymentsMade',
            'notifications'
        ));
    }
    // This function return table of product requested by seller in request section : DONE
    public function productRequests()
    {
        return view('auth.seller.requests');
    }
    // This function filter data using panel search inserted in blade with ajax : DONE
    public function filteredProductRequests(Request $request)
    {
        try {
            $sellerId = auth()->id();
    
            $query = OrdersRequest::with(['importedproducts', 'payments'])
                ->where('sellerID', $sellerId)
                
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
                            // Ensure we are only using the OrdersRequest fields, and not those from related models
                            return [
                                'request_id' => $row->requestNO,
                                'created_at' => $row->created_at->isoFormat('L LTS'), // Created date from OrdersRequest
                                'updated_at' => $row->updated_at->isoFormat('L LTS'), // Updated date from OrdersRequest
                                'product_name' => $row->importedproducts->pluck('productName')->implode(', '),
                                'quantity' => $row->importedproducts->sum('qte'),
                                'country_from' => $row->countryFrom,
                                'country_to' => $row->countryTo,
                                'request_status' => $row->statusRequest,
                                'payment_status' => $row->payments->isNotEmpty() ? $row->payments->first()->status : '-', // Check if payments exist before accessing
                                'view_url' => url('/seller/requests/' . $row->id)
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
    // This function return view to add request : DONE
    public function addProductRequests(){
        $sourcingCountries = SourcingCountry::all();  // Replace with actual model and table if different
        $destinationCountries = DestinationCountry::all();  // Replace with actual model and table if different    
        return view('auth.seller.addRequests', compact('sourcingCountries', 'destinationCountries'));
    }
    // This function store the request added : DONE
    public function storeProductRequests(Request $request)
    {
        // Convert quantity and country IDs to integers
        $request->merge([
            'quantity' => (int) $request->input('quantity'),
            'countryTo' => (int) $request->input('countryTo'),
            'countryFrom' => (int) $request->input('countryFrom'),
        ]);
    
        $validator = Validator::make($request->all(), [
            'product_name'    => 'required|string|max:255',
            'product_url'     => 'nullable|url|max:2048',
            'product_image'   => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'category'        => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
            'countryTo'       => 'required|integer|exists:destination_countries,id',
            'countryFrom'     => 'required|integer|exists:sourcing_countries,id',
            'shipping_method' => 'required|string|max:255',
        ])->after(function ($v) use ($request) {
            if (!$request->filled('product_url') && !$request->hasFile('product_image')) {
                $v->errors()->add('product_url', __('pages.url_or_image_required'));
            }
        });
    
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            // Use a transaction to ensure atomicity
            DB::transaction(function () use ($request) {
                // Convert country IDs to names
                $countryToName = DestinationCountry::findOrFail($request->countryTo)->country_name;
                $countryFromName = SourcingCountry::findOrFail($request->countryFrom)->country_name;
                $asAget = $this->assignAgent($request->countryFrom, $request->countryTo);
                // Create a new order request
                $orderRequest = new OrdersRequest();
                $orderRequest->sellerID = Auth::id(); // Assuming the seller is the authenticated user
                $orderRequest->agentID = $asAget; // Assign the selected agent
                // requestNO auto-generated by OrdersRequest::boot()
                $orderRequest->statusRequest = 'quoting'; // Set initial status
                $orderRequest->countryFrom = $countryFromName; // Use the country name
                $orderRequest->countryTo = $countryToName; // Use the country name
                $orderRequest->ShippingMethod = $request->shipping_method;
                $orderRequest->save();
    
                // Handle product image upload
                $productImage = null;
                if ($request->hasFile('product_image')) {
                    $file = $request->file('product_image');
                    $filename = 'product_' . $orderRequest->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $productImage = $file->storeAs('product_images', $filename, 'public');
                }

                $importedProduct = new ImportedProduct();
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
                if ($allMatchingAgents->isNotEmpty()) {
                    foreach ($allMatchingAgents as $agent) {
                        $this->sendNotificationToAgent($agent, $orderRequest->id);
                    }
                } else {
                    $this->sendNoAgentNotificationToAdmin($orderRequest->id, $countryFromName, $countryToName);
                }
                
                
                
    
            });
    
            // Redirect with success message
            return redirect()->route('seller.productRequests')->with('success', __('pages.request_submitted'));

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in storeProductRequests: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', __('pages.request_submit_error'));
        }
    }
    private function getMatchingAgents(int $countryFromId, int $countryToId)
    {
        return User::where('role', 2)
            ->where('status', 'active')
            ->whereHas('sourcingCountries', fn($q) => $q->where('sourcing_countries.id', $countryFromId))
            ->whereHas('destinationCountries', fn($q) => $q->where('destination_countries.id', $countryToId))
            ->get();
    }

    // This function assign request to agent with low work : DONE
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
    // This function complete the assignement : DONE
    private function dispatchAgent($agents){
        return DB::transaction(function () use ($agents)
        {
            // Fetch the current workload for each agent
            $workloads = $agents->mapWithKeys(function ($agent) {
                $requestCount = OrdersRequest::where('agentID', $agent->id)->count();
                return [$agent->id => $requestCount];
            });
    
            // Find the agent with the minimum workload
            $minWorkload = $workloads->min();
            $leastBusyAgents = $workloads->filter(function ($workload) use ($minWorkload) {
                return $workload === $minWorkload;
            });
    
            // Select the least busy agent (if there are multiple, you can choose based on additional criteria)
            return $leastBusyAgents->keys()->first(); // Return the first agent ID with the minimum workload
        });
    }
    // This function show details of requested product made by seller : DONE
    public function followUpProductRequest($id)
    {
        // Fetch the order request with its associated imported products and payments
        $orderRequest = OrdersRequest::with(['importedproducts', 'payments'])
            ->findOrFail($id); // Fetch the request or fail if not found

        // Fetch the first (and only) payment
        $payment = $orderRequest->payments->first();

        // Check if the payment is 'confirmed'
        $isPaid = $payment && $payment->status === 'approved';

        // Fetch payment options only if the request is not paid
        $paymentOptions = !$isPaid ? PaymentOption::all() : null;

        // Pass Chating Sys
        $chatThread = ChatThread::with('messages')
                        ->where('order_request_id', $id)
                        ->first();
        
        $chatMessages = $chatThread ? $chatThread->messages : [];

        // Pass the data to the view
        return view('auth.seller.viewRequest', compact('orderRequest', 'isPaid', 'payment','chatMessages'));
    }
    // This function retreive Orders Data
    public function filteredOrders(Request $request)
    {
        try {
            $sellerId = auth()->id();

            $query = OrdersRequest::with(['importedproducts', 'payments'])
                ->where('sellerID', $sellerId)
                ->whereHas('payments', function ($query) {
                    $query->where('status', 'approved'); // Only include orders with approved payments
                })
                ->when($request->input('date'), function ($query, $date) {
                    $query->whereDate('created_at', $date);
                })
                ->when($request->input('country_from'), function ($query, $countryFrom) {
                    $query->where('countryFrom', 'like', "%{$countryFrom}%");
                })
                ->when($request->input('country_to'), function ($query, $countryTo) {
                    $query->where('countryTo', 'like', "%{$countryTo}%");
                })
                ->when($request->input('statusProduct'), function ($query, $statusProduct) {
                    $query->whereHas('importedproducts', function ($q) use ($statusProduct) {
                        $q->where('statusProduct', 'like', "%{$statusProduct}%");
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
                        'requested_at'    => $row->created_at->isoFormat('L LTS'),
                        'request_no'      => $row->requestNO,
                        'product_name'    => $row->importedproducts->pluck('productName')->implode(', '),
                        'quantity'        => $row->importedproducts->sum('qte'),
                        'total_price'     => format_currency($row->importedproducts->sum('totalPrice')),
                        'product_url'     => $row->importedproducts->first()?->productURL,
                        'product_image'   => $row->importedproducts->first()?->productImage
                                             ? asset('storage/' . $row->importedproducts->first()->productImage)
                                             : null,
                        'tracking_number' => $row->importedproducts->pluck('trackingNumber')->implode(', '),
                        'carrier'         => $row->importedproducts->pluck('carrier')->implode(', '),
                        'statusProduct'   => $row->importedproducts->pluck('statusProduct')->implode(', '),
                        'country_from'    => $row->countryFrom,
                        'country_to'      => $row->countryTo,
                        'shipping_method' => $row->ShippingMethod,
                    ];
                })->toArray();

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords, // Update if separate filtered count
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }
    }
    public function getOrders()
    {
        return view('auth.seller.orders');
    }
    public function reclamation(){
        $requests = OrdersRequest::all();
        $payments = Payment::with('ordersrequests')->get();
        $orders = OrdersRequest::whereHas('payments', function ($query) {
            $query->where('status', 'approved');
        })->get();
        return view('auth.seller.reclamation', compact('requests', 'payments', 'orders'));
    }
    public function reclamationSend(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'message' => 'required|string',
            'request_id' => 'nullable|exists:ordersrequests,id',
            'payment_id' => 'nullable|exists:payments,id',
            'order_id' => 'nullable|exists:ordersrequests,id',
        ]);
    
        // Retrieve data from the request
        $type = $validated['type'];
        $messageContent = $validated['message'];
    
        // Initialize variables for related items and emails
        $relatedItem = null;
        $relatedItemIdentifier = null;
        $ccEmails = []; // Email CC list
    
        // Determine the related item based on the type
        if ($type === 'request' || $type === 'order') {
            $relatedItem = OrdersRequest::find($validated['request_id']);
            $relatedItemIdentifier = $relatedItem?->requestNO;
        } elseif ($type === 'payment') {
            $relatedItem = Payment::find($validated['payment_id']);
            $relatedItemIdentifier = $relatedItem?->ordersrequests?->requestNO;
        }
    
        // Fetch the admin email
        $adminEmail = User::where('role', 1)->first()?->email;
    
        // Determine the agent's email based on the related item
        $agentID = $relatedItem?->agentID ?? ($type === 'payment' ? $relatedItem?->ordersrequest?->agentID : null);
        if ($agentID) {
            $agent = User::find($agentID);
            if ($agent) {
                $ccEmails[] = $agent->email; // Add agent email to CC list
            }
        }
    
        // Send the reclamation email
        try {
            Mail::to($adminEmail)
                ->cc(array_filter($ccEmails)) // Filter out null values
                ->send(new ReclamationMail(
                    $type,
                    $messageContent,
                    $relatedItemIdentifier,
                    auth()->user()->email // Email of the authenticated user
                ));
    
            // Redirect back with a success message
            return redirect()->back()->with('success', __('pages.reclamation_sent'));
        } catch (\Exception $e) {
            // Handle email sending errors
            return redirect()->back()->withErrors(__('pages.reclamation_send_error'));
        }
    }
    
      
    // This function return blade of payement : DONE
    public function showPaymentMethods($orderRequestId)
    {
        // Fetch the order request
        $orderRequest = OrdersRequest::findOrFail($orderRequestId);

        // Fetch payment options
        $paymentOptions = PaymentOption::all();

        // Fetch existing payment if it exists
        $existingPayment = $orderRequest->payments()->first();

        // Pass data to the view
        return view('auth.seller.pay', compact('orderRequest', 'paymentOptions'));
    }
    // This function store the payment info : DONE
    public function payOrder(Request $request, $orderRequestId){
        // Validate request
        $request->validate([
            'payment_option_id' => 'required|exists:payment_options,id',
            'screenshot' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Adjust as needed
        ]);

        // Fetch the order request
        $orderRequest = OrdersRequest::findOrFail($orderRequestId);
        $product = $orderRequest->importedproducts()->first();

        // Check if a payment record already exists
        $payment = Payment::where('requestID', $orderRequest->id)->first();

        // Update existing payment or create a new one
        if (!$payment) {
            // Create a new payment record
            $payment = new Payment();
            $payment->sellerID = $orderRequest->sellerID;
            $payment->requestID = $orderRequest->id;
            $payment->amount = $product->totalPrice;
        }
        $payment->paymentMethod = $request->payment_option_id;
        $payment->status = 'pending'; // Default to pending

        // Handle file upload and renaming
        if ($request->hasFile('screenshot')) {
            // Delete old screenshot if it exists
            if ($payment->screenshot && Storage::disk('public')->exists($payment->screenshot)) {
                Storage::disk('public')->delete($payment->screenshot);
            }

            $file = $request->file('screenshot');
            // Generate a new filename with the order request ID and original extension
            $filename = 'order_screen_' . $orderRequest->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');
            $payment->screenshot = $path;
        }

        // Save the payment record
        $payment->save();
        $this->sendNotificationToAdmin($orderRequestId);

        // Redirect with success message
        return redirect()->route('seller.productRequests')
                        ->with('success', __('pages.payment_submitted'));
    }
    // This function shows payments history : DONE
    public function paymentHistory(){
        return view('auth.seller.payment');
    }
    // This function filter data using panel search inserted in blade with ajax : DONE
    public function filteredPaymentHistory(Request $request)
    {
        try {
            $sellerId = auth()->id();
    
            // Build the query with relationships
            $query = Payment::where('sellerID', $sellerId)
                ->with(['ordersrequests.importedproducts']) // Load related data
                ->when($request->input('start_date'), function ($query, $start_date) {
                    $query->whereDate('created_at', '>=', $start_date);
                })
                ->when($request->input('end_date'), function ($query, $end_date) {
                    $query->whereDate('created_at', '<=', $end_date);
                })
                ->when($request->input('status'), function ($query, $status) {
                    $query->where('status', 'like', "%{$status}%");
                });
    
            // Total records without pagination
            $totalRecords = $query->count();
    
            // Paginate the results
            $data = $query->skip($request->input('start', 0))
                ->take($request->input('length', 10))
                ->get()
                ->map(function ($payment) {
                    $orderRequest = $payment->ordersrequests;
                    $product = $orderRequest->importedproducts->first(); // Always one product
    
                    return [
                        'payment_id' => $payment->code,
                        'created_at' => $payment->created_at->isoFormat('L LTS'), // Payment creation date
                        'request_no' => $orderRequest->requestNO,
                        'product_name' => $product->productName,
                        'amount' => $payment->amount,
                        'status' => $payment->status,
                    ];
                })->toArray();
    
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords, // Update if you have a separate filtered count
                'data' => $data,
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error fetching payment history: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }
    }
    // Send Notification To Agent
    public function sendNotificationToAgent($agent, $requestID): void
    {
        NotificationService::notify(
            $agent,
            $requestID,
            'new_request_agent',
            [],
            route('agent.followUpProductRequest', ['id' => $requestID])
        );
    }

    // Send Notification To Admin
    public function sendNotificationToAdmin($requestID): void
    {
        $admin = User::where('role', '1')->first();
        if (!$admin) return;

        $requestNO = OrdersRequest::find($requestID)?->requestNO ?? $requestID;

        NotificationService::notify(
            $admin,
            $requestID,
            'payment_submitted_admin',
            ['request_no' => $requestNO],
            route('admin.followUpProductRequest', ['id' => $requestID]),
            ['db']
        );
    }

    private function sendNoAgentNotificationToAdmin(int $requestID, string $countryFrom, string $countryTo): void
    {
        $admin = User::where('role', '1')->first();
        if (!$admin) return;

        $requestNO = OrdersRequest::find($requestID)?->requestNO ?? $requestID;

        NotificationService::notify(
            $admin,
            $requestID,
            'new_request_admin',
            ['request_no' => $requestNO, 'country_from' => $countryFrom, 'country_to' => $countryTo],
            route('admin.followUpProductRequest', ['id' => $requestID]),
            ['db']
        );
    }

    public function pending()
    {
        if (Auth::user()->status !== 'pending') {
            return redirect()->route('seller.dashboard');
        }
        return view('auth.seller.pending');
    }

    public function profile()
    {
        return view('auth.seller.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $user->id,
            'phone_number'        => 'nullable|string|max:30',
            'address'             => 'nullable|string|max:500',
            'user_type'           => 'required|in:particular,company',
            'company_name'        => 'nullable|string|max:255',
            'company_information' => 'nullable|string|max:1000',
        ]);

        $user->fill($request->only([
            'name', 'email', 'phone_number', 'address',
            'user_type', 'company_name', 'company_information',
        ]));
        $user->save();

        return back()->with('success', __('pages.profile_updated'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'          => 'required',
            'password'                  => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('pages.wrong_current_password')])->withInput();
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return back()->with('success', __('pages.password_updated'));
    }
}