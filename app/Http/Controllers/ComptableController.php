<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Payment;
use App\Models\OrdersRequest;
use App\Models\SourcingCountry;
use App\Models\DestinationCountry;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ComptableController extends Controller
{
    public function dashboard()
    {
        $totalQuotedRequests = OrdersRequest::where('statusRequest', 'quoted')->count();

        $totalOrdersPaid = OrdersRequest::whereHas('payments', function ($q) {
            $q->where('status', 'approved');
        })->count();

        $totalOrdersAwaitingPayment = $totalQuotedRequests - $totalOrdersPaid;

        $totalPaymentsPending = Payment::where('status', 'pending')->count();

        $totalOrdersAwaitingShipping = OrdersRequest::whereHas('payments', function ($q) {
            $q->where('status', 'approved');
        })->whereDoesntHave('importedproducts', function ($q) {
            $q->whereIn('statusProduct', ['shipped', 'in transit', 'preparing', 'delivered']);
        })->count();

        $totalOrdersDelivered = OrdersRequest::whereHas('importedproducts', function ($q) {
            $q->where('statusProduct', 'delivered');
        })->count();

        $totalAmountPaid = Payment::where('status', 'approved')->sum('amount');

        return view('auth.comptable.dashboard', compact(
            'totalQuotedRequests',
            'totalOrdersAwaitingPayment',
            'totalPaymentsPending',
            'totalOrdersPaid',
            'totalOrdersAwaitingShipping',
            'totalOrdersDelivered',
            'totalAmountPaid'
        ));
    }

    public function payments()
    {
        $sellers = User::where('role', '3')->get();
        return view('auth.comptable.payment', compact('sellers'));
    }

    public function paymentsData(Request $request)
    {
        try {
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

            $totalRecords = $query->count();

            $data = $query->skip($request->input('start', 0))
                        ->take($request->input('length', 10))
                        ->get()
                        ->map(function ($payment) {
                            $screenshotUrl = asset('storage/' . $payment->screenshot);
                            $actionURL1 = '-';
                            $actionURL2 = '-';

                            if ($payment->status === 'pending') {
                                $actionURL1 = url('/comptable/payments/approve/' . $payment->payment_id);
                                $actionURL2 = url('/comptable/payments/disapprove/' . $payment->payment_id);
                            } elseif ($payment->status === 'approved') {
                                $actionURL2 = url('/comptable/payments/disapprove/' . $payment->payment_id);
                            } elseif ($payment->status === 'disapproved') {
                                $actionURL1 = url('/comptable/payments/approve/' . $payment->payment_id);
                            }

                            return [
                                'payment_id'     => $payment->payment_id,
                                'created_at'     => $payment->payment_created_at,
                                'request_no'     => $payment->ordersrequests->requestNO,
                                'seller_id'      => $payment->seller_id,
                                'seller_name'    => $payment->seller_name,
                                'amount'         => $payment->amount,
                                'payment_option' => $payment->paymentMethod,
                                'screenshot'     => '<a class="badge btn bg-gradient-dark" href="' . $screenshotUrl . '" target="_blank">View Document</a>',
                                'status'         => $payment->status,
                                'approve'        => $actionURL1,
                                'disapprove'     => $actionURL2,
                            ];
                        })->toArray();

            return response()->json([
                'draw'            => intval($request->input('draw')),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data'            => $data,
            ]);

        } catch (\Exception $e) {
            Log::error('ComptableController@paymentsData: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the data.'], 500);
        }
    }

    public function approvePayment($paymentID)
    {
        $payment = Payment::findOrFail($paymentID);
        $payment->status = 'approved';
        $payment->save();

        $this->sendPaymentNotification($paymentID, 1);

        return redirect()->back()->with('success', 'Payment approved successfully.');
    }

    public function disapprovePayment($paymentID)
    {
        $payment = Payment::findOrFail($paymentID);
        $payment->status = 'disapproved';
        $payment->save();

        $this->sendPaymentNotification($paymentID, 0);

        return redirect()->back()->with('success', 'Payment disapproved successfully.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.comptable.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:30',
        ]);

        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();

        return redirect()->back()->with('success', __('pages.profile_updated'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => __('pages.wrong_current_password')]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', __('pages.password_updated'));
    }

    private function sendPaymentNotification($paymentId, $isApproved)
    {
        $payment   = Payment::find($paymentId);
        $requestID = $payment->requestID;
        $sellerID  = $payment->ordersrequests->sellerID;
        $agentID   = $payment->ordersrequests->agentID;
        $seller    = User::find($sellerID);
        $agent     = User::find($agentID);

        $params = ['payment_id' => $paymentId];

        if ($seller) {
            $key  = $isApproved ? 'payment_approved_seller' : 'payment_rejected_seller';
            $link = route('seller.followUpProductRequest', ['id' => $requestID]);
            NotificationService::notify($seller, $requestID, $key, $params, $link);
        }

        if ($agent) {
            $key  = $isApproved ? 'payment_approved_agent' : 'payment_rejected_agent';
            $link = route('agent.followUpProductRequest', ['id' => $requestID]);
            NotificationService::notify($agent, $requestID, $key, $params, $link, ['db', 'mail', 'sms']);
        }
    }
}
