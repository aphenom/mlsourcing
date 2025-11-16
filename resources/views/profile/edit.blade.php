@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>{{ __('Profile') }}</h6>
                </div>
                <div class="card-body">
                    <div class="p-4 shadow-sm rounded" style="background-color:#fbfbfb !important;">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-4 shadow-sm rounded mt-4" style="background-color:#fbfbfb !important;">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection