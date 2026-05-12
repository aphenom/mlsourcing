<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\ComptableController;


Route::get('/symlink', function(){
    $targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/public/storage';
    symlink($targetFolder,$linkFolder);
});
Route::get('/', function () {
    $user = Auth::user();

    if (!$user) {
        // If user is not authenticated, redirect to login
        return redirect()->route('login');
    }

    // Redirect based on user role
    switch ($user->role) {
        case 1: // Admin
            return redirect()->route('admin.dashboard');
        case 2: // Agent
            return redirect()->route('agent.dashboard');
        case 3: // Seller
            if ($user->status === 'pending') return redirect()->route('seller.pending');
            if ($user->status === 'blocked') {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => __('pages.account_blocked')]);
            }
            return redirect()->route('seller.dashboard');
        case 4: // Comptable
            return redirect()->route('comptable.dashboard');
        default:
            // Default action if role is undefined or invalid
            abort(403, 'Unauthorized action.');
    }
    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function() {
    Route::get('switch_language', [FilterController::class, 'switch_language'])->name('switch_language');
    Route::get('switch_currency', [FilterController::class, 'switch_currency'])->name('switch_currency');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Send a message
    Route::post('/chat/send/{orderRequestId}', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [UserNotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [UserNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/delete/{notificationId}', [UserNotificationController::class, 'delete'])->name('notifications.delete');


    // Seller pending page (accessible to all authenticated sellers regardless of status)
    Route::get('/seller/pending', [SellerController::class, 'pending'])->name('seller.pending');

    // Seller Routing
    Route::middleware(CheckRole::class . ':3')->group(function () {
        
        // Seller Dashboard
        Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
        
        // Seller Requests
        Route::get('seller/requests', [SellerController::class, 'productRequests'])->name('seller.productRequests');
        Route::get('seller/requests-data', [SellerController::class, 'filteredProductRequests'])->name('seller.requestsData');
        Route::get('/seller/requests/add', [SellerController::class, 'addProductRequests'])->name('seller.addProductRequests');
        Route::post('/seller/requests/save', [SellerController::class, 'storeProductRequests'])->name('seller.storeProductRequests');
        Route::get('/seller/requests/{id}', [SellerController::class, 'followUpProductRequest'])->name('seller.followUpProductRequest');
        Route::get('/seller/requests/payment-option/{orderRequestId}', [SellerController::class, 'showPaymentMethods'])->name('seller.pay-option');
        Route::post('/seller/requests/pay/{orderRequestId}', [SellerController::class, 'payOrder'])->name('seller.pay');


        // Seller Payments
        Route::get('/seller/payments', [SellerController::class, 'paymentHistory'])->name('seller.paymentHistory');
        Route::get('/seller/payment-data', [SellerController::class, 'filteredPaymentHistory'])->name('seller.filteredPayments');

        // Seller Orders
        Route::get('seller/orders-data', [SellerController::class, 'filteredOrders'])->name('seller.ordersData');
        Route::get('seller/orders', [SellerController::class, 'getOrders'])->name('seller.getOrders');

        // Seller Reclamation
        Route::get('seller/reclamation', [SellerController::class, 'reclamation'])->name('seller.reclamation');
        Route::post('seller/reclamation/send', [SellerController::class, 'reclamationSend'])->name('reclamations.store');

        // FAQs
        Route::get('/faq', [FaqController::class, 'index'])->name('faqs');

        // Seller Profile
        Route::get('/seller/profile', [SellerController::class, 'profile'])->name('seller.profile');
        Route::post('/seller/profile', [SellerController::class, 'updateProfile'])->name('seller.updateProfile');
        Route::post('/seller/profile/password', [SellerController::class, 'updatePassword'])->name('seller.updatePassword');

    });

    // Agent Routing
    Route::middleware(CheckRole::class . ':2')->group(function () {
        
        // Agent Dashboard
        Route::get('/agent/dashboard', [AgentController::class, 'dashboard'])->name('agent.dashboard');

        // Agent Requests
        Route::get('agent/requests', [AgentController::class, 'productRequests'])->name('agent.productRequests');
        Route::get('agent/requests-data', [AgentController::class, 'filteredProductRequests'])->name('agent.requestsData');
        Route::get('agent/requests/add-for-seller', [AgentController::class, 'addRequestForSeller'])->name('agent.addRequestForSeller');
        Route::post('agent/requests/add-for-seller', [AgentController::class, 'storeRequestForSeller'])->name('agent.storeRequestForSeller');
        Route::get('/agent/requests/{id}', [AgentController::class, 'followUpProductRequest'])->name('agent.followUpProductRequest');
        Route::post('/agent/requests/{id}/update-quantity', [AgentController::class, 'updateQuantity'])->name('agent.updateQuantity');

        Route::post('/agent/requests/quote/{id}', [AgentController::class, 'quote'])->name('agent.quote');
        
        // Agent Orders
        Route::get('agent/orders', [AgentController::class, 'orders'])->name('agent.orders');
        Route::get('agent/orders-data', [AgentController::class, 'filteredOrders'])->name('agent.ordersData');
        Route::get('agent/orders/dispatching/{id}', [AgentController::class, 'dispatching'])->name('agent.dispatching');
        Route::post('agent/orders/dispatch/{id}', [AgentController::class, 'dispatch'])->name('agent.dispatch');

        // Agent Seller Management
        Route::get('agent/sellers', [AgentController::class, 'sellers'])->name('agent.sellers');
        Route::post('agent/sellers/create', [AgentController::class, 'storeSeller'])->name('agent.storeSeller');
        Route::post('agent/sellers/{id}/activate', [AgentController::class, 'activateSeller'])->name('agent.activateSeller');
        Route::post('agent/sellers/{id}/update', [AgentController::class, 'updateSeller'])->name('agent.updateSeller');
    });

    // Admin
    Route::middleware(CheckRole::class . ':1')->group(function () {
        
        // Admin Dashboard
        Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Admin Configurator
        Route::get('admin/configuration', [AdminController::class, 'configuration'])->name('admin.configuration');

        // Currency management
        Route::get('admin/currencies', [AdminController::class, 'currencies'])->name('admin.currencies');
        Route::post('admin/currencies/{code}/update', [AdminController::class, 'updateCurrencyRate'])->name('admin.currencies.update');
        Route::post('admin/currencies/sync', [AdminController::class, 'syncCurrencyRates'])->name('admin.currencies.sync');

        // Sourcing and Destination Countries - Controller:
        // Delete
        Route::delete('/admin/sourcing-countries/{sourcingCountryId}', [AdminController::class, 'deleteSourcingCountry'])->name('admin.deleteSourcingCountry');
        Route::delete('/admin/destination-countries/{destinationCountryId}', [AdminController::class, 'deleteDestinationCountry'])->name('admin.deleteDestinationCountry');
        // Add
        Route::post('/admin/destination-countries/add', [AdminController::class, 'addDestinationCountry'])->name('admin.addDestinationCountry');
        Route::post('/admin/sourcing-countries/add', [AdminController::class, 'addSourcingCountry'])->name('admin.addSourcingCountry');

        // Agent - Controller
        // Add
        Route::post('/admin/add-agent', [AdminController::class, 'storeAgent'])->name('admin.storeAgent');
        // Delete
        Route::delete('/admin/agents/delete/{id}', [AdminController::class, 'deleteAgent'])->name('admin.deleteAgent');

        // Comptable - Controller
        Route::post('/admin/add-comptable', [AdminController::class, 'storeComptable'])->name('admin.storeComptable');
        Route::delete('/admin/comptables/delete/{id}', [AdminController::class, 'deleteComptable'])->name('admin.deleteComptable');
        // Unlink a destination country
        Route::delete('/admin/agents/{agentId}/unlink-destination-countries/{destinationCountryId}', [AdminController::class, 'unlinkDestinationCountry'])->name('admin.unlinkDestinationCountry');
        // Link new destination countries
        Route::post('/admin/agents/link-destination-countries', [AdminController::class, 'linkDestinationCountries'])->name('admin.linkDestinationCountries');
        // Link Sourcing Country
        Route::post('/admin/agents/link-sourcing-countries/{agentId}', [AdminController::class, 'linkSourcingCountry'])->name('admin.linkSourcingCountry');
        
        // Payment
        // Delete Payment Option
        Route::delete('/admin/payment-option/delete/{paymentOptionId}', [AdminController::class, 'deletePaymentOption'])->name('admin.deletePaymentOption');
        // Add Payment Option
        Route::post('admin/payment-option/add',[AdminController::class, 'addPaymentOption'])->name('admin.addPaymentOption');
        // Approve Payment
        Route::get('admin/requests/approve-payment/{paymentID}', [AdminController::class, 'approvePayment'])->name('admin.approvePayment');
        // Disapprove Payment
        Route::get('admin/requests/disapprove-payment/{paymentID}', [AdminController::class, 'disapprovePayment'])->name('admin.disapprovePayment');
        // Payment view
        Route::get('admin/payments', [AdminController::class, 'payments'])->name('admin.payments');
        // Filter Payments Data
        Route::get('admin/payments-data', [AdminController::class, 'paymentsData'])->name('admin.paymentsData');
        
        // Requests
        // View of Blade Requests
        Route::get('admin/requests', [AdminController::class, 'productRequests'])->name('admin.productRequests');
        // See Filtered Requests
        Route::get('admin/requests-data', [AdminController::class, 'filteredProductRequests'])->name('admin.requestsData');
        // Add request on behalf of a seller
        Route::get('admin/requests/add-for-seller', [AdminController::class, 'addRequestForSeller'])->name('admin.addRequestForSeller');
        Route::post('admin/requests/add-for-seller', [AdminController::class, 'storeRequestForSeller'])->name('admin.storeRequestForSeller');
        // See Details of specific request
        Route::get('admin/requests/{id}', [AdminController::class, 'followUpProductRequest'])->name('admin.followUpProductRequest');
        Route::post('admin/requests/{id}/update-quantity', [AdminController::class, 'updateQuantity'])->name('admin.updateQuantity');
        
        // Delete actions (admin only)
        Route::delete('admin/requests/{id}', [AdminController::class, 'deleteRequest'])->name('admin.deleteRequest');
        Route::delete('admin/orders/{id}', [AdminController::class, 'deleteOrder'])->name('admin.deleteOrder');
        Route::delete('admin/payments/{id}', [AdminController::class, 'deletePayment'])->name('admin.deletePayment');

        // See and filter orders
        Route::get('admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('admin/orders-data', [AdminController::class, 'filteredAdminOrders'])->name('admin.ordersData');

        // Admin Seller Management
        Route::get('admin/sellers', [AdminController::class, 'sellers'])->name('admin.sellers');
        Route::post('admin/sellers/create', [AdminController::class, 'storeSeller'])->name('admin.storeSeller');
        Route::post('admin/sellers/{id}/activate', [AdminController::class, 'activateSeller'])->name('admin.activateSeller');
        Route::post('admin/sellers/{id}/block', [AdminController::class, 'blockSeller'])->name('admin.blockSeller');
        Route::post('admin/sellers/{id}/unblock', [AdminController::class, 'unblockSeller'])->name('admin.unblockSeller');
        Route::post('admin/sellers/{id}/update', [AdminController::class, 'updateSeller'])->name('admin.updateSeller');
    });
    // Comptable
    Route::middleware(CheckRole::class . ':4')->group(function () {
        Route::get('comptable/dashboard', [ComptableController::class, 'dashboard'])->name('comptable.dashboard');
        Route::get('comptable/payments', [ComptableController::class, 'payments'])->name('comptable.payments');
        Route::get('comptable/payments-data', [ComptableController::class, 'paymentsData'])->name('comptable.paymentsData');
        Route::get('comptable/payments/approve/{paymentID}', [ComptableController::class, 'approvePayment'])->name('comptable.approvePayment');
        Route::get('comptable/payments/disapprove/{paymentID}', [ComptableController::class, 'disapprovePayment'])->name('comptable.disapprovePayment');
        Route::get('comptable/profile', [ComptableController::class, 'profile'])->name('comptable.profile');
        Route::post('comptable/profile', [ComptableController::class, 'updateProfile'])->name('comptable.updateProfile');
        Route::post('comptable/profile/password', [ComptableController::class, 'updatePassword'])->name('comptable.updatePassword');
        Route::get('comptable/currencies', [ComptableController::class, 'currencies'])->name('comptable.currencies');
        Route::post('comptable/currencies/{code}/update', [ComptableController::class, 'updateCurrencyRate'])->name('comptable.currencies.update');
        Route::post('comptable/currencies/sync', [ComptableController::class, 'syncCurrencyRates'])->name('comptable.currencies.sync');
    });
});




require __DIR__.'/auth.php';
