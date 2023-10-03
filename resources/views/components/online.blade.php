@section('contents')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Add rows table start -->
        <div class="col-sm-12 col-md-12">
            <div class="col-md-6">
                <div class="card bg-c-blue order-card">
                    <div class="card-body">
                        <h6 class="m-b-20">Total This Session</h6>
                        <h2 class="text-left"><span id="totalAmount">Loading...</span></h2>
                        <p class="m-b-0 text-right">Started At
                            {{ auth()->user()->sessions->last()->created_at->format('H:i:s') }} <span
                                class="float-left">Shortcode:
                                {{ auth()->user()->sessions->last()->shortcode }}</span></p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>{{ auth()->user()->firstname . ' ' . auth()->user()->lastname }}</h5>
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
                                    {{-- <th>Account</th> --}}
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
            var index = {{ $last_index }};
            var t = $('#add-row-table').DataTable({
                order: [
                    [0, 'desc']
                ],
            });
            setInterval(() => {
                // [ Add Rows ]
                $.get('online/' + index, function(data) {
                    $("#totalAmount").html(data.totalAmount)
                    data.new_players.forEach(player => {
                        t.row.add([
                            player.TransTime,
                            player.FirstName,
                            player.MSISDN,
                            player.TransAmount,
                            player.TransID,
                            player.BusinessShortCode,
                            // data
                        ]).draw(false);
                        index = player.id;
                    });

                })

            }, 5000);

            setTimeout(function() {})

        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
