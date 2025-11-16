@extends('auth.theme.dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Orders</h6>
                        <!-- Add filters here -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="filter-form" class="row">
                                    <div class="form-group col-md-2">
                                        <label for="dateFrom">Date From:</label>
                                        <input type="date" class="form-control" id="dateFrom" name="date_from">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="dateTo">Date To:</label>
                                        <input type="date" class="form-control" id="dateTo" name="date_to">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="agentFilter">Agent:</label>
                                        <select class="form-control" id="agentFilter" name="agent_id">
                                            <option value="">All</option>
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }}
                                                    ({{ $agent->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="sourcingCountryFilter">Sourcing Country:</label>
                                        <select class="form-control" id="sourcingCountryFilter"
                                            name="sourcing_country_name">
                                            <option value="">All</option>
                                            @foreach ($sourcingCountries as $country)
                                                <option value="{{ $country->country_name }}">{{ $country->country_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="destinationCountryFilter">Destination Country:</label>
                                        <select class="form-control" id="destinationCountryFilter"
                                            name="destination_country_name">
                                            <option value="">All</option>
                                            @foreach ($destinationCountries as $country)
                                                <option value="{{ $country->country_name }}">{{ $country->country_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                                    
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-primary" style="width:100%;">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div id="chart-container" style="width:100%; height:400px;"></div>
                        <div id="table-responsive-id" class="table-responsive">
                            <table id="example" class="table table-bordered table-striped table-hover" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Agent</th>
                                        <th>Request No</th>
                                        <th>Product Name</th>
                                        <th>Product Link</th>
                                        <th>Sourcing Country</th>
                                        <th>Destination Country</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>Weight</th>
                                        <th>Tracking Number</th>
                                        <th>Carrier</th>
                                        <th>Status Product</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <!-- Data will be populated by DataTables -->
                                </tbody>
                                <tfoot class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Agent</th>
                                        <th>Request No</th>
                                        <th>Product Name</th>
                                        <th>Product Link</th>
                                        <th>Sourcing Country</th>
                                        <th>Destination Country</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>Weight</th>
                                        <th>Tracking Number</th>
                                        <th>Carrier</th>
                                        <th>Status Product</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#example').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 't<"bottom"ip>', // Remove the search box and only show table, info, and pagination

                    ajax: {
                        url: '{{ route('admin.ordersData') }}',
                        data: function(d) {
                            d.agent_id = $('#agentFilter').val();
                            d.sourcing_country_name = $('#sourcingCountryFilter').val();
                            d.destination_country_name = $('#destinationCountryFilter').val();
                            d.date_from = $('#dateFrom').val();
                            d.date_to = $('#dateTo').val();
                            d.status = $('#statusFilter').val();
                            d.start = d.start;
                            d.length = d.length;
                        }
                    },
                    columns: [
                        {data: 'created_at',name: 'created_at'},
                        {data: 'agent',name: 'agent'},
                        {data: 'request_no',name: 'request_no'},
                        {data: 'product_name',name: 'product_name'},
                        {
                            data: 'product_url',
                            name: 'product_url',
                            render: function(data, type, row) {
                                return `<a class="badge btn bg-gradient-dark" href="${data}">View Product</a>`;
                            }
                        },
                        {data: 'sourcing_country',name: 'sourcing_country'},
                        {data: 'destination_country',name: 'destination_country'},
                        {data: 'qte',name: 'qte'},
                        {data: 'unitPrice',name: 'unitPrice'},
                        {data: 'totalPrice',name: 'totalPrice'},
                        {data: 'weight',name: 'weight'},
                        {data: 'trackingNumber',name: 'trackingNumber'},
                        {data: 'carrier',name: 'carrier'},
                        {
                            data: 'statusProduct', 
                            name: 'statusProduct',
                            render: function(data, type, row) {
                                let className;
                                let displayText = data;

                                if (data === '-' || data === null) {
                                    displayText = 'Not Yet'; // Show "Not Yet" for '-' or null
                                    className = 'bg-gradient-warning';
                                } else {
                                    switch (data.toLowerCase()) {
                                        case 'shipped':
                                            className = 'bg-gradient-primary';
                                            break;
                                        case 'delivered':
                                            className = 'bg-gradient-success';
                                            break;
                                        default:
                                            className = 'bg-gradient-warning';
                                            break;
                                    }
                                }

                                return `<p class="badge ${className}">${displayText}</p>`;
                            }
                        }
                    ],

                });

                const chart = Highcharts.chart('chart-container', {
                    chart: {
                        type: 'pie',
                        styledMode: true
                    },
                    title: {
                        text: 'Distribution of Sourcing Countries, Destination Countries, and Request Status'
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Sourcing Country',
                            data: chartData(table, 5), // Column index for sourcing country
                            center: ['20%', '50%'], // Center for the first pie
                            size: '90%' // Size for the first pie
                        },
                        {
                            name: 'Destination Country',
                            data: chartData(table, 6), // Column index for destination country
                            center: ['50%', '50%'], // Center for the second pie
                            size: '90%' // Size for the second pie
                        },
                        {
                            name: 'Request Status',
                            data: chartData(table, 13), // Column index for request status
                            center: ['80%', '50%'], // Center for the third pie
                            size: '90%' // Size for the third pie
                        }
                    ]
                });

                // Update charts on DataTable draw event
                table.on('draw', function () {
                    chart.series[0].setData(chartData(table, 5)); // Update sourcing country data
                    chart.series[1].setData(chartData(table, 6)); // Update destination country data
                    chart.series[2].setData(chartData(table, 13)); // Update request status data
                });

                // Function to fetch and process data for the chart
                function chartData(table, columnIndex) {
                    var counts = {};

                    // Count the number of entries for each status
                    table
                        .column(columnIndex, { search: 'applied' }) // Adjust the column index
                        .data()
                        .each(function (val) {
                            if (counts[val]) {
                                counts[val] += 1;
                            } else {
                                counts[val] = 1;
                            }
                        });

                    return Object.entries(counts).map(([name, y]) => ({ name, y }));
                }

                // Event listeners for filters
                $('#filter-form').on('submit', function(e) {
                    e.preventDefault();
                    table.draw(); // Redraw the table with new filter parameters
                });
            });
        </script>
    @endpush
@endsection
