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
                        <div class="col-md-12">
                            <form id="filter-form" class="row align-items-center">
                                <div class="form-group col-md-4">
                                    <label for="date">Date:</label>
                                    <input type="date" class="form-control" id="date" name="date">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="status">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">All</option>
                                        <option value="-">Not Yet</option>
                                        <option value="preparing">Preparing</option>
                                        <option value="in transit">In Transit</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
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
                                    <th>Date</th>
                                    <th>Request No</th>
                                    <th>Product Name</th>
                                    <th>Product Link</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Weight</th>
                                    <th>Tracking Number</th>
                                    <th>Carrier</th>
                                    <th>Status Product</th>
                                    <th>Dispatch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Request No</th>
                                    <th>Product Name</th>
                                    <th>Product Link</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Weight</th>
                                    <th>Tracking Number</th>
                                    <th>Carrier</th>
                                    <th>Status Product</th>
                                    <th>Dispatch</th>
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
                url: '{{ route("agent.ordersData") }}', // Adjust the route as needed
                type: 'GET',
                data: function(d) {
                    // Collect filter data
                    d.date = $('#date').val();
                    d.status = $('#status').val();
                },
                error: function(xhr, error, thrown) {
                    console.log(xhr.responseText);
                    alert('An error occurred while fetching data.');
                }
            },
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'request_no', name: 'request_no' },
                { data: 'product_name', name: 'product_name' },
                { 
                    data: 'product_url', 
                    name: 'product_url', 
                    render: function(data, type, row) {
                        return `<a href="${data}" target="_blank">View Product</a>`;
                    }
                },
                { data: 'qte', name: 'qte' },
                { data: 'unitPrice', name: 'unitPrice' },
                { data: 'totalPrice', name: 'totalPrice' },
                { data: 'weight', name: 'weight' },
                { data: 'trackingNumber', name: 'trackingNumber' },
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
                {
                    data: 'dispatch_button',
                    name: 'dispatch_button',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return data || '';
                    }
                }
            ]
        });

        // Function to gather and process data for chart
        function chartData(table, columnIndex) {
            var counts = {};
            table.column(columnIndex, { search: 'applied' }).data().each(function(val) {
                counts[val] = (counts[val] || 0) + 1;
            });

            return Object.entries(counts).map(([name, y]) => ({ name, y }));
        }

        // Create pie chart for order statuses
        const orderChart = Highcharts.chart('order-chart-container', {
            chart: { type: 'pie', styledMode: true },
            title: { text: 'Order Status Distribution' },
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
                data: chartData(table, 10), // Column index for statusProduct
                size: '100%'
            }]
        });

        // Update chart on DataTable draw event
        table.on('draw', function() {
            orderChart.series[0].setData(chartData(table, 10)); // Update data based on statusProduct
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
