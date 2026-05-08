@extends('auth.theme.dashboard')
@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ __('pages.currency_management') }}</h6>
                    <form method="POST" action="{{ route('comptable.currencies.sync') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm bg-gradient-info mb-0">
                            <i class="fa fa-sync me-1"></i>{{ __('pages.sync_from_api') }}
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('pages.currency_code') }}</th>
                                    <th>{{ __('pages.currency_symbol') }}</th>
                                    <th>{{ __('pages.currency_name') }}</th>
                                    <th>{{ __('pages.fcfa_per_unit') }}</th>
                                    <th>{{ __('pages.rate_updated_at') }}</th>
                                    <th>{{ __('pages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($currencies as $currency)
                                <tr>
                                    <td><span class="badge bg-gradient-primary">{{ $currency->code }}</span></td>
                                    <td class="fw-bold">{{ $currency->symbol }}</td>
                                    <td>{{ $currency->name }}</td>
                                    <td>
                                        @if($currency->code === 'XOF')
                                            <span class="text-muted">1 <small>(référence)</small></span>
                                        @else
                                            {{ number_format($currency->fcfa_per_unit, 2) }} FCFA
                                            <small class="text-muted d-block">
                                                1 {{ $currency->symbol }} = {{ number_format($currency->fcfa_per_unit, 2) }} FCFA
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-sm text-muted">
                                        {{ $currency->rate_updated_at ? \Carbon\Carbon::parse($currency->rate_updated_at)->isoFormat('LLL') : '—' }}
                                    </td>
                                    <td>
                                        @if($currency->code !== 'XOF')
                                        <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editRateModal"
                                                data-code="{{ $currency->code }}"
                                                data-rate="{{ $currency->fcfa_per_unit }}"
                                                data-name="{{ $currency->name }}">
                                            <i class="fa fa-edit me-1"></i>{{ __('pages.edit_rate') }}
                                        </button>
                                        @else
                                        <span class="text-muted text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rate History --}}
    @if($history->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">{{ __('pages.rate_history') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('pages.currency_code') }}</th>
                                    <th>{{ __('pages.old_rate') }}</th>
                                    <th>{{ __('pages.source') }}</th>
                                    <th>{{ __('pages.changed_at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $h)
                                <tr>
                                    <td><span class="badge bg-gradient-secondary">{{ $h->code }}</span></td>
                                    <td>{{ number_format($h->fcfa_per_unit, 2) }} FCFA</td>
                                    <td>
                                        <span class="badge {{ $h->source === 'api' ? 'bg-gradient-info' : 'bg-gradient-warning' }}">
                                            {{ $h->source }}
                                        </span>
                                    </td>
                                    <td class="text-muted text-sm">{{ \Carbon\Carbon::parse($h->changed_at)->isoFormat('LLL') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Edit Rate Modal --}}
<div class="modal fade" id="editRateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="editRateTitle">{{ __('pages.edit_rate') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editRateForm">
                @csrf
                <div class="modal-body">
                    <p class="text-sm text-muted mb-3">{{ __('pages.fcfa_per_unit_hint') }}</p>
                    <label class="form-label text-sm">{{ __('pages.fcfa_per_unit') }}</label>
                    <div class="input-group">
                        <input type="number" name="fcfa_per_unit" id="edit_rate_value"
                               class="form-control" step="0.01" min="0.01" required>
                        <span class="input-group-text">FCFA</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('pages.close') }}</button>
                    <button type="submit" class="btn bg-gradient-success btn-sm text-white">{{ __('pages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('[data-bs-target="#editRateModal"]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var code = this.dataset.code;
        document.getElementById('editRateTitle').textContent = '{{ __('pages.edit_rate') }} — ' + code;
        document.getElementById('edit_rate_value').value = parseFloat(this.dataset.rate);
        document.getElementById('editRateForm').action = '/comptable/currencies/' + code + '/update';
    });
});
</script>
@endpush
@endsection
