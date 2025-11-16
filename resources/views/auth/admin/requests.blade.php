@extends('auth.theme.dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>All Requests</h6>
                        <!-- Add filters here -->
                        <div class="row">
                            <div class="col-md-12">
                                <form id="filter-form" class="row">

                                    <div class="form-group col-md-3">
                                        <label for="date_from">Date From:</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="date_to">Date To:</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="agent_id">Agent:</label>
                                        <select class="form-control" id="agent_id" name="agent_id">
                                            <option value="" selected disabled>Select an agent</option>
                                            <option value="">All Agents</option>

                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }}
                                                    ({{ $agent->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="sourcing_country_name">Sourcing Country:</label>
                                        <select class="form-control" id="sourcing_country_name"
                                            name="sourcing_country_name">
                                            <option value="" selected>Select a sourcing country</option>
                                            <option value="">All Countries</option>

                                            @foreach ($sourcingCountries as $country)
                                                <option value="{{ $country->country_name }}">{{ $country->country_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="destination_country_name">Destination Country:</label>
                                        <select class="form-control" id="destination_country_name"
                                            name="destination_country_name">
                                            <option value="" selected>Select a destination country</option>
                                            <option value="">All Countries</option>

                                            @foreach ($destinationCountries as $country)
                                                <option value="{{ $country->country_name }}">{{ $country->country_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="status">Status:</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">All</option>
                                            <option value="quoting">Quoting</option>
                                            <option value="quoted">Quoted</option>
                                            <!-- Add more status options as needed -->
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
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
                                        <th>Requested At</th>
                                        <th>Updated At</th>
                                        <th>Agent</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Country From</th>
                                        <th>Country To</th>
                                        <th>Request Status</th>
                                        <th>Payment Status</th>
                                        <th>See Request</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <!-- Data will be populated by DataTables -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Requested At</th>
                                        <th>Updated At</th>
                                        <th>Agent</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Country From</th>
                                        <th>Country To</th>
                                        <th>Request Status</th>
                                        <th>Payment Status</th>
                                        <th>See Request</th>
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
                    dom: 't<"bottom"ip>',

                    ajax: {
                        url: '{{ route('admin.requestsData') }}',
                        type: 'GET',
                        data: function(d) {
                            // Add custom search parameters from the form
                            d.date_from = $('#date_from').val();
                            d.date_to = $('#date_to').val();
                            d.agent_id = $('#agent_id').val();
                            d.sourcing_country_name = $('#sourcing_country_name').val();
                            d.destination_country_name = $('#destination_country_name').val();
                            d.status = $('#status').val();
                        },
                        error: function(xhr, error, thrown) {
                            console.log(xhr.responseText); // Log error to console
                            alert('An error occurred while fetching data.'); // Display alert message
                        }
                    },
                    columns: [
                        {data: 'created_at',name: 'created_at'},
                        {data: 'updated_at',name: 'updated_at'},
                        {data: 'agent',name: 'agent'},
                        {data: 'product_name',name: 'product_name'},
                        {data: 'quantity',name: 'quantity'},
                        {data: 'country_from',name: 'country_from'},
                        {data: 'country_to',name: 'country_to'},
                        {
                            data: 'request_status',
                            name: 'request_status',
                            render: function(data, type, row) {
                                let className = data.toLowerCase() === 'quoting' ?
                                    'bg-gradient-secondary' : 'bg-gradient-info';
                                return `<p class="badge ${className}">${data}</p>`;

                            }
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status',
                            render: function(data, type, row) {
                                let className;
                                switch (data.toLowerCase()) {
                                    case 'approved':
                                        className = 'bg-gradient-success';
                                        break;
                                    case 'disapproved':
                                        className = 'bg-gradient-danger';
                                        break;
                                    case 'pending':
                                        className = 'bg-gradient-warning';
                                        break;
                                    default:
                                        className = '';
                                        break;
                                }
                                return `<p class="badge ${className}">${data}</p>`;
                            }
                        },
                        {
                            data: 'view_url',
                            name: 'view_url',
                            render: function(data, type, row) {
                                return `<a class="badge btn bg-gradient-dark" href="${data}">View</a>`;
                            }
                        }
                    ]
                });

                // Function to fetch and process data for the chart
                function chartData(table, columnIndex) {
                    var counts = {};

                    // Count the number of entries for each status
                    table
                        .column(columnIndex, {
                            search: 'applied'
                        }) // Adjust the column index
                        .data()
                        .each(function(val) {
                            if (counts[val]) {
                                counts[val] += 1;
                            } else {
                                counts[val] = 1;
                            }
                        });

                    return Object.entries(counts).map(([name, y]) => ({
                        name,
                        y
                    }));
                }

                // Create chart
                const chart = Highcharts.chart('chart-container', {
                    chart: {
                        type: 'pie',
                        styledMode: true
                    },
                    title: {
                        text: 'Request and Payment Status Distribution'
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                            name: 'Request Status',
                            data: chartData(table, 7), // Column index for request status
                            center: ['25%', '50%'], // Center for the first pie
                            size: '100%' // Size for the first pie
                        },
                        {
                            name: 'Payment Status',
                            data: chartData(table, 8), // Column index for payment status
                            center: ['75%', '50%'], // Center for the second pie
                            size: '100%' // Size for the second pie
                        }
                    ]
                });

                // Update chart on DataTable draw event
                table.on('draw', function() {
                    chart.series[0].setData(chartData(table, 7)); // Update request status data
                    chart.series[1].setData(chartData(table, 8)); // Update payment status data
                });

                // Handle form submission
                $('#filter-form').on('submit', function(e) {
                    e.preventDefault();
                    table.draw(); // Redraw the table with new filter parameters
                });
            });
        </script>
    @endpush
@endsection
