@extends('auth.theme.dashboard')
@section('title', __('pages.profile'))

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

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <ul class="nav nav-pills mb-4" id="profileTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#tab-info">
                        <i class="fa fa-user me-1"></i> {{ __('pages.profile_info') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#tab-password">
                        <i class="fa fa-lock me-1"></i> {{ __('pages.change_password') }}
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane fade show active" id="tab-info">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>{{ __('pages.profile_info') }}</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('comptable.updateProfile') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('pages.seller_name') }}*</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('pages.email') }}*</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('pages.phone') }}</label>
                                        <input type="text" name="phone_number" class="form-control"
                                               value="{{ old('phone_number', $user->phone_number) }}">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn bg-gradient-success w-100">
                                            {{ __('pages.save') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-password">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>{{ __('pages.change_password') }}</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('comptable.updatePassword') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">{{ __('pages.current_password') }}*</label>
                                    <input type="password" name="current_password"
                                           class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('pages.new_password') }}*</label>
                                    <input type="password" name="new_password"
                                           class="form-control @error('new_password') is-invalid @enderror" required minlength="8">
                                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('pages.confirm_new_password') }}*</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" required>
                                </div>
                                <button type="submit" class="btn bg-gradient-primary w-100">
                                    {{ __('pages.change_password') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    @if($errors->hasAny(['current_password','new_password']))
        document.querySelector('[href="#tab-password"]').click();
    @endif
</script>
@endpush
@endsection
