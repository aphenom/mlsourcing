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
                        <h4 class="font-weight-bold mb-3">Product Information</h4>
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Request No:</strong>
                                <p class="mb-0">{{ $orderRequest->requestNO }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Product Name:</strong>
                                <p class="mb-0">{{ $importedProduct->productName }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Quantity:</strong>
                                <p class="mb-0">{{ $importedProduct->qte }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Country From:</strong>
                                <p class="mb-0">{{ $orderRequest->countryFrom }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong>Country To:</strong>
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
                                <h4 class="font-weight-bold mb-3">Ship The Order</h4>

                                <div class="mb-3">
                                    <label for="carrier" class="form-label">Carrier Name*</label>
                                    <input type="text" id="carrier" name="carrier" class="form-control" placeholder="Carrier name" value="{{ old('carrier', $importedProduct->carrier) }}" required>
                                    @error('carrier')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tracking_number" class="form-label">Tracking Number*</label>
                                    <input type="text" id="tracking_number" name="tracking_number" class="form-control" placeholder="Tracking number" value="{{ old('tracking_number', $importedProduct->trackingNumber) }}" required>
                                    @error('tracking_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="shipping_status" class="form-label">Shipping Status*</label>
                                    <select id="shipping_status" name="shipping_status" class="form-select" required>
                                        <option value="" disabled {{ old('shipping_status', $importedProduct->statusProduct) === null ? 'selected' : '' }}>Select Status</option>
                                        <option value="-" {{ old('shipping_status', $importedProduct->statusProduct) == '-' ? 'selected' : '' }}>Not Yet</option>
                                        <option value="preparing" {{ old('shipping_status', $importedProduct->statusProduct) == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="shipped" {{ old('shipping_status', $importedProduct->statusProduct) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="in transit" {{ old('shipping_status', $importedProduct->statusProduct) == 'in transit' ? 'selected' : '' }}>In Transit</option>
                                        <option value="delivered" {{ old('shipping_status', $importedProduct->statusProduct) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                    @error('shipping_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Mark As Shipped</button>
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
