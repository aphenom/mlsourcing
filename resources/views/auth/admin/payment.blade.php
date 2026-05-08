@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.payment_history') }}</h6>
                    <!-- Add filters here -->
                    <form id="filter-form" class="row">
                        <div class="form-group col-md-4">
                            <label for="statusFilter">{{ __('pages.status') }}:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">{{ __('pages.all') }}</option>
                                <option value="pending">{{ __('pages.pending') }}</option>
                                <option value="approved">{{ __('pages.approved') }}</option>
                                <option value="disapproved">{{ __('pages.disapproved') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sellerFilter">{{ __('pages.sellers_filter') }}:</label>
                            <select class="form-control" id="sellerID" name="sellerID">
                                <option value="">{{ __('pages.all') }}</option>
                                @foreach($sellers as $seller)
                                <option value="{{$seller->id}}">ID:{{$seller->id}} - {{$seller->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-primary" style="width:100%;">{{ __('pages.filter') }}</button>
                        </div>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                <div id="payment-chart-container" style="width:100%; height:400px;"></div>
                    <div id="table-responsive-id" class="table-responsive">
                        <table id="example" class="table table-bordered table-striped table-hover" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('pages.payment_id') }}</th>
                                    <th>{{ __('pages.created_at_col') }}</th>
                                    <th>{{ __('pages.request_no') }}</th>
                                    <th>{{ __('pages.seller_id') }}</th>
                                    <th>{{ __('pages.seller_name') }}</th>
                                    <th>{{ __('pages.amount') }}</th>
                                    <th>{{ __('pages.payment_option_col') }}</th>
                                    <th>{{ __('pages.screenshot_col') }}</th>
                                    <th>{{ __('pages.status') }}</th>
                                    <th>{{ __('pages.approve') }}</th>
                                    <th>{{ __('pages.disapprove') }}</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th>{{ __('pages.payment_id') }}</th>
                                    <th>{{ __('pages.created_at_col') }}</th>
                                    <th>{{ __('pages.request_no') }}</th>
                                    <th>{{ __('pages.seller_id') }}</th>
                                    <th>{{ __('pages.seller_name') }}</th>
                                    <th>{{ __('pages.amount') }}</th>
                                    <th>{{ __('pages.payment_option_col') }}</th>
                                    <th>{{ __('pages.screenshot_col') }}</th>
                                    <th>{{ __('pages.status') }}</th>
                                    <th>{{ __('pages.approve') }}</th>
                                    <th>{{ __('pages.disapprove') }}</th>
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
                url: '{{ route("admin.paymentsData") }}',
                type: 'GET',
                data: function(d) {
                    // Add custom search parameters from the form
                    d.status = $('#status').val();
                },
                error: function(xhr, error, thrown) {
                    console.log(xhr.responseText); // Log error to console
                    alert('An error occurred while fetching data.'); // Display alert message
                }
            },
            columns: [
                {data: 'payment_id', name: 'payment_id'},
                {data: 'created_at', name: 'created_at'},
                {data: 'request_no', name: 'request_no'},
                {data: 'seller_id', name: 'seller_id'},
                {data: 'seller_name', name: 'seller_name'},
                {data: 'amount', name: 'amount'},
                {data: 'payment_option', name: 'payment_option'},
                {data: 'screenshot', name: 'screenshot'},    
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        let className;
                        switch (data.toLowerCase()) {
                            case 'approved':
                                className = 'bg-gradient-success'; // Green for approved
                                break;
                            case 'disapproved':
                                className = 'bg-gradient-danger'; // Red for disapproved
                                break;
                            case 'pending':
                                className = 'bg-gradient-warning'; // Yellow for pending
                                break;
                            default:
                                className = ''; // Default class
                                break;
                        }
                        return `<p class="badge ${className}">${data}</p>`;
                    }
                },
                {
                    data: 'approve',
                    name: 'approve',
                    render: function(data, type, row) {
                        // Only display the Approve button if data is not '-'
                        if (data !== '-') {
                            return `<a type="button" class="btn btn-success" href="${data}">{{ __('pages.approve') }}</a>`;
                        }
                        return ''; // Return an empty string if data is '-'
                }},

                {
                    data: 'disapprove',
                    name: 'disapprove',
                    render: function(data, type, row) {
                        // Only display the Disapprove button if data is not '-'
                        if (data !== '-') {
                            return `<a type="button" class="btn btn-danger" href="${data}">{{ __('pages.disapprove') }}</a>`;
                        }
                        return ''; // Return an empty string if data is '-'
                }}
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

        // Function to fetch and process data for the chart
        function chartData(table, columnIndex) {
            var counts = {};
            // Count the number of entries for each status
            table.column(columnIndex, {
                search: 'applied'
            }).data().each(function(val) {
                if (counts[val]) {
                    counts[val] += 1;
                } else {
                    counts[val] = 1;
                }
            });

            return Object.entries(counts).map(([name, y]) => ({ name, y }));
        }

        // Create pie chart for payment statuses
        const paymentChart = Highcharts.chart('payment-chart-container', {
            chart: {
                type: 'pie',
                styledMode: true
            },
            title: {
                text: '{{ __("pages.chart_payment_dist") }}'
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
                name: 'Payment Status',
                data: chartData(table, 8), // Column index for status
                size: '100%'
            }]
        });

        // Update chart on DataTable draw event
        table.on('draw', function() {
            paymentChart.series[0].setData(chartData(table, 8)); // Update data
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
