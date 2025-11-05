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
use App\Notifications\UserNotification;
use App\Models\ChatThread;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\SmsNotification;
use Illuminate\Support\Facades\Notification;


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

            $query = OrdersRequest::with(['importedproducts', 'payments'])
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
                                'request_id' => $row->id, // Add requestID
                                'created_at' => $row->created_at->format('Y-m-d'),
                                'updated_at' => $row->updated_at->format('Y-m-d'),
                                'product_name' => $row->importedproducts->pluck('productName')->implode(', '),
                                'quantity' => $row->importedproducts->sum('qte'),
                                'country_from' => $row->countryFrom,
                                'country_to' => $row->countryTo,
                                'request_status' => $row->statusRequest,
                                'payment_status' => $row->payments->isNotEmpty() ? $row->payments->first()->status : '-', // Check if payments exist before accessing
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
        $orderRequest = OrdersRequest::with(['importedproducts', 'payments'])
            ->findOrFail($id); // Fetch the request or fail if not found

        // Fetch the first (and only) payment
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
    public function quote(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'unit_price' => 'required|numeric|min:0.01',
            'weight' => 'required|string', // Allow null or numeric values
            'note' => 'nullable|string', // Allow null or string values
        ]);

        // Find the order request
        $orderRequest = OrdersRequest::findOrFail($id);

        // Retrieve the first associated imported product
        $importedProduct = $orderRequest->importedproducts->first();

        if ($importedProduct) {
            // Calculate total price
            $unitPrice = $validated['unit_price'];
            $quantity = $importedProduct->qte; // Assuming qte is stored in the imported product
            $totalPrice = $unitPrice * $quantity;

            // Update the imported product
            $importedProduct->unitPrice = $unitPrice;
            $importedProduct->totalPrice = $totalPrice;
            $importedProduct->agentNote = $validated['note'] ?? '-';
            $importedProduct->weight = $validated['weight'] ?? '-';
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
                                'created_at' => $row->created_at->format('Y-m-d'),
                                'request_no' => $row->requestNO,
                                'product_name' => $importedProduct->productName,
                                'product_url' => $importedProduct->productURL,
                                'qte' => $importedProduct->qte,
                                'unitPrice' => $importedProduct->unitPrice,
                                'totalPrice' => $importedProduct->totalPrice,
                                'weight' => $importedProduct->weight,
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
    public function sendNotificationToSeller($seller,$requestID,$requestNO,$sbjct){
        
        $subject='';
        $message='';
        $link='';

        if($sbjct === 'request_quoted'){
            $subject = 'Request Quoted';
            $message='Your request NO : '.$requestNO.' was quoted. Please make the payment.';
            $link = route('seller.followUpProductRequest', ['id' => $requestID]);
        }

        if($sbjct === 'product_status_updated'){
            $subject = 'Product Status Updated';
            $message='Status of product in request NO : '.$requestNO.' has been updated. see more details.';
            $link = route('seller.followUpProductRequest', ['id' => $requestID]);
        }
        $sms = $subject.' : '.$message;
        $this->sendMailNotificationToSeller($seller,$subject,$message,$link);
        $this->sendNotification($seller,$sms);
        $seller->notify(new UserNotification(
            $requestID,
            $subject,
            $message,
            $link,
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

    // Send Notification To Admin
    public function sendNotificationToAdmin($requestID,$requestNO){
        
        $subject = 'Product Status Updated';
        $message='Status of product in request NO : '.$requestNO.' has been updated. see more details.';
        $link = route('admin.followUpProductRequest', ['id' => $requestID]);
        
        $admin = User::where('role', '1')->first();

        $admin->notify(new UserNotification(
            $requestID,
            $subject,
            $message,
            $link,
        ));
    }

    public function sendNotification($seller,$message)
    {
        $recipients = [$seller->phone_number];
        Notification::route('sms', $recipients)->notify(new SmsNotification($recipients, $message));
    }

}
