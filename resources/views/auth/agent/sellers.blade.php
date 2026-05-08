@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Sellers List -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ __('pages.sellers') }}</h6>
                </div>
                <div class="card-body px-3 pt-3 pb-2">
                    <div class="table-responsive">
                        <table id="sellersTable" class="table table-hover align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>{{ __('pages.seller_name') }}</th>
                                    <th>{{ __('pages.email') }}</th>
                                    <th>{{ __('pages.phone') }}</th>
                                    <th>{{ __('pages.user_type') }}</th>
                                    <th>{{ __('pages.account_status') }}</th>
                                    <th>{{ __('pages.registered_at') }}</th>
                                    <th>{{ __('pages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sellers as $seller)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $seller->name }}</td>
                                    <td class="text-secondary">{{ $seller->email }}</td>
                                    <td>{{ $seller->phone_number ?? '-' }}</td>
                                    <td>{{ ucfirst($seller->user_type) }}</td>
                                    <td>
                                        @if($seller->status === 'active')
                                            <span class="badge bg-gradient-success text-xs">{{ __('pages.active') }}</span>
                                        @elseif($seller->status === 'pending')
                                            <span class="badge bg-gradient-warning text-xs">{{ __('pages.pending') }}</span>
                                        @else
                                            <span class="badge bg-gradient-danger text-xs">{{ __('pages.blocked') }}</span>
                                        @endif
                                    </td>
                                    <td data-order="{{ $seller->created_at->timestamp }}">
                                        {{ $seller->created_at->isoFormat('L') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <button type="button"
                                                    class="btn btn-xs btn-outline-info py-1 px-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editSellerModal"
                                                    data-id="{{ $seller->id }}"
                                                    data-name="{{ $seller->name }}"
                                                    data-email="{{ $seller->email }}"
                                                    data-phone="{{ $seller->phone_number }}"
                                                    data-address="{{ $seller->address }}"
                                                    data-usertype="{{ $seller->user_type }}"
                                                    data-company="{{ $seller->company_name }}"
                                                    data-companyinfo="{{ $seller->company_information }}">
                                                <i class="fa fa-edit me-1"></i>{{ __('pages.edit_seller') }}
                                            </button>
                                            @if($seller->status === 'pending')
                                                <form method="POST" action="{{ route('agent.activateSeller', $seller->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline-success py-1 px-2">
                                                        <i class="fa fa-check me-1"></i>{{ __('pages.activate') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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

    <!-- Create Seller -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.create_seller') }}</h6>
                    <p class="text-sm text-muted mb-0">{{ __('pages.create_seller_hint') }}</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('agent.storeSeller') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('pages.seller_name') }}*</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('pages.email') }}*</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('pages.phone') }}*</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('pages.user_type') }}*</label>
                                <select name="user_type" id="create_usertype_agent" class="form-control" required>
                                    <option value="particular" {{ old('user_type') === 'particular' ? 'selected' : '' }}>{{ __('pages.particular') }}</option>
                                    <option value="company"    {{ old('user_type') === 'company'    ? 'selected' : '' }}>{{ __('pages.company') }}</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('pages.address_label') }}*</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                            </div>
                            <div id="create_company_fields_agent" class="{{ old('user_type') === 'company' ? '' : 'd-none' }}">
                                <div class="col-12 mb-3">
                                    <label class="form-label">{{ __('pages.company_name_label') }}*</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">{{ __('pages.company_information_label') }}</label>
                                    <textarea name="company_information" class="form-control" rows="3">{{ old('company_information') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn bg-gradient-success w-100">{{ __('pages.create_seller') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Edit Seller Modal --}}
<div class="modal fade" id="editSellerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('pages.edit_seller') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editSellerForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('pages.seller_name') }}*</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('pages.email') }}*</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('pages.phone') }}</label>
                            <input type="text" name="phone_number" id="edit_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('pages.user_type') }}*</label>
                            <select name="user_type" id="edit_usertype" class="form-control" required>
                                <option value="particular">{{ __('pages.particular') }}</option>
                                <option value="company">{{ __('pages.company') }}</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">{{ __('pages.address_label') }}</label>
                            <input type="text" name="address" id="edit_address" class="form-control">
                        </div>
                        <div id="edit_company_fields">
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('pages.company_name_label') }}</label>
                                <input type="text" name="company_name" id="edit_company" class="form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('pages.company_information_label') }}</label>
                                <textarea name="company_information" id="edit_companyinfo" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <hr>
                            <p class="text-sm text-muted">{{ __('pages.reset_password') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('pages.new_password') }}</label>
                            <input type="password" name="new_password" class="form-control" minlength="8">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('pages.confirm_new_password') }}</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#sellersTable').DataTable({
        order: [[6, 'desc']],
        columnDefs: [
            { targets: 0, orderable: false, searchable: false,
              render: function(data, type, row, meta) { return meta.row + 1; } },
            { targets: -1, orderable: false, searchable: false }
        ],
        pageLength: 25,
        language: {
            search:      "{{ __('pages.search') ?? 'Rechercher' }}:",
            lengthMenu:  "_MENU_ {{ __('pages.per_page') ?? 'par page' }}",
            zeroRecords: "{{ __('pages.no_sellers') }}",
            info:        "{{ __('pages.showing') ?? 'Affichage de' }} _START_ {{ __('pages.to') ?? 'à' }} _END_ {{ __('pages.of') ?? 'sur' }} _TOTAL_",
            paginate: { previous: "‹", next: "›" }
        }
    });
});

// Create form: show/hide company fields
var createType = document.getElementById('create_usertype_agent');
if (createType) {
    createType.addEventListener('change', function() {
        document.getElementById('create_company_fields_agent').classList.toggle('d-none', this.value !== 'company');
    });
}

document.querySelectorAll('[data-bs-target="#editSellerModal"]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id       = this.dataset.id;
        var userType = this.dataset.usertype;

        document.getElementById('edit_name').value        = this.dataset.name;
        document.getElementById('edit_email').value       = this.dataset.email;
        document.getElementById('edit_phone').value       = this.dataset.phone;
        document.getElementById('edit_address').value     = this.dataset.address;
        document.getElementById('edit_company').value     = this.dataset.company;
        document.getElementById('edit_companyinfo').value = this.dataset.companyinfo;

        var sel = document.getElementById('edit_usertype');
        for (var i = 0; i < sel.options.length; i++) {
            sel.options[i].selected = (sel.options[i].value === userType);
        }
        toggleCompanyFields(userType);

        document.getElementById('editSellerForm').action = '/agent/sellers/' + id + '/update';
    });
});

document.getElementById('edit_usertype').addEventListener('change', function() {
    toggleCompanyFields(this.value);
});

function toggleCompanyFields(type) {
    document.getElementById('edit_company_fields').style.display = (type === 'company') ? '' : 'none';
}
</script>
@endpush

@endsection
