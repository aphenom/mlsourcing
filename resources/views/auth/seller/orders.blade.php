@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Orders</h6>
                    <!-- Add filters here -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-10">
                            <form id="filter-form" class="row align-items-center">
                                <div class="form-group col-md-2">
                                    <label for="date">Date:</label>
                                    <input type="date" class="form-control" id="date" name="date">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="country_from">Country From:</label>
                                    <input type="text" class="form-control" id="country_from" name="country_from">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="country_to">Country To:</label>
                                    <input type="text" class="form-control" id="country_to" name="country_to">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="status_product">Status:</label>
                                    <select class="form-control" id="status_product" name="status_product">
                                        <option value="">All</option>
                                        <option value="preparing">Preparing</option>
                                        <option value="in transit">In Transit</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                                    <th>Requested At</th>
                                    <th>Request NO</th>
                                    <th>Product Name</th>
                                    <th>Qte</th>
                                    <th>Total Price</th>
                                    <th>View Product</th>
                                    <th>Tracking Number</th>
                                    <th>Carrier</th>
                                    <th>Status Product</th>
                                    <th>Country From</th>
                                    <th>Country To</th>
                                    <th>Shipping Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th>Requested At</th>
                                    <th>Request NO</th>
                                    <th>Product Name</th>
                                    <th>Qte</th>
                                    <th>Total Price</th>
                                    <th>View Product</th>
                                    <th>Tracking Number</th>
                                    <th>Carrier</th>
                                    <th>Status Product</th>
                                    <th>Country From</th>
                                    <th>Country To</th>
                                    <th>Shipping Method</th>
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
                    data: 'view_product',
                    name: 'view_product',
                    render: function(data, type, row) {
                        return `<a class="badge btn bg-gradient-dark" href="${data}" target="_blank">Product Link</a>`;
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