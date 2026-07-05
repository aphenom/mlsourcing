<!-- resources/views/seller/orders/index.blade.php -->

@extends('auth.theme.dashboard')

@section('content')
@php
    $product = $orderRequest->importedproducts->first();
@endphp
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
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
                            <div class="d-flex align-items-center gap-2 flex-wrap text-sm">
                                @if($product->productURL)
                                    <a href="{{ $product->productURL }}" target="_blank" class="badge btn bg-gradient-dark">{{ __('pages.view_product') }}</a>
                                @endif
                                @if($product->productImage)
                                    <a href="{{ asset('storage/' . $product->productImage) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $product->productImage) }}" class="img-thumbnail" style="max-height:80px;">
                                    </a>
                                @endif
                                @if(!$product->productURL && !$product->productImage)
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
                            <div class="d-flex align-items-center text-sm">{{ $product->cbm + 0 }} m³</div>
                        </li>
                        @elseif($product->weight)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.weight_value') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">{{ $product->weight + 0 }} kg</div>
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
                            @if ($orderRequest->statusRequest === 'quoting')
                                <form method="POST" action="{{ route('agent.updateQuantity', $orderRequest->id) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <input type="number" name="qte" value="{{ $product->qte }}" min="1"
                                           class="form-control form-control-sm" style="width:90px;">
                                    <button type="submit" class="btn btn-sm bg-gradient-success text-white mb-0">
                                        {{ __('pages.save') }}
                                    </button>
                                </form>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-sm font-weight-bold">{{ $product->qte }}</span>
                                    <span class="badge bg-gradient-secondary text-xs">
                                        <i class="fa fa-lock me-1"></i>{{ __('pages.quantity_locked') }}
                                    </span>
                                </div>
                            @endif
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.unit_price_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                {{ $product->unitPrice != 0 ? format_currency($product->client_unit_price ?? $product->unitPrice) : '-' }}
                            </div>
                        </li>
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                            <div class="d-flex flex-column">
                                <h6 class="text-dark mb-1 font-weight-bold text-sm">{{ __('pages.total_label') }}</h6>
                            </div>
                            <div class="d-flex align-items-center text-sm">
                                <strong>
                                    {{ $product->totalPrice != 0 ? format_currency($product->client_total_price ?? $product->totalPrice) : '-' }}
                                </strong>
                            </div>
                        </li>
                    </ul>

                    @if(!$Quoted)
                    <div class="d-flex justify-content-center my-4">
                        <a class="btn bg-gradient-dark mb-0" href="#" data-bs-toggle="modal" data-bs-target="#quoteModal">
                            <i class="fas fa-plus me-2" aria-hidden="true"></i> {{ __('pages.quote_it') }}
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
                                <div class="p-2 d-inline-block rounded {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark' }}">
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

