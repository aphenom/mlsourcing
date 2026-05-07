@extends('auth.theme.dashboard')
@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.total_sellers') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalSellers }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.total_agents') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalAgents }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-user-run text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.total_requests') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalRequests }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.requests_quoting') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalQuotingRequests }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="ni ni-ruler-pencil text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.requests_quoted') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalQuotedRequests }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.orders_awaiting_payment') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersAwaitingPayment }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.orders_paid') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersPaid }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.orders_awaiting_shipping') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersAwaitingShipping }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                <i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.orders_in_transit') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersShipped }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-secondary shadow text-center border-radius-md">
                                <i class="ni ni-send text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.orders_delivered') }}</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersDelivered }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-light shadow text-center border-radius-md">
                                <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ __('pages.amount_paid') }}</p>
                                <h5 class="font-weight-bolder mb-0">${{ number_format($totalAmountPaid, 2) }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.sourcing_countries') }}</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($sourcingCountriesList as $country)
                            <li class="list-group-item">{{ $country->country_name }}</li>
                        @empty
                            <li class="list-group-item">{{ __('pages.no_sourcing_countries') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white text-center">{{ __('pages.destination_countries') }}</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($destinationCountriesList as $country)
                            <li class="list-group-item">{{ $country->country_name }}</li>
                        @empty
                            <li class="list-group-item">{{ __('pages.no_destination_countries') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 d-none">
    <div class="col-12">
        <div class="card">
        <div class="card-header pb-3">
            <h5 class="mb-0 text-center">{{ __('pages.notifications') }}</h5>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-3">
            <table class="table align-items-center table-hover">
                <thead class="bg-gradient-primary text-white">
                <tr>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">{{ __('pages.date') }}</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">{{ __('pages.subject') }}</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">{{ __('pages.message_col') }}</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">{{ __('pages.link_label') }}</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">{{ __('pages.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($notifications as $notification)
                    <tr>
                    <td class="text-center" style="border: 1px solid #d3d3d3">
                        <p class="text-sm font-weight-bold mb-0">{{ $notification->created_at->format('Y-m-d') }}</p>
                    </td>
                    <td class="text-center" style="border: 1px solid #d3d3d3">
                        <p class="text-sm font-weight-bold mb-0">{{ $notification->data['subject'] }}</p>
                    </td>
                    <td class="text-center" style="border: 1px solid #d3d3d3">
                        <p class="text-sm mb-0">{{ $notification->data['message'] }}</p>
                    </td>
                    <td class="text-center" style="border: 1px solid #d3d3d3">
                        <a href="{{ $notification->data['link'] }}" class="badge btn bg-gradient-dark">{{ __('pages.view') }}</a>
                    </td>
                    <td class="text-center" style="border: 1px solid #d3d3d3">
                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-rounded">{{ __('pages.delete') }}</button>
                        </form>
                    </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="5" class="text-center">
                        <p class="text-sm font-weight-bold mb-0">{{ __('pages.no_notifications') }}</p>
                    </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
