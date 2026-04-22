@extends('auth.theme.dashboard')
@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Card 1: Requests Arrived -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Requests Arrived</p>
                                <h5 class="font-weight-bolder mb-0">{{ $requestsArrived }}</h5>
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

        <!-- Card 2: Requests Quoted -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Requests Quoted</p>
                                <h5 class="font-weight-bolder mb-0">{{ $requestsQuoted }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Requests Pending Quoting -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Requests Pending Quoting</p>
                                <h5 class="font-weight-bolder mb-0">{{ $requestsPendingQuoting }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Orders Paid -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Orders Paid</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersPaid }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Total Orders Waiting Payment -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders Waiting Payment</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersWaitingPayment }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 6: Orders Waiting for Shipping -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Orders Waiting for Shipping</p>
                                <h5 class="font-weight-bolder mb-0">{{ $ordersWaitingForShipping }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-secondary shadow text-center border-radius-md">
                                <i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 7: Shipped Orders -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Shipped Orders</p>
                                <h5 class="font-weight-bolder mb-0">{{ $shippedOrders }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                <i class="ni ni-send text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 8: Total Orders Arrived -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Orders Arrived</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalOrdersArrived }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-box-2 text-lg opacity-10" aria-hidden="true"></i>
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
                        @forelse($sourcing_countries as $country)
                            <li class="list-group-item">{{ $country }}</li>
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
                        @forelse($destination_countries as $country)
                            <li class="list-group-item">{{ $country }}</li>
                        @empty
                            <li class="list-group-item">No destination countries managed</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="row mt-4 d-none">
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
