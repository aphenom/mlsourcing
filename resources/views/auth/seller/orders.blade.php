@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.orders') }}</h6>
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-10">
                            <form id="filter-form" class="row align-items-center">
                                <div class="form-group col-md-2">
                                    <label for="date">{{ __('pages.date') }}:</label>
                                    <input type="date" class="form-control" id="date" name="date">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="country_from">{{ __('pages.country_from') }}:</label>
                                    <input type="text" class="form-control" id="country_from" name="country_from">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="country_to">{{ __('pages.country_to') }}:</label>
                                    <input type="text" class="form-control" id="country_to" name="country_to">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="status_product">{{ __('pages.status') }}:</label>
                                    <select class="form-control" id="status_product" name="status_product">
                                        <option value="">{{ __('pages.all') }}</option>
                                        <option value="preparing">{{ __('pages.status_preparing') }}</option>
                                        <option value="in transit">{{ __('pages.status_in_transit') }}</option>
                                        <option value="shipped">{{ __('pages.status_shipped') }}</option>
                                        <option value="delivered">{{ __('pages.status_delivered') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">{{ __('pages.filter') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Add chart for order statuses -->
                    <div id="order-chart-container" style="width:100%; height:400px;"></div>
                    
                    <div id="table-responsive-id" class="table-responsive">
                        <table id="example" class="table table-bordered table-striped table-hover" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('pages.requested_at') }}</th>
                                    <th>{{ __('pages.request_no') }}</th>
                                    <th>{{ __('pages.product_name_col') }}</th>
                                    <th>{{ __('pages.qte') }}</th>
                                    <th>{{ __('pages.total_price') }}</th>
                                    <th>{{ __('pages.view_product') }}</th>
                                    <th>{{ __('pages.tracking_number_col') }}</th>
                                    <th>{{ __('pages.carrier') }}</th>
                                    <th>{{ __('pages.status_product_col') }}</th>
                                    <th>{{ __('pages.country_from') }}</th>
                                    <th>{{ __('pages.country_to') }}</th>
                                    <th>{{ __('pages.shipping_method_col') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th>{{ __('pages.requested_at') }}</th>
                                    <th>{{ __('pages.request_no') }}</th>
                                    <th>{{ __('pages.product_name_col') }}</th>
                                    <th>{{ __('pages.qte') }}</th>
                                    <th>{{ __('pages.total_price') }}</th>
                                    <th>{{ __('pages.view_product') }}</th>
                                    <th>{{ __('pages.tracking_number_col') }}</th>
                                    <th>{{ __('pages.carrier') }}</th>
                                    <th>{{ __('pages.status_product_col') }}</th>
                                    <th>{{ __('pages.country_from') }}</th>
                                    <th>{{ __('pages.country_to') }}</th>
                                    <th>{{ __('pages.shipping_method_col') }}</th>
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
            dom: 't<"bottom"ip>', // Remove search box, only show table, pagination, etc.

            ajax: {
                url: '{{ route("seller.ordersData") }}', // Adjust the route as needed
                type: 'GET',
                data: function(d) {
                    // Collect filter data
                    d.date = $('#date').val();
                    d.country_from = $('#country_from').val();
                    d.country_to = $('#country_to').val();
                    d.status_product = $('#status_product').val();
                },
                error: function(xhr, error, thrown) {
                    console.log(xhr.responseText);
                    alert('An error occurred while fetching data.');
                }
            },
            columns: [
                { data: 'requested_at', name: 'requested_at' },
                { data: 'request_no', name: 'request_no' },
                { data: 'product_name', name: 'product_name' },
                { data: 'quantity', name: 'quantity' },
                { data: 'total_price', name: 'total_price' },
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
                { data: 'tracking_number', name: 'tracking_number' },
                { data: 'carrier', name: 'carrier' },
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
                },
                { data: 'country_from', name: 'country_from' },
                { data: 'country_to', name: 'country_to' },
                { data: 'shipping_method', name: 'shipping_method' },
            ]
        });

        // Function to gather and process data for chart
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

        // Create pie chart for order statuses
        const orderChart = Highcharts.chart('order-chart-container', {
            chart: {
                type: 'pie',
                styledMode: true
            },
            title: {
                text: 'Order Status Distribution'
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
                name: 'Order Status',
                data: chartData(table, 8), // Column index for status_product
                size: '100%'
            }]
        });

        // Update chart on DataTable draw event
        table.on('draw', function() {
            orderChart.series[0].setData(chartData(table, 8)); // Update data based on order status
        });

        // Handle form submission for filtering
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.draw(); // Redraw the table with new filter parameters
        });
    });
</script>
@endpush
@endsection