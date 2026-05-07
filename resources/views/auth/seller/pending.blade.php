@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card text-center shadow">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="#f0ad4e" viewBox="0 0 16 16">
                            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1m0 1a6 6 0 1 1 0 12A6 6 0 0 1 8 2"/>
                            <path d="M7.5 4h1v4h-1zm0 5h1v1h-1z"/>
                        </svg>
                    </div>
                    <h3 class="mb-3">{{ __('pages.account_pending_title') }}</h3>
                    <p class="text-muted mb-4">{{ __('pages.account_pending_message') }}</p>
                    <p class="text-muted small">{{ __('pages.account_pending_contact') }}</p>
                    <div class="mt-4">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                {{ __('global.menu_logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
