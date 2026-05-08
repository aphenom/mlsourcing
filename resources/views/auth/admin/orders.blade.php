@extends('auth.theme.dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>{{ __('pages.orders') }}</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <form id="filter-form" class="row">
                                    <div class="form-group col-md-2">
                                        <label for="dateFrom">{{ __('pages.date_from') }}:</label>
                                        <input type="date" class="form-control" id="dateFrom" name="date_from">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="dateTo">{{ __('pages.date_to') }}:</label>
                                        <input type="date" class="form-control" id="dateTo" name="date_to">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="agentFilter">{{ __('pages.agent_col') }}:</label>
                                        <select class="form-control" id="agentFilter" name="agent_id">
                                            <option value="">{{ __('pages.all') }}</option>
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="sourcingCountryFilter">{{ __('pages.sourcing_country_filter') }}:</label>
                                        <select class="form-control" id="sourcingCountryFilter" name="sourcing_country_name">
                                            <option value="">{{ __('pages.all') }}</option>
                                            @foreach ($sourcingCountries as $country)
                                                <option value="{{ $country->country_name }}">{{ $country->country_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="destinationCountryFilter">{{ __('pages.destination_country_filter') }}:</label>
                                        <select class="form-control" id="destinationCountryFilter" name="destination_country_name">
                                            <option value="">{{ __('pages.all') }}</option>
                                            @foreach ($destinationCountries as $country)
                                                <option value="{{ $country->country_name }}">{{ $country->country_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-primary" style="width:100%;">{{ __('pages.filter') }}</button>
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
                                        <th>{{ __('pages.date') }}</th>
                                        <th>{{ __('pages.agent_col') }}</th>
                                        <th>{{ __('pages.request_no') }}</th>
                                        <th>{{ __('pages.product_name_col') }}</th>
                                        <th>{{ __('pages.product_link') }}</th>
                                        <th>{{ __('pages.sourcing_country_filter') }}</th>
                                        <th>{{ __('pages.destination_country_filter') }}</th>
                                        <th>{{ __('pages.quantity_col') }}</th>
                                        <th>{{ __('pages.unit_price_col') }}</th>
                                        <th>{{ __('pages.total_price') }}</th>
                                        <th>{{ __('pages.weight_col') }}</th>
                                        <th>{{ __('pages.tracking_number_col') }}</th>
                                        <th>{{ __('pages.carrier') }}</th>
                                        <th>{{ __('pages.status_product_col') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center"></tbody>
                                <tfoot class="thead-light">
                                    <tr>
                                        <th>{{ __('pages.date') }}</th>
                                        <th>{{ __('pages.agent_col') }}</th>
                                        <th>{{ __('pages.request_no') }}</th>
                                        <th>{{ __('pages.product_name_col') }}</th>
                                        <th>{{ __('pages.product_link') }}</th>
                                        <th>{{ __('pages.sourcing_country_filter') }}</th>
                                        <th>{{ __('pages.destination_country_filter') }}</th>
                                        <th>{{ __('pages.quantity_col') }}</th>
                                        <th>{{ __('pages.unit_price_col') }}</th>
                                        <th>{{ __('pages.total_price') }}</th>
                                        <th>{{ __('pages.weight_col') }}</th>
                                        <th>{{ __('pages.tracking_number_col') }}</th>
                                        <th>{{ __('pages.carrier') }}</th>
                                        <th>{{ __('pages.status_product_col') }}</th>
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
                        url: "{{ route('admin.ordersData') }}",
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
                                if (row.product_url) {
                                    return `<a class="badge btn bg-gradient-dark" href="${row.product_url}" target="_blank">{{ __('pages.view_product') }}</a>`;
                                } else if (row.product_image) {
                                    return `<a href="${row.product_image}" target="_blank"><img src="${row.product_image}" style="max-height:48px;border-radius:4px;border:1px solid #dee2e6;"></a>`;
                                }
                                return '-';
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
                    language: {
                        processing:   "{{ __('pages.dt_processing') }}",
                        search:       "{{ __('pages.dt_search') }}:",
                        zeroRecords:  "{{ __('pages.dt_no_data') }}",
                        emptyTable:   "{{ __('pages.dt_no_data') }}",
                        info:         "{{ __('pages.dt_info') }}",
                        infoEmpty:    "{{ __('pages.dt_info_empty') }}",
                        infoFiltered: "{{ __('pages.dt_info_filtered') }}",
                        loadingRecords: "{{ __('pages.dt_loading') }}",
                        paginate:     { previous: "‹", next: "›" }
                    }
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
