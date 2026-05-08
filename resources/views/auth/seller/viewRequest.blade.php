<!-- resources/views/seller/orders/index.blade.php -->

@extends('auth.theme.dashboard')

@section('content')
@php
    $product = $orderRequest->importedproducts->first();
@endphp
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ __('pages.quotation') }} #{{ $orderRequest->requestNO }}</h4>
                        <p class="mb-0 text-sm">{{ $orderRequest->created_at->isoFormat('LLL') }}</p>
                    </div>
                    <div>
                        @if($orderRequest->statusRequest === 'quoted')
                            <span class="badge badge-sm bg-gradient-info">Quoted</span>
                        @else
                            <span class="badge badge-sm bg-gradient-secondary">{{ $orderRequest->statusRequest }}</span>
                        @endif
                        @if($payment)
                            @if($payment->status === 'approved')
                                <span class="badge badge-sm bg-gradient-success">Approved</span>
                            @elseif($payment->status === 'disapproved')
                                <span class="badge badge-sm bg-gradient-danger">Disapproved</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge badge-sm bg-gradient-warning">Pending</span>
                            @else
                                <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($payment->status) }}</span>
                            @endif
                        @endif
                        @php
                            $statusClasses = [
                                'not yet' => 'bg-gradient-warning',
                                'delivered' => 'bg-gradient-delivered',
                                'preparing' => 'bg-gradient-info',
                                'in transit' => 'bg-gradient-info',
                                'shipped' => 'bg-gradient-info',
                            ];

                            $statusLabels = [
                                'not yet' => 'Not Yet Shipped',
                                'delivered' => 'Delivered',
                                'preparing' => 'Preparing',
                                'in transit' => 'In Transit',
                                'shipped' => 'Shipped',
                            ];
                        @endphp

                        @if(isset($statusClasses[$product->statusProduct]))
                            <span class="badge badge-sm {{ $statusClasses[$product->statusProduct] }}">
                                {{ $statusLabels[$product->statusProduct] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
        </div>
    </div>

    <!-- Request Details -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 font-14">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.request_details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('pages.requested_at_label') }}</strong>
                            <p class="mb-0">{{ $orderRequest->created_at->isoFormat('LLL') }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('pages.paid_at') }}</strong>
                            <p class="mb-0">
                                @if($isPaid)
                                    {{ $payment->created_at->isoFormat('LLL') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('pages.shipping_status') }}</strong>
                            <p class="mb-0">
                                @if($product->statusProduct)
                                    {{ $product->statusProduct }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('pages.carrier_name') }}</strong>
                            <p class="mb-0">
                                @if($product->carrier)
                                    {{ $product->carrier }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('pages.tracking_number') }}</strong>
                            <p class="mb-0">
                                @if($product->trackingNumber)
                                    {{ $product->trackingNumber }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <strong>{{ __('pages.agent_note') }}</strong>
                            <p class="mb-0">
                                @if($product->agentNote)
                                    {{ $product->agentNote }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                    <strong>{{ __('pages.payment_proof') }}</strong>
                    <div class="row pt-3">
                        <div class="col-12 col-md-6">
                            @if($payment && $payment->screenshot)
                                <a href="{{ asset('storage/' . $payment->screenshot) }}">
                                    <img class="img-thumbnail" src="{{ asset('storage/' . $payment->screenshot) }}">
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Details -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body p-3 pb-0">
                    <ul class="list-group">
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ __('pages.product_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $product->productName }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ __('pages.category_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                            {{ $product->productCategory }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ __('pages.link_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                @if($product->productURL)
                                    <a href="{{ $product->productURL }}" target="_blank" class="badge btn bg-gradient-dark">{{ __('pages.view_product') }}</a>
                                @elseif($product->productImage)
                                    <a href="{{ asset('storage/' . $product->productImage) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $product->productImage) }}" class="img-thumbnail" style="max-height:80px;">
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ __('pages.sourcing_country_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $orderRequest->countryFrom }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.destination_country_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $orderRequest->countryTo }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.shipping_method_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $orderRequest->ShippingMethod }}
                            </div>
                        </li>
                        @if($product->measurement_type === 'cbm' && $product->cbm)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.cbm_value') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">{{ $product->cbm }} m³</div>
                        </li>
                        @elseif($product->weight)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.weight_value') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">{{ $product->weight }} kg</div>
                        </li>
                        @endif
                        @if($product->productSpecification)
                        <li class="list-group-item border-0 ps-0 mb-2 border-radius-lg">
                            <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.request_note') }}</h6>
                            <p class="mb-0 text-sm">{{ $product->productSpecification }}</p>
                        </li>
                        @endif
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.quantity_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $product->qte }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.unit_price_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $product->client_unit_price ? format_currency($product->client_unit_price) : ($product->unitPrice != 0 ? format_currency($product->unitPrice) : '-') }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.total_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $product->client_total_price ? format_currency($product->client_total_price) : ($product->totalPrice != 0 ? format_currency($product->totalPrice) : '-') }}
                            </div>
                        </li>
                        @if($orderRequest->commission_amount)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.service_fees_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ format_currency($orderRequest->commission_amount) }}
                            </div>
                        </li>
                        @endif
                        @if($orderRequest->transit_client_amount)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.transit_fees_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ format_currency($orderRequest->transit_client_amount) }}
                            </div>
                        </li>
                        @if($orderRequest->transit_payment_mode)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.transit_payment_mode_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm text-end" style="max-width:55%">
                                {{ __('pages.transit_' . $orderRequest->transit_payment_mode) }}
                            </div>
                        </li>
                        @endif
                        @endif
                        @php
                            $clientTotal  = $product->client_total_price ?? $product->totalPrice ?? 0;
                            $serviceFees  = $orderRequest->commission_amount ?? 0;
                            $grandTotal   = $clientTotal + $serviceFees;
                        @endphp
                        @if($grandTotal > 0)
                        <li class="list-group-item border-0 ps-0 border-radius-lg">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="text-dark mb-0 font-weight-bold text-sm">{{ __('pages.grand_total_label') }}</h6>
                                <strong class="text-sm text-primary">{{ format_currency($grandTotal) }}
                                    @if($orderRequest->transit_client_amount)
                                        + {{ format_currency($orderRequest->transit_client_amount) }}
                                    @endif
                                </strong>
                            </div>
                        </li>
                        @endif
                    </ul>

                    @if(($clientTotal != 0) && (!$payment || $payment->status === 'disapproved'))
                    <div class="d-flex justify-content-center my-4">
                            <a class="btn bg-gradient-dark mb-0" href="{{ route('seller.pay-option', ['orderRequestId' => $orderRequest->id]) }}">
                                <i class="fas fa-plus me-2" aria-hidden="true"></i>{{ __('pages.make_payment') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Section -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-4">{{ __('pages.chat') }}</h5>
                    
                    <!-- Chat Messages -->
                    <div id="chat-box" class="p-3" style="max-height: 400px; overflow-y: auto;">
                        @forelse($chatMessages as $message)
                            <div class="mb-3 {{ $message->sender_id == auth()->id() ? 'text-end' : '' }}">
                                <div class="p-3 d-inline-block rounded {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                    @if($message->file_path)
                                        @php
                                            $fileExtension = pathinfo($message->file_path, PATHINFO_EXTENSION);
                                        @endphp
                                        <!-- If the file is an image -->
                                        @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <img src="{{ asset('storage/' . $message->file_path) }}" alt="Image" class="img-fluid" style="max-width: 200px; border-radius: 8px;">
                                        @elseif(in_array($fileExtension, ['mp4', 'avi', 'mkv']))
                                            <!-- If the file is a video -->
                                            <video controls class="img-fluid" style="max-width: 200px; border-radius: 8px;">
                                                <source src="{{ asset('storage/' . $message->file_path) }}" type="video/{{ $fileExtension }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <!-- If the file is neither image nor video -->
                                            <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="text-muted">
                                                {{ pathinfo($message->file_path, PATHINFO_BASENAME) }}
                                            </a>
                                        @endif
                                    @endif
                                    <p class="mb-0">{{ $message->message_content }}</p>
                                </div>
                                <small class="d-block text-muted">{{ $message->created_at->isoFormat('LLL') }}</small>
                            </div>
                        @empty
                            <p class="text-center text-muted">{{ __('pages.no_messages') }}</p>
                        @endforelse
                    </div>

                    <!-- Send Message Form -->
                    <form id="chat-form" enctype="multipart/form-data" method="POST" class="mt-3">
                        @csrf
                        <div class="form-group">
                            <textarea name="message_content" id="message_content" placeholder="{{ __('pages.type_message') }}" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="d-flex align-items-center mt-2">
                            <input type="file" name="file" id="file" class="form-control me-2" accept="image/*,video/*">
                            <button type="submit" class="btn btn-primary px-4 mt-2 mt-sm-0" id="send-message-btn">
                                <i class="fas fa-paper-plane"></i> {{ __('pages.send') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<!-- Include the necessary JS scripts -->
<script>
$(document).ready(function () {
    // Hardcoded sender and recipient IDs based on Blade variables
    var senderID = "{{ $orderRequest->sellerID }}";  // Seller ID is fixed
    var recipientID = "{{ $orderRequest->agentID }}";  // Agent ID is fixed

    // Send message via AJAX
    $('#chat-form').submit(function (event) {
        event.preventDefault(); // Prevent page reload

        // Prepare the form data
        var formData = new FormData(this);

        // Append sender and recipient IDs to the form data
        formData.append('sender_id', senderID); // Seller is always the sender
        formData.append('recipient_id', recipientID); // Agent is always the recipient

        // AJAX request
        $.ajax({
            url: '{{ route('chat.send', ['orderRequestId' => $orderRequest->id]) }}', // Dynamic route
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    // Append the new message to the chat box
                    var newMessage = `
                        <div class="mb-3 text-end">
                            <div class="p-2 d-inline-block rounded bg-primary text-white">
                                ${response.message.file_path ? getFileHtml(response.message.file_path) : ''}
                                <p class="mb-0">${response.message.message}</p>
                            </div>
                            <small class="d-block text-muted">${response.message.created_at}</small>
                        </div>
                    `;
                    $('#chat-box').append(newMessage);
                    $('#message_content').val(''); // Clear message input
                    $('#file').val(''); // Clear file input
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // Scroll to bottom of chat box
                } else {
                    alert('Error sending message. Please try again.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Message sending failed. Please check your input or try again later.');
            },
        });
    });

    // Function to determine the file type and generate HTML accordingly
    function getFileHtml(filePath) {
        const fileExtension = filePath.split('.').pop().toLowerCase();

        // Image file types
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
            return `<img src="${filePath}" class="img-fluid" style="max-width: 300px;">`;
        }

        // Video file types
        if (['mp4', 'avi', 'mkv'].includes(fileExtension)) {
            return `
                <video controls class="img-fluid" style="max-width: 300px;">
                    <source src="${filePath}" type="video/${fileExtension}">
                    Your browser does not support the video tag.
                </video>
            `;
        }

        // Default file link (for unsupported files)
        return ``;
    }
});
</script>
@endpush
@endsection