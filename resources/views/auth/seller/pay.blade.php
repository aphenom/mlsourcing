<!-- resources/views/auth/seller/pay.blade.php -->

@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.attention') }}</h6>
                </div>
                <div class="card-body">
                    <p>{{ __('pages.insert_reference') }}</p>
                    <p><strong>{{ __('pages.request_number') }}:</strong> {{ $orderRequest->requestNO }}</p>
                    <p><strong>{{ __('pages.total_price') }}:</strong> ${{ $orderRequest->importedproducts->first()->totalPrice }}</p>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.select_payment_method') }}</h6>
                </div>
                <div class="card-body">
                    @php
                        $existingPayment = $orderRequest->payments->first();
                        $paymentDisapproved = $existingPayment && $existingPayment->status === 'Disapproved';
                        $paymentExists = !is_null($existingPayment);
                    @endphp

                    @if(!$paymentExists || $paymentDisapproved)
                        <div class="payment-methods row">
                            @foreach($paymentOptions as $option)
                                <div class="col-md-4 mb-3">
                                    <div class="card payment-option cursor-pointer" data-option-id="{{ $option->id }}">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $option->image) }}" class="card-img-top me-3" alt="{{ $option->name }}" style="width: 50px; height: 50px;">
                                                <div>
                                                    <h6 class="card-title">{{ $option->name }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($paymentExists && !$paymentDisapproved)
                        <div class="alert alert-info">
                            {{ __('pages.payment_already_processed') }}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4 d-none" id="payment-info-card">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.payment_details') }}</h6>
                </div>
                <div class="card-body">
                    <div id="payment-info-content">
                        <!-- Payment information will be inserted here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentInfoCard = document.getElementById('payment-info-card');
        const paymentInfoContent = document.getElementById('payment-info-content');

        paymentOptions.forEach(option => {
            option.addEventListener('click', function () {
                const optionId = this.dataset.optionId;

                const optionDetails = {
                    @foreach($paymentOptions as $option)
                        {{ $option->id }}: {
                            requestNO: "{{ $orderRequest->requestNO }}",
                            totalPrice: "{{ $orderRequest->importedproducts->first()->totalPrice }}",
                            details: @json(json_decode($option->details, true)),
                            id: "{{ $option->id }}"
                        },
                    @endforeach
                };

                const data = optionDetails[optionId];

                let detailsHtml = `
                    <p><strong>Request Number:</strong> ${data.requestNO}</p>
                    <p><strong>Total Price:</strong> $${parseFloat(data.totalPrice).toFixed(2)}</p>
                `;

                for (const key in data.details) {
                    if (typeof data.details[key] === 'object') {
                        detailsHtml += `<p><strong>${key}:</strong></p><ul>`;
                        for (const subKey in data.details[key]) {
                            detailsHtml += `<li>${subKey}: ${data.details[key][subKey]}</li>`;
                        }
                        detailsHtml += `</ul>`;
                    } else {
                        detailsHtml += `<p><strong>${key}:</strong> ${data.details[key]}</p>`;
                    }
                }

                detailsHtml += `
                    <form action="{{ route('seller.pay', ['orderRequestId' => $orderRequest->id]) }}" method="POST" enctype="multipart/form-data" class="payment-form">
                        @csrf
                        <input type="hidden" name="payment_option_id" value="${data.id}">
                        <div class="mb-3">
                            <label for="screenshot" class="form-label">{{ __('pages.upload_payment_proof') }}</label>
                            <input type="file" name="screenshot" id="screenshot-${data.id}" class="form-control screenshot-input @error('screenshot') is-invalid @enderror" required>
                            @error('screenshot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary submit-button" disabled>{{ __('pages.submit_payment') }}</button>
                    </form>
                `;

                paymentInfoContent.innerHTML = detailsHtml;
                paymentInfoCard.classList.remove('d-none');

                const fileInput = document.querySelector(`#screenshot-${data.id}`);
                const submitButton = document.querySelector('.submit-button');

                fileInput.addEventListener('change', function () {
                    if (this.files.length > 0) {
                        submitButton.removeAttribute('disabled');
                    } else {
                        submitButton.setAttribute('disabled', true);
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