<!-- Quote Modal -->
<div class="modal fade" id="quoteModal" tabindex="-1" role="dialog" aria-labelledby="quoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quoteModalLabel">{{ __('pages.enter_quote_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('agent.quote', ['id' => $orderRequest->id]) }}" method="POST"
                  style="display:flex;flex-direction:column;flex:1 1 auto;overflow:hidden;min-height:0;">
                @csrf
                <div class="modal-body" style="overflow-y:auto;flex:1 1 auto;">
                    <div class="row g-4">
                        {{-- LEFT COLUMN: Inputs --}}
                        <div class="col-lg-7">

                            {{-- Section 1: Pricing --}}
                            <div class="card border mb-3">
                                <div class="card-header py-2 bg-light">
                                    <h6 class="mb-0 text-sm font-weight-bold">{{ __('pages.internal_pricing_label') }}</h6>
                                </div>
                                <div class="card-body py-3">
                                    <div class="mb-3">
                                        <label class="form-label text-xs">{{ __('pages.purchase_price_label') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ currency_symbol() }}</span>
                                            <input type="text" inputmode="decimal" class="form-control" id="q_purchase_price" name="purchase_price"
                                                   placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-xs">{{ __('pages.sale_price_label') }}* <span class="badge bg-gradient-info text-xs">{{ active_currency() }}</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ currency_symbol() }}</span>
                                            <input type="text" inputmode="decimal" class="form-control" id="q_unit_price" name="unit_price"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-xs">{{ __('pages.margin_percent_label') }}</label>
                                        <div class="row g-2">
                                            <div class="col-8">
                                                <select class="form-control form-control-sm" id="q_margin_preset">
                                                    <option value="0">0%</option>
                                                    <option value="5">5%</option>
                                                    <option value="10">10%</option>
                                                    <option value="15">15%</option>
                                                    <option value="20">20%</option>
                                                    <option value="custom">{{ __('pages.margin_custom') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" class="form-control form-control-sm d-none" id="q_margin_custom"
                                                       step="0.01" min="0" placeholder="%">
                                            </div>
                                        </div>
                                        <input type="hidden" id="q_margin_percent" name="margin_percent" value="0">
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: Commission --}}
                            <div class="card border mb-3">
                                <div class="card-header py-2 bg-light">
                                    <h6 class="mb-0 text-sm font-weight-bold">{{ __('pages.service_fees_label') }}</h6>
                                </div>
                                <div class="card-body py-3">
                                    {{-- Type toggle --}}
                                    <div class="mb-3">
                                        <label class="form-label text-xs">{{ __('pages.commission_type_label') }}</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="commission_type" id="ct_percent" value="percent" checked>
                                                <label class="form-check-label text-xs" for="ct_percent">{{ __('pages.commission_type_percent') }}</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="commission_type" id="ct_fixed" value="fixed">
                                                <label class="form-check-label text-xs" for="ct_fixed">{{ __('pages.commission_type_fixed') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Percent section --}}
                                    <div id="comm_percent_section">
                                        <label class="form-label text-xs">{{ __('pages.commission_percent_label') }}</label>
                                        <div class="row g-2">
                                            <div class="col-8">
                                                <select class="form-control form-control-sm" id="q_commission_preset">
                                                    <option value="0">0%</option>
                                                    <option value="5">5%</option>
                                                    <option value="10">10%</option>
                                                    <option value="15">15%</option>
                                                    <option value="20">20%</option>
                                                    <option value="custom">{{ __('pages.margin_custom') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" inputmode="decimal" class="form-control form-control-sm d-none" id="q_commission_custom" placeholder="%">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Fixed amount section --}}
                                    <div id="comm_fixed_section" class="d-none">
                                        <label class="form-label text-xs">{{ __('pages.commission_fixed_label') }} ({{ currency_symbol() }})</label>
                                        <input type="text" inputmode="decimal" class="form-control form-control-sm" id="q_commission_fixed" name="commission_fixed" placeholder="0.00" value="0">
                                    </div>

                                    <input type="hidden" id="q_commission_percent" name="commission_percent" value="0">
                                </div>
                            </div>

                            {{-- Section 3: Transit --}}
                            <div class="card border mb-3">
                                <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-sm font-weight-bold">{{ __('pages.transit_mode_label') }}</h6>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="q_transit_enabled" checked>
                                        <label class="form-check-label text-xs" for="q_transit_enabled">{{ __('pages.transit_include_label') }}</label>
                                    </div>
                                </div>
                                <div class="card-body py-3" id="transit_fields_body">
                                    <div class="mb-3">
                                        <label class="form-label text-xs">{{ __('pages.transit_mode_label') }}*</label>
                                        <select class="form-control" id="q_transit_mode" name="transit_mode" required>
                                            <option value="normal">{{ __('pages.transit_normal') }} — 10 500 FCFA/kg</option>
                                            <option value="express">{{ __('pages.transit_express') }} — 15 900 FCFA/kg</option>
                                            <option value="maritime">{{ __('pages.transit_maritime') }} — 280 000 FCFA/m³</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-xs">{{ __('pages.transit_payment_mode_label') }}*</label>
                                        <select class="form-control" id="q_transit_payment_mode" name="transit_payment_mode" required>
                                            <option value="at_delivery">{{ __('pages.transit_at_delivery') }}</option>
                                            <option value="half_half">{{ __('pages.transit_half_half') }}</option>
                                            <option value="at_order">{{ __('pages.transit_at_order') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Section 4: Logistics --}}
                            <div class="card border mb-3">
                                <div class="card-header py-2 bg-light">
                                    <h6 class="mb-0 text-sm font-weight-bold">{{ __('pages.measurement_type') }}</h6>
                                </div>
                                <div class="card-body py-3">
                                    <div class="mb-3">
                                        <label class="form-label text-xs">{{ __('pages.measurement_type') }}*</label>
                                        <select class="form-control" id="measurement_type" name="measurement_type" required>
                                            <option value="weight" {{ old('measurement_type', $product->measurement_type ?? 'weight') === 'weight' ? 'selected' : '' }}>{{ __('pages.weight_kg') }}</option>
                                            <option value="cbm"    {{ old('measurement_type', $product->measurement_type ?? 'weight') === 'cbm'    ? 'selected' : '' }}>{{ __('pages.cbm_label') }}</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" id="field_weight">
                                        <label class="form-label text-xs">{{ __('pages.weight_value') }}*</label>
                                        <div class="input-group">
                                            <input type="text" inputmode="decimal" class="ps-5 form-control" id="q_weight" name="weight"
                                                   value="{{ old('weight', $product->weight) }}">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </div>
                                    <div class="mb-3 d-none" id="field_cbm">
                                        <label class="form-label text-xs">{{ __('pages.cbm_value') }}*</label>
                                        <div class="input-group">
                                            <input type="text" inputmode="decimal" class="ps-5 form-control" id="q_cbm" name="cbm"
                                                   value="{{ old('cbm', $product->cbm) }}">
                                            <span class="input-group-text">m³</span>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label text-xs">{{ __('pages.agent_note_input') }}</label>
                                        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end left col --}}

                        {{-- RIGHT COLUMN: Live preview --}}
                        <div class="col-lg-5">
                            <div class="card border border-primary h-100">
                                <div class="card-header py-2 bg-gradient-primary text-white">
                                    <h6 class="mb-0 text-sm">{{ __('pages.preview_label') }}</h6>
                                </div>
                                <div class="card-body py-3 font-14">
                                    <p class="text-xs text-muted mb-2">{{ __('pages.quantity_label') }}: <strong>{{ $product->qte }}</strong></p>

                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-xs text-muted">{{ __('pages.sale_price_label') }}</span>
                                        <span class="text-xs" id="prev_sale_price">—</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-xs text-muted">{{ __('pages.margin_percent_label') }}</span>
                                        <span class="text-xs" id="prev_margin_pct">0%</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1 fw-semibold">
                                        <span class="text-xs">{{ __('pages.client_unit_price_label') }}</span>
                                        <span class="text-xs" id="prev_client_unit">—</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 fw-semibold">
                                        <span class="text-xs">{{ __('pages.client_total_label') }}</span>
                                        <span class="text-xs" id="prev_client_total">—</span>
                                    </div>

                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-xs text-muted" id="prev_comm_type_label">{{ __('pages.commission_percent_label') }}</span>
                                        <span class="text-xs" id="prev_comm_pct">0%</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-xs">{{ __('pages.service_fees_label') }}</span>
                                        <span class="text-xs text-success fw-semibold" id="prev_service_fees">—</span>
                                    </div>

                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-xs text-muted">{{ __('pages.transit_mode_label') }}</span>
                                        <span class="text-xs" id="prev_transit_mode">—</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-xs">{{ __('pages.transit_fees_label') }}</span>
                                        <span class="text-xs text-info fw-semibold" id="prev_transit_fees">—</span>
                                    </div>

                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-sm fw-bold">{{ __('pages.grand_total_label') }}</span>
                                        <span class="text-sm fw-bold text-primary" id="prev_grand_total">—</span>
                                    </div>

                                    <div class="alert alert-secondary mt-3 py-2 px-3 text-xs mb-0 text-white">
                                        <strong>{{ __('pages.transit_payment_mode_label') }}:</strong>
                                        <span id="prev_transit_payment">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>{{-- end right col --}}

                    </div>{{-- end row --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('pages.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('pages.submit_quote') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var qte       = {{ $product->qte }};
    var fxRate    = {{ fx_rate() }};         // FCFA per 1 active currency unit (e.g. 600 for USD)
    var curCode   = '{{ active_currency() }}';
    var curSymbol = '{{ currency_symbol() }}';

    var TRANSIT_RATES = {
        normal:   { client: 10500,  margin: 1500,  unit: 'kg'  },
        express:  { client: 15900,  margin: 2900,  unit: 'kg'  },
        maritime: { client: 280000, margin: 50000, unit: 'cbm' }
    };

    var TRANSIT_LABELS = {
        normal:      '{{ __('pages.transit_normal') }}',
        express:     '{{ __('pages.transit_express') }}',
        maritime:    '{{ __('pages.transit_maritime') }}'
    };

    var PAYMENT_LABELS = {
        at_delivery: '{{ __('pages.transit_at_delivery') }}',
        half_half:   '{{ __('pages.transit_half_half') }}',
        at_order:    '{{ __('pages.transit_at_order') }}'
    };

    // Format in active display currency (input is in active currency, multiply by fxRate → FCFA → display)
    function fmt(n) {
        if (!n && n !== 0) return '—';
        var decimals = (curCode === 'XOF') ? 0 : 2;
        if (curCode === 'XOF') {
            return parseFloat(n).toLocaleString('fr-FR', {maximumFractionDigits: 0}) + ' FCFA';
        }
        return curSymbol + parseFloat(n).toLocaleString('fr-FR', {minimumFractionDigits: decimals, maximumFractionDigits: decimals});
    }
    // Format FCFA amount for display (transit fees are always stored/entered in FCFA)
    function fmtFcfa(fcfaAmount) {
        if (!fcfaAmount && fcfaAmount !== 0) return '—';
        var display = fcfaAmount / fxRate;
        return fmt(display);
    }

    function normalizeNum(val) { return (val || '').toString().replace(',', '.'); }
    function getVal(id) { return parseFloat(normalizeNum(document.getElementById(id)?.value)) || 0; }

    var COMM_LABELS = {
        percent: '{{ __('pages.commission_percent_label') }}',
        fixed:   '{{ __('pages.commission_type_fixed') }}'
    };

    function getCommType() {
        var checked = document.querySelector('input[name="commission_type"]:checked');
        return checked ? checked.value : 'percent';
    }

    function isTransitEnabled() {
        var cb = document.getElementById('q_transit_enabled');
        return cb ? cb.checked : true;
    }

    function updatePreview() {
        var salePrice    = getVal('q_unit_price');
        var marginPct    = getVal('q_margin_percent');
        var commType     = getCommType();
        var commPct      = commType === 'percent' ? getVal('q_commission_percent') : 0;
        var commFixed    = commType === 'fixed'   ? getVal('q_commission_fixed')   : 0;
        var transitOn    = isTransitEnabled();
        var transitMode  = document.getElementById('q_transit_mode')?.value || 'normal';
        var payMode      = document.getElementById('q_transit_payment_mode')?.value || 'at_delivery';
        var weight       = getVal('q_weight');
        var cbm          = getVal('q_cbm');

        var clientUnit   = salePrice > 0 ? salePrice * (1 + marginPct / 100) : 0;
        var clientTotal  = clientUnit * qte;
        var serviceFees  = commType === 'fixed' ? commFixed : clientTotal * commPct / 100;

        var rates        = TRANSIT_RATES[transitMode];
        var measure      = transitOn ? (rates.unit === 'kg' ? weight : cbm) : 0;
        var transitFees  = transitOn ? rates.client * measure : 0;

        document.getElementById('prev_sale_price').textContent    = salePrice > 0 ? fmt(salePrice) : '—';
        document.getElementById('prev_margin_pct').textContent    = marginPct + '%';
        document.getElementById('prev_client_unit').textContent   = clientUnit > 0 ? fmt(clientUnit) : '—';
        document.getElementById('prev_client_total').textContent  = clientTotal > 0 ? fmt(clientTotal) : '—';
        document.getElementById('prev_comm_type_label').textContent = COMM_LABELS[commType] || COMM_LABELS.percent;
        document.getElementById('prev_comm_pct').textContent      = commType === 'fixed' ? fmt(commFixed) : commPct + '%';
        document.getElementById('prev_service_fees').textContent  = serviceFees > 0 ? fmt(serviceFees) : '0';
        document.getElementById('prev_transit_mode').textContent  = transitOn ? (TRANSIT_LABELS[transitMode] || '—') : '—';
        document.getElementById('prev_transit_fees').textContent  = transitOn && measure > 0 ? fmtFcfa(transitFees) : '—';
        document.getElementById('prev_grand_total').textContent   = (clientTotal + serviceFees) > 0
            ? fmt(clientTotal + serviceFees) + (transitOn ? ' + ' + fmtFcfa(transitFees) : '')
            : '—';
        document.getElementById('prev_transit_payment').textContent = transitOn ? (PAYMENT_LABELS[payMode] || '—') : '—';
    }

    // Measurement type toggle
    function toggleMeasurementFields(type) {
        var fw = document.getElementById('field_weight');
        var fc = document.getElementById('field_cbm');
        if (type === 'cbm') {
            fw.classList.add('d-none');
            fc.classList.remove('d-none');
        } else {
            fw.classList.remove('d-none');
            fc.classList.add('d-none');
        }
        updatePreview();
    }

    // Preset dropdowns
    function wirePreset(presetId, customId, hiddenId) {
        var preset = document.getElementById(presetId);
        var custom = document.getElementById(customId);
        var hidden = document.getElementById(hiddenId);
        if (!preset) return;

        preset.addEventListener('change', function() {
            if (this.value === 'custom') {
                custom.classList.remove('d-none');
                hidden.value = parseFloat(custom.value) || 0;
            } else {
                custom.classList.add('d-none');
                hidden.value = parseFloat(this.value) || 0;
            }
            updatePreview();
        });
        custom.addEventListener('input', function() {
            hidden.value = parseFloat(this.value) || 0;
            updatePreview();
        });
    }

    wirePreset('q_margin_preset',     'q_margin_custom',     'q_margin_percent');
    wirePreset('q_commission_preset', 'q_commission_custom', 'q_commission_percent');

    // Commission type toggle
    function toggleCommType() {
        var isFixed = getCommType() === 'fixed';
        document.getElementById('comm_percent_section').classList.toggle('d-none', isFixed);
        document.getElementById('comm_fixed_section').classList.toggle('d-none', !isFixed);
        if (isFixed) { document.getElementById('q_commission_percent').value = '0'; }
        updatePreview();
    }
    document.querySelectorAll('input[name="commission_type"]').forEach(function(r) {
        r.addEventListener('change', toggleCommType);
    });

    // Fixed amount input listeners
    var fixedInput = document.getElementById('q_commission_fixed');
    if (fixedInput) {
        fixedInput.addEventListener('input', updatePreview);
        fixedInput.addEventListener('blur', function() {
            var norm = normalizeNum(this.value);
            var n = parseFloat(norm);
            if (!isNaN(n)) { this.value = norm; }
        });
    }

    var sel = document.getElementById('measurement_type');
    if (sel) {
        sel.addEventListener('change', function() { toggleMeasurementFields(this.value); });
    }

    ['q_unit_price', 'q_purchase_price', 'q_weight', 'q_cbm'].forEach(function(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', updatePreview);
        el.addEventListener('blur', function() {
            var norm = normalizeNum(this.value);
            var n = parseFloat(norm);
            if (!isNaN(n)) { this.value = norm; }
        });
    });
    ['q_transit_mode', 'q_transit_payment_mode'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('change', updatePreview);
    });

    // Transit section enable/disable toggle
    function toggleTransitSection() {
        var enabled = isTransitEnabled();
        var body    = document.getElementById('transit_fields_body');
        var mode    = document.getElementById('q_transit_mode');
        var pay     = document.getElementById('q_transit_payment_mode');
        if (body) body.classList.toggle('d-none', !enabled);
        [mode, pay].forEach(function(el) {
            if (!el) return;
            el.disabled  = !enabled;
            el.required  = enabled;
        });
        updatePreview();
    }
    var transitToggle = document.getElementById('q_transit_enabled');
    if (transitToggle) {
        transitToggle.addEventListener('change', toggleTransitSection);
        toggleTransitSection();
    }

    // Normalize decimal separators before form submission (support both . and ,)
    var quoteForm = document.querySelector('#quoteModal form');
    if (quoteForm) {
        quoteForm.addEventListener('submit', function() {
            ['q_purchase_price', 'q_unit_price', 'q_weight', 'q_cbm', 'q_commission_fixed'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.value = normalizeNum(el.value);
            });
        });
    }

    var modal = document.getElementById('quoteModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            var type = sel ? sel.value : 'weight';
            toggleMeasurementFields(type);
            updatePreview();
        });
    }

    toggleMeasurementFields(sel ? sel.value : 'weight');
    updatePreview();
})();

$(document).ready(function () {
    // Hardcoded sender and recipient IDs based on Blade variables
    var senderID = "{{ auth()->id() }}";
    var recipientID = "{{ $orderRequest->sellerID }}";

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