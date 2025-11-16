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
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Sellers</p>
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

        <!-- Card 2: Total Agents -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Agents</p>
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

        <!-- Card 3: Total Requests -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
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

        <!-- Card 4: Requests Quoting -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Requests Quoting</p>
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

        <!-- Card 5: Requests Quoted -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Requests Quoted</p>
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

        <!-- Card 6: Orders Awaiting Payment -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders Awaiting Payment</p>
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

        <!-- Card 7: Orders Paid -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders Paid</p>
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

        <!-- Card 8: Orders Awaiting Shipping -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders Awaiting Shipping</p>
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

        <!-- Card 9: Orders In Transit -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders In Transit</p>
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

        <!-- Card 10: Orders Delivered -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders Delivered</p>
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

        <!-- Card 11: Amount Paid -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Amount Paid</p>
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

    <!-- Sourcing and Destination Countries -->
    <div class="row g-4 mt-4">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white text-center">Sourcing Countries</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($sourcingCountriesList as $country)
                            <li class="list-group-item">{{ $country->country_name }}</li>
                        @empty
                            <li class="list-group-item">No sourcing countries managed</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white text-center">Destination Countries</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($destinationCountriesList as $country)
                            <li class="list-group-item">{{ $country->country_name }}</li>
                        @empty
                            <li class="list-group-item">No destination countries managed</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- Notifications Section -->
    <div class="row mt-4">
    <div class="col-12">
        <div class="card">
        <div class="card-header pb-3">
            <h5 class="mb-0 text-center">Notifications</h5>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-3">
            <table class="table align-items-center table-hover">
                <thead class="bg-gradient-primary text-white">
                <tr>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">Date</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">Subject</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">Message</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">Link</th>
                    <th class="text-center text-uppercase text-xs font-weight-bold opacity-9">Actions</th>
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
                        <a href="{{ $notification->data['link'] }}" class="badge btn bg-gradient-dark">View</a>
                    </td>
                    <td class="text-center" style="border: 1px solid #d3d3d3">
                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-rounded">Delete</button>
                        </form>
                    </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="5" class="text-center">
                        <p class="text-sm font-weight-bold mb-0">No notifications available.</p>
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
