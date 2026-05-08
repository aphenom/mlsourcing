@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.payment_history') }}</h6>
                    <!-- Add filters here -->
                    <form id="filter-form" class="row align-items-center">
                        <div class="form-group col-md-3">
                            <label for="start_date">{{ __('pages.start_date') }}:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="end_date">{{ __('pages.end_date') }}:</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="status">{{ __('pages.status') }}:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">{{ __('pages.all') }}</option>
                                <option value="approved">{{ __('pages.approved') }}</option>
                                <option value="pending">{{ __('pages.pending') }}</option>
                                <option value="disapproved">{{ __('pages.disapproved') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 mt-3">
                            <button type="submit" class="btn btn-primary w-100">{{ __('pages.filter') }}</button>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Add chart for payment statuses -->
                    <div id="payment-chart-container" style="width:100%; height:400px;"></div>
                    <div id="table-responsive-id" class="table-responsive">
                        <table id="example" class="table table-bordered table-striped table-hover" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('pages.payment_id') }}</th>
                                    <th>{{ __('pages.date') }}</th>
                                    <th>{{ __('pages.request_no') }}</th>
                                    <th>{{ __('pages.product_col') }}</th>
                                    <th>{{ __('pages.amount') }}</th>
                                    <th>{{ __('pages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th>{{ __('pages.payment_id') }}</th>
                                    <th>{{ __('pages.date') }}</th>
                                    <th>{{ __('pages.request_no') }}</th>
                                    <th>{{ __('pages.product_col') }}</th>
                                    <th>{{ __('pages.amount') }}</th>
                                    <th>{{ __('pages.status') }}</th>
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
                url: '{{ route("seller.filteredPayments") }}',
                type: 'GET',
                data: function(d) {
                    // Add custom search parameters from the form
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.status = $('#status').val();
                },
                error: function(xhr, error, thrown) {
                    console.log(xhr.responseText); // Log error to console
                    alert('An error occurred while fetching data.'); // Display alert message
                }
            },
            columns: [
                { data: 'payment_id', name: 'payment_id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'request_no', name: 'request_no' },
                { data: 'product_name', name: 'product_name' },
                { data: 'amount', name: 'amount' },
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
                text: 'Payment Status Distribution'
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
                data: chartData(table, 3), // Column index for status
                size: '100%'
            }]
        });

        // Update chart on DataTable draw event
        table.on('draw', function() {
            paymentChart.series[0].setData(chartData(table, 3)); // Update data
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