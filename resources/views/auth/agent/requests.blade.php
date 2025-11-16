@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Agent Requests</h6>
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
                                    <label for="status">Request Status:</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">All</option>
                                        <option value="quoting">Quoting</option>
                                        <option value="quoted">Quoted</option>
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
                    <div id="chart-container" style="width:100%; height:400px;"></div>
                    <div id="table-responsive-id" class="table-responsive">
                        <table id="example" class="table table-bordered table-striped table-hover" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>Request ID</th>
                                    <th>Requested At</th>
                                    <th>Updated At</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Country From</th>
                                    <th>Country To</th>
                                    <th>Request Status</th>
                                    <th>Payment Status</th>
                                    <th>See Request</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th>Request ID</th>
                                    <th>Requested At</th>
                                    <th>Updated At</th>
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
                url: '{{ route("agent.requestsData") }}',
                type: 'GET',
                data: function(d) {
                    d.date = $('#date').val();
                    d.country_from = $('#country_from').val();
                    d.country_to = $('#country_to').val();
                    d.status = $('#status').val();
                },
                error: function(xhr, error, thrown) {
                    console.log(xhr.responseText);
                    alert('An error occurred while fetching data.');
                }
            },
            columns: [
                { data: 'request_id', name: 'request_id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'product_name', name: 'product_name' },
                { data: 'quantity', name: 'quantity' },
                { data: 'country_from', name: 'country_from' },
                { data: 'country_to', name: 'country_to' },
                {
                    data: 'request_status',
                    name: 'request_status',
                    render: function(data, type, row) {
                        let className = data.toLowerCase() === 'quoting' ? 'bg-gradient-secondary' : 'bg-gradient-info';
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

        function chartData(table, columnIndex) {
            var counts = {};
            table.column(columnIndex, { search: 'applied' })
                .data()
                .each(function(val) {
                    counts[val] = (counts[val] || 0) + 1;
                });

            return Object.entries(counts).map(([name, y]) => ({ name, y }));
        }

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
            series: [
                {
                    name: 'Request Status',
                    data: chartData(table, 7),
                    center: ['25%', '50%'],
                    size: '100%'
                },
                {
                    name: 'Payment Status',
                    data: chartData(table, 8),
                    center: ['75%', '50%'],
                    size: '100%'
                }
            ]
        });

        table.on('draw', function() {
            chart.series[0].setData(chartData(table, 7));
            chart.series[1].setData(chartData(table, 8));
        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.draw();
        });
    });
</script>
@endpush
@endsection
