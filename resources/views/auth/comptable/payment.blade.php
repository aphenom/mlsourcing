@extends('auth.theme.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>{{ __('pages.payment_history') }}</h6>
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
                                <option value="{{ $seller->id }}">ID:{{ $seller->id }} - {{ $seller->name }}</option>
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
                            <tbody class="text-center"></tbody>
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
            dom: 't<"bottom"ip>',
            ajax: {
                url: '{{ route("comptable.paymentsData") }}',
                type: 'GET',
                data: function(d) {
                    d.status   = $('#status').val();
                    d.sellerID = $('#sellerID').val();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('An error occurred while fetching data.');
                }
            },
            columns: [
                {data: 'payment_id',     name: 'payment_id'},
                {data: 'created_at',     name: 'created_at'},
                {data: 'request_no',     name: 'request_no'},
                {data: 'seller_id',      name: 'seller_id'},
                {data: 'seller_name',    name: 'seller_name'},
                {data: 'amount',         name: 'amount'},
                {data: 'payment_option', name: 'payment_option'},
                {data: 'screenshot',     name: 'screenshot'},
                {
                    data: 'status',
                    name: 'status',
                    render: function(data) {
                        let cls;
                        switch (data.toLowerCase()) {
                            case 'approved':    cls = 'bg-gradient-success'; break;
                            case 'disapproved': cls = 'bg-gradient-danger';  break;
                            case 'pending':     cls = 'bg-gradient-warning'; break;
                            default:            cls = '';
                        }
                        return `<p class="badge ${cls}">${data}</p>`;
                    }
                },
                {
                    data: 'approve',
                    name: 'approve',
                    render: function(data) {
                        return data !== '-'
                            ? `<a type="button" class="btn btn-success" href="${data}">{{ __('pages.approve') }}</a>`
                            : '';
                    }
                },
                {
                    data: 'disapprove',
                    name: 'disapprove',
                    render: function(data) {
                        return data !== '-'
                            ? `<a type="button" class="btn btn-danger" href="${data}">{{ __('pages.disapprove') }}</a>`
                            : '';
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

        function chartData(table, colIdx) {
            var counts = {};
            table.column(colIdx, {search: 'applied'}).data().each(function(val) {
                counts[val] = (counts[val] || 0) + 1;
            });
            return Object.entries(counts).map(([name, y]) => ({ name, y }));
        }

        const paymentChart = Highcharts.chart('payment-chart-container', {
            chart: { type: 'pie', styledMode: true },
            title: { text: '{{ __("pages.chart_payment_dist") }}' },
            plotOptions: { pie: { dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f} %' } } },
            series: [{ name: 'Payment Status', data: chartData(table, 8), size: '100%' }]
        });

        table.on('draw', function() {
            paymentChart.series[0].setData(chartData(table, 8));
        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.draw();
        });
    });
</script>
@endpush

@endsection
