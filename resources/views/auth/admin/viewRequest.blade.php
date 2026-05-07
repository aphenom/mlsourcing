<!-- resources/views/seller/orders/index.blade.php -->

@extends('auth.theme.dashboard')

@section('content')
@php
    $product = $orderRequest->importedproducts->first();
@endphp
<div class="container-fluid py-4">
    <!-- Display Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Display Errors -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Quotation #{{ $orderRequest->requestNO }}</h4>
                        <p class="mb-0 text-sm">{{ $orderRequest->created_at->isoFormat('LLL') }}</p>
                    </div>
                    <div>
                        @if(strtolower($orderRequest->statusRequest)==='quoting')
                        <span class="badge badge-sm bg-gradient-secondary">{{ $orderRequest->statusRequest }}</span>
                        @else
                        <span class="badge badge-sm bg-gradient-info">{{ $orderRequest->statusRequest }}</span>
                        @endif

                        @php
                            $statusClasses = [
                                'pending' => 'bg-gradient-warning',
                                'approved' => 'bg-gradient-success',
                                'disapproved' => 'bg-gradient-danger',
                            ];
                        @endphp

                        @if ($payment)
                            @php
                                $status = strtolower($payment->status);
                                $badgeClass = $statusClasses[$status] ?? 'bg-gradient-secondary'; // Default class if status not found
                            @endphp
                        <span class="badge badge-sm {{ $badgeClass }}">{{ $payment->status }}</span>
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
    <!-- Seller Info -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-2 px-3 d-flex align-items-center gap-4 flex-wrap">
                    <span class="text-xs text-uppercase font-weight-bold text-muted">{{ __('pages.seller_info') }}</span>
                    <span class="text-sm"><i class="fa fa-user me-1"></i>{{ $orderRequest->seller->name ?? '-' }}</span>
                    <span class="text-sm"><i class="fa fa-envelope me-1"></i>{{ $orderRequest->seller->email ?? '-' }}</span>
                    @if($orderRequest->seller?->phone)
                    <span class="text-sm"><i class="fa fa-phone me-1"></i>{{ $orderRequest->seller->phone }}</span>
                    @endif
                </div>
            </div>
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
                                @if ($payment && ($payment->status === 'approved'))
                                )
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
                        <li class="list-group-item border-0 ps-0 mb-2 border-radius-lg">
                            <h6 class="text-dark mb-2 font-weight-bold text-sm">{{ __('pages.quantity_label') }}</h6>
                            <form method="POST" action="{{ route('admin.updateQuantity', $orderRequest->id) }}" class="d-flex align-items-center gap-2">
                                @csrf
                                <input type="number" name="qte" value="{{ $product->qte }}" min="1"
                                       class="form-control form-control-sm" style="width:90px;">
                                <button type="submit" class="btn btn-sm bg-gradient-success text-white mb-0">
                                    {{ __('pages.save') }}
                                </button>
                            </form>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.unit_price_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                @if($product->unitPrice != 0)
                                ${{ $product->unitPrice }}
                                @else
                                    -
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.total_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                <strong>
                                    @if($product->totalPrice != 0)
                                       ${{$product->totalPrice}}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </div>
                        </li>
                    </ul>

                    @if($payment && ($payment->status != 'approved'))
                        <div class="d-flex justify-content-center my-4">
                            <a class="btn bg-gradient-success mb-0" href="{{ route('admin.approvePayment',$payment->id) }}">
                                <i class="fas fa-plus me-2" aria-hidden="true"></i> {{ __('pages.approve_payment') }}
                            </a>
                        </div>

                        
                    @elseif($payment && ($payment->status != 'disapproved'))
                    <div class="d-flex justify-content-center my-4">
                            <a class="btn bg-gradient-danger mb-0" href="{{ route('admin.disapprovePayment',$payment->id) }}">
                                <i class="fas fa-plus me-2" aria-hidden="true"></i> {{ __('pages.disapprove_payment') }}
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
                            <div class="mb-3 {{ $message->sender_id == $orderRequest->sellerID ? 'text-end' : '' }}">
                                <div class="p-2 d-inline-block rounded {{ $message->sender_id == $orderRequest->sellerID ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                    @if($message->sender_id == $orderRequest->sellerID)
                                    <p><strong style="font-size: 10px;color: #4b4b4b;">{{ __('pages.seller_label') }}</strong></p>
                                    @else
                                    <p><strong style="font-size: 10px;color: #4b4b4b;">{{ __('pages.agent_label') }}</strong></p>
                                    @endif
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

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection