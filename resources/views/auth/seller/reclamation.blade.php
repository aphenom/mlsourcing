@extends('auth.theme.dashboard')

@section('content')
<div class="card mb-4">
    <div class="card-header pb-0">
        <h6>{{ __('pages.reclamation_form') }}</h6>
    </div>
    <div class="card-body">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('reclamations.store') }}" method="POST">
            @csrf

            <!-- Select Type -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="type" class="form-label"><strong>{{ __('pages.about') }}</strong></label>
                    <select class="form-select" id="type" name="type" aria-label="Select Type" onchange="handleTypeChange(this.value)">
                        <option value="" selected disabled>{{ __('pages.select_type') }}</option>
                        <option value="request">{{ __('pages.type_request') }}</option>
                        <option value="payment">{{ __('pages.type_payment') }}</option>
                        <option value="order">{{ __('pages.type_order') }}</option>
                        <option value="other">{{ __('pages.type_other') }}</option>
                    </select>
                    @error('type')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Dropdown for Requests -->
            <div class="row mb-3 d-none" id="request-dropdown">
                <div class="col-md-6">
                    <label for="request_id" class="form-label"><strong>{{ __('pages.select_request_label') }}</strong></label>
                    <select class="form-select" id="request_id" name="request_id" aria-label="Select Request">
                        <option value="" selected disabled>{{ __('pages.select_request_opt') }}</option>
                        @if($requests->isEmpty())
                            <option value="" disabled>{{ __('pages.no_requests_available') }}</option>
                        @else
                            @foreach($requests as $request)
                                <option value="{{ $request->id }}">{{ $request->importedproducts->first()->productName }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('request_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Dropdown for Payments -->
            <div class="row mb-3 d-none" id="payment-dropdown">
                <div class="col-md-6">
                    <label for="payment_id" class="form-label"><strong>{{ __('pages.select_payment_label') }}</strong></label>
                    <select class="form-select" id="payment_id" name="payment_id" aria-label="Select Payment">
                        <option value="" selected disabled>{{ __('pages.select_payment_opt') }}</option>
                        @foreach($payments as $payment)
                            <option value="{{ $payment->id }}">Request #{{ $payment->ordersrequests->requestNO }} - {{ $payment->status }}</option>
                        @endforeach
                    </select>
                    @error('payment_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Dropdown for Orders -->
            <div class="row mb-3 d-none" id="order-dropdown">
                <div class="col-md-6">
                    <label for="order_id" class="form-label"><strong>{{ __('pages.select_order_label') }}</strong></label>
                    <select class="form-select" id="order_id" name="order_id" aria-label="Select Order">
                        <option value="" selected disabled>{{ __('pages.select_order_opt') }}</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">{{ $order->importedproducts->first()->productName }}</option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Message Box -->
            <div class="row mb-3">
                <div class="col-12">
                    <label for="message" class="form-label"><strong>{{ __('pages.message_label') }}</strong></label>
                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="{{ __('pages.message_placeholder') }}" aria-label="Message"></textarea>
                    @error('message')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>



            <!-- Submit Button -->
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">{{ __('pages.submit_reclamation') }}</button>
                </div>
            </div>
            <div id="loading-spinner" class="text-center d-none mt-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function handleTypeChange(type) {
        // Hide all dropdowns
        document.getElementById('request-dropdown').classList.add('d-none');
        document.getElementById('payment-dropdown').classList.add('d-none');
        document.getElementById('order-dropdown').classList.add('d-none');

        // Show the relevant dropdown based on type
        if (type === 'request') {
            document.getElementById('request-dropdown').classList.remove('d-none');
        } else if (type === 'payment') {
            document.getElementById('payment-dropdown').classList.remove('d-none');
        } else if (type === 'order') {
            document.getElementById('order-dropdown').classList.remove('d-none');
        }
    }

    document.querySelector('form').addEventListener('submit', function () {
        document.getElementById('loading-spinner').classList.remove('d-none');
    });


</script>
@endpush
@endsection
