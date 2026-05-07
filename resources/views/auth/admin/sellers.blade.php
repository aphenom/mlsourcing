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
                    <h6>{{ __('pages.sellers') }}</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
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
                                @forelse($sellers as $seller)
                                <tr>
                                    <td>{{ $seller->id }}</td>
                                    <td>{{ $seller->name }}</td>
                                    <td>{{ $seller->email }}</td>
                                    <td>{{ $seller->phone_number ?? '-' }}</td>
                                    <td>{{ ucfirst($seller->user_type) }}</td>
                                    <td>
                                        @if($seller->status === 'active')
                                            <span class="badge bg-gradient-success">{{ __('pages.active') }}</span>
                                        @elseif($seller->status === 'pending')
                                            <span class="badge bg-gradient-warning">{{ __('pages.pending') }}</span>
                                        @else
                                            <span class="badge bg-gradient-danger">{{ __('pages.blocked') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $seller->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($seller->status === 'pending')
                                            <form method="POST" action="{{ route('admin.activateSeller', $seller->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm bg-gradient-success text-white">{{ __('pages.activate') }}</button>
                                            </form>
                                        @elseif($seller->status === 'active')
                                            <form method="POST" action="{{ route('admin.blockSeller', $seller->id) }}" class="d-inline" onsubmit="return confirm('{{ __('pages.confirm_block') }}')">
                                                @csrf
                                                <button class="btn btn-sm bg-gradient-danger text-white">{{ __('pages.block') }}</button>
                                            </form>
                                        @elseif($seller->status === 'blocked')
                                            <form method="POST" action="{{ route('admin.unblockSeller', $seller->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm bg-gradient-primary text-white">{{ __('pages.unblock') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center">{{ __('pages.no_sellers') }}</td></tr>
                                @endforelse
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
                    <p class="text-sm text-muted">{{ __('pages.create_seller_hint') }}</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.storeSeller') }}">
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
                                <select name="user_type" class="form-control" required>
                                    <option value="particular" {{ old('user_type') === 'particular' ? 'selected' : '' }}>{{ __('pages.particular') }}</option>
                                    <option value="company" {{ old('user_type') === 'company' ? 'selected' : '' }}>{{ __('pages.company') }}</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('pages.address_label') }}*</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
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
@endsection
