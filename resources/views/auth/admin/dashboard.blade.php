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
                                <h5 class="font-weight-bolder mb-0">{{ format_currency($totalAmountPaid) }}</h5>
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

    {{-- Financial KPIs --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0">{{ __('pages.financial_kpis') }}</h6>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="text-xs text-muted">{{ __('pages.period_start') }}</span>
                        <input type="date" name="period_start" class="form-control form-control-sm" style="width:140px"
                               value="{{ $periodStart }}">
                        <span class="text-xs text-muted">{{ __('pages.period_end') }}</span>
                        <input type="date" name="period_end" class="form-control form-control-sm" style="width:140px"
                               value="{{ $periodEnd }}">
                        <button type="submit" class="btn btn-sm bg-gradient-primary mb-0">{{ __('pages.apply_filter') }}</button>
                        @if($periodStart || $periodEnd)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-0">{{ __('pages.reset_filter') }}</a>
                        @endif
                    </form>
                </div>
                <div class="card-body pt-2">
                    <div class="row g-3">
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="bg-gradient-success rounded p-3 text-white text-center">
                                <p class="text-xs mb-1 opacity-9">{{ __('pages.ca_label') }}</p>
                                <h6 class="mb-0 font-weight-bolder text-white">{{ format_currency($financialCA) }}</h6>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="bg-gradient-danger rounded p-3 text-white text-center">
                                <p class="text-xs mb-1 opacity-9">{{ __('pages.purchase_cost_label') }}</p>
                                <h6 class="mb-0 font-weight-bolder text-white">{{ format_currency($financialPurchase) }}</h6>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="bg-gradient-info rounded p-3 text-white text-center">
                                <p class="text-xs mb-1 opacity-9">{{ __('pages.commissions_label') }}</p>
                                <h6 class="mb-0 font-weight-bolder text-white">{{ format_currency($financialCommission) }}</h6>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="bg-gradient-warning rounded p-3 text-white text-center">
                                <p class="text-xs mb-1 opacity-9">{{ __('pages.transit_margins_label') }}</p>
                                <h6 class="mb-0 font-weight-bolder text-white">{{ format_currency($financialTransit) }}</h6>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-8 col-12">
                            <div class="bg-gradient-dark rounded p-3 text-white text-center">
                                <p class="text-xs mb-1 opacity-9">{{ __('pages.global_profit_label') }}</p>
                                <h6 class="mb-0 font-weight-bolder text-white">{{ format_currency($financialGlobalProfit) }}</h6>
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
                        <p class="text-sm font-weight-bold mb-0">{{ $notification->created_at->isoFormat('L') }}</p>
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
