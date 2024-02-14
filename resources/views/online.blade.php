@extends('base')
@section('page-title', 'Players')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/data-tables/css/datatables.min.css') }}">
@endsection
@section('contents')
    <!-- [ Main Content ] start -->
    <div class="row mb-3">
        <div class="col-md-8">
            <p class="lead">The real power of DataTables can be exploited through the use of the API that it presents. The
                DataTables API is designed to be simple.</p>
            <p class="f-w-500">Check out <a href="https://datatables.net/examples/api/add_row.html" target="_blank"
                    class="badge badge-secondary">Live demo</a></p>
        </div>
    </div>
    <div class="row">
        <!-- Add rows table start -->
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>current players</h5>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="add-row-table" class="table  table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Names</th>
                                    <th>Phone</th>
                                    <th>Amount</th>
                                    <th>TransCode</th>
                                    <th>Paybill Number</th>
                                    <th>Account</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add rows table end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('page-js')
    <!-- datatable Js -->
    <script src="{{ asset('assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var counter = 1;
            setInterval(() => {
                // [ Add Rows ]
                var t = $('#add-row-table').DataTable();
                $.get('presenters/online/' + item.id, function(data) {
                    console.log(data)
                    t.row.add([
                        'value now ' + counter + '.1',
                        'value now ' + counter + '.2',
                        'value now ' + counter + '.3',
                        'value now ' + counter + '.4',
                        'value now ' + counter + '.5',
                        'value now ' + counter + '.6',
                        'value now ' + counter + '.7'
                    ]).draw(false);
                })
                counter++;
            }, 5000);

            setTimeout(function() {})

        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
