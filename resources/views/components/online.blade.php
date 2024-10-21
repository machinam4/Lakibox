@section('contents')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Add rows table start -->
        <div class="col-sm-12 col-md-12">
            <div class="col-md-6">
                <div class="card bg-c-blue order-card">
                    <div class="card-body">
                        <h6 class="m-b-20">Total Today</h6>
                        <h2 class="text-left"><span id="totalAmount">Loading...</span></h2>
                        <p class="m-b-0 text-right">Shortcode:
                            {{ env('SMS_SHORTCODE') }}</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>{{ auth()->user()->username }}</h5>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="add-row-table" class="table  table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>FirstName</th>
                                    <th>Phone</th>
                                    <th>BOX CHOICE</th>
                                    <th>Amount</th>
                                    <th>TransCode</th>
                                    <th>Paybill Number</th>
                                    {{-- <th>Account</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($players as $player)
                                    <tr>
                                        <th>{{ $player->TransTime }}</th>
                                        <th>{{ optional($player->player)->FirstName }}</th>
                                        <th>{{ $player->MSISDN }}</th>
                                        <th>{{ $player->BillRefNumber }}</th>
                                        <th>{{ $player->TransAmount }}</th>
                                        <th>{{ $player->TransID }}</th>
                                        <th>{{ $player->BusinessShortCode }}</th>
                                        {{-- <th>Account</th> --}}
                                    </tr>
                                @endforeach
                            </tbody>
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
                if (index > 1) {
                    // [ Add Rows ]
                    $.get('online/' + index, function(data) {
                        $("#totalAmount").html(data.totalAmount)
                        console.log(data);
                        data.new_players.forEach(player => {
                            t.row.add([
                                player.TransTime,
                                player.player.FirstName,
                                player.MSISDN,
                                player.BillRefNumber,
                                player.TransAmount,
                                player.TransID,
                                player.BusinessShortCode,
                                // data
                            ]).draw(false);
                            index = player.id;
                        });

                    })
                }

            }, 5000);

        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
