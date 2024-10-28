@section('contents')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Add rows table start -->
        <div class="col-sm-12 col-md-12">
            {{-- <div class="col-md-6">
                <div class="card bg-c-blue order-card">
                    <div class="card-body">
                        <h6 class="m-b-20">Total Today</h6>
                        <h2 class="text-left"><span id="toalAmount">Loading...</span></h2>
                        <p class="m-b-0 text-right">Winnings:
                            {{ $totalWinnings }}</p>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="card statustic-card">
                        <div class="card-header bg-c-blue border-0">
                            <h5 class="text-white m-b-0">Total Paid In</h5>
                        </div>
                        <div class="card-body text-center">
                            <span class="d-block text-c-blue f-36" id="totalAmount">Loading...</span>
                            <p class="m-b-0">Total</p>
                            <div class="progress">
                                <div class="progress-bar bg-c-blue" style="width:56%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card statustic-card">
                        <div class="card-header bg-c-purple border-0">
                            <h5 class="text-white m-b-0">Winnings</h5>
                        </div>
                        <div class="card-body text-center">
                            <span class="d-block text-c-purple f-36"
                                id="totalWinnings">{{ number_format($totalWinnings) }}</span>
                            <p class="m-b-0">Total</p>
                            <div class="progress">
                                <div class="progress-bar bg-c-purple" style="width:85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card statustic-card">
                        <div class="card-header bg-c-green border-0">
                            <h5 class="text-white m-b-0">Yield</h5>
                        </div>
                        <div class="card-body text-center">
                            <span class="d-block text-c-green f-36" id="totalYield">Loading...</span>
                            <p class="m-b-0">Total</p>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:42%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddRadioModal">Filter
                    Records</button>

                <div id="AddRadioModal" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="AddRadioModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddRadioModalTitle">Modal Title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('filter') }}">
                                    @csrf
                                    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer')
                                        <div class="form-group">
                                            <select class="mb-3 form-control" name="role">
                                                <option value="24119">24119</option>
                                                <option value="23086">23086</option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="inputUsername">From Date*</label>
                                        <input type="datetime-local" id="radio-vertical" class="form-control"
                                            name="from_date" placeholder="From Date*"
                                            max="{{ now()->format('Y-m-d\TH:i:s') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword">To Date*</label>
                                        <input type="datetime-local" id="paybill-vertical" class="form-control"
                                            name="to_date" placeholder="To Date*" min="{{ now()->format('Y-m-d\TH:i:s') }}">
                                    </div>
                                    <button type="reset" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
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
                                    <th>SMS SHORTCODE</th>
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
                                        <th>{{ $player->SmsShortcode }}</th>
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
                        $("#totalAmount").html(data.totalAmount.toLocaleString())
                        $("#totalWinnings").html(data.totalWinnings.toLocaleString())
                        $("#totalYield").html(data.totalAmount - data.totalWinnings)
                        console.log(data);
                        data.new_players.forEach(player => {
                            t.row.add([
                                player.TransTime,
                                player.player.FirstName,
                                player.MSISDN,
                                player.BillRefNumber,
                                player.TransAmount,
                                player.TransID,
                                player.SmsShortcode,
                                // data
                            ]).draw(false);
                            index = player.id;
                        });

                    })
                }

            }, 5000);


        })
    </script>
    <script>
        // Add event listener to "From Date" input
        document.getElementById('radio-vertical').addEventListener('change', function() {
            // Get the selected "From Date" value
            var fromDateValue = this.value;

            // Set the "To Date" input's min attribute to the selected "From Date" value
            document.getElementById('paybill-vertical').min = fromDateValue;
        });
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
