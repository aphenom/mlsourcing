@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Display general validation errors if there are any -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display exception error messages if any -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Display success messages if any -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Display product info -->
                    <div class="mb-4">
                        <h4 class="font-weight-bold mb-3">{{ __('pages.product_information') }}</h4>
                        <div class="row">
                            <div class="col-md-2">
                                <strong>{{ __('pages.request_no') }}:</strong>
                                <p class="mb-0">{{ $orderRequest->requestNO }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>{{ __('pages.product_name_col') }}:</strong>
                                <p class="mb-0">{{ $importedProduct->productName }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>{{ __('pages.quantity_col') }}:</strong>
                                <p class="mb-0">{{ $importedProduct->qte }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>{{ __('pages.country_from') }}:</strong>
                                <p class="mb-0">{{ $orderRequest->countryFrom }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>{{ __('pages.country_to') }}:</strong>
                                <p class="mb-0">{{ $orderRequest->countryTo }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dispatch form -->
                    <form action="{{ route('agent.dispatch', $orderRequest->id) }}" method="POST">
                        @csrf
                        <div class="row align-items-center">
                            <!-- Image on the left -->
                            <div class="col-md-5">
                                <img src="https://cdn.codpartner.com/assets/img/pngs/sourcing.png" class="img-fluid rounded" alt="Shipping Image">
                            </div>
                            
                            <!-- Form on the right -->
                            <div class="col-md-7">
                                <h4 class="font-weight-bold mb-3">{{ __('pages.ship_the_order') }}</h4>

                                <div class="mb-3">
                                    <label for="carrier" class="form-label">{{ __('pages.carrier_name_input') }}</label>
                                    <input type="text" id="carrier" name="carrier" class="form-control" placeholder="Carrier name" value="{{ old('carrier', $importedProduct->carrier) }}" required>
                                    @error('carrier')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tracking_number" class="form-label">{{ __('pages.tracking_number_input') }}</label>
                                    <input type="text" id="tracking_number" name="tracking_number" class="form-control" placeholder="Tracking number" value="{{ old('tracking_number', $importedProduct->trackingNumber) }}" required>
                                    @error('tracking_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="shipping_status" class="form-label">{{ __('pages.shipping_status') }}</label>
                                    <select id="shipping_status" name="shipping_status" class="form-select" required>
                                        <option value="" disabled {{ old('shipping_status', $importedProduct->statusProduct) === null ? 'selected' : '' }}>{{ __('pages.select_status') }}</option>
                                        <option value="-" {{ old('shipping_status', $importedProduct->statusProduct) == '-' ? 'selected' : '' }}>{{ __('pages.not_yet') }}</option>
                                        <option value="preparing" {{ old('shipping_status', $importedProduct->statusProduct) == 'preparing' ? 'selected' : '' }}>{{ __('pages.preparing') }}</option>
                                        <option value="shipped" {{ old('shipping_status', $importedProduct->statusProduct) == 'shipped' ? 'selected' : '' }}>{{ __('pages.shipped') }}</option>
                                        <option value="in transit" {{ old('shipping_status', $importedProduct->statusProduct) == 'in transit' ? 'selected' : '' }}>{{ __('pages.in_transit') }}</option>
                                        <option value="delivered" {{ old('shipping_status', $importedProduct->statusProduct) == 'delivered' ? 'selected' : '' }}>{{ __('pages.delivered') }}</option>
                                    </select>
                                    @error('shipping_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">{{ __('pages.mark_as_shipped') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
