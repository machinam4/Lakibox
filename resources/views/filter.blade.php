@extends('base')
@section('page-title', 'Filters')
@section('page-css')
@section('contents')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Project statustic start -->

        <div class="card">

        </div>
        <div class="col-xl-12">
            <div class="card proj-progress-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-2">
                            <a href="{{ Route('players') }}" class="btn btn-primary block">
                                BACK
                            </a>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-2">
                            <h6>From Date</h6>
                            <h5 class="m-b-2">{{ $fromDate }}</h5>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-2">
                            <h6>From Date</h6>
                            <h5 class="m-b-2">{{ $toDate }}</h5>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-2">
                            <h6>Duration</h6>
                            @php
                                $diffInMinutes = $fromDate->diffInMinutes($toDate);
                                $hours = floor($diffInMinutes / 60);
                                $minutes = $diffInMinutes % 60;
                            @endphp
                            <h5 class="m-b-2">{{ $hours }} hours {{ $minutes }} mins</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card statustic-card">
                        <div class="card-header bg-c-blue border-0">
                            <h5 class="text-white m-b-0">Total Paid In</h5>
                        </div>
                        <div class="card-body text-center">
                            <span class="d-block text-c-blue f-36">{{ number_format($totalToday) }}</span>
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
                            <span class="d-block text-c-purple f-36">{{ number_format($totalWinnings) }}</span>
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
                            <span class="d-block text-c-green f-36">{{ number_format($totalToday - $totalWinnings) }}</span>
                            <p class="m-b-0">Total</p>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:42%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add rows table start -->
        <div class="col-sm-12 col-md-12">
            {{-- <div class="col-md-6">
                <div class="card bg-c-blue order-card">
                    <div class="card-body">
                        <h6 class="m-b-20">Total Today</h6>
                        <h2 class="text-left"><span id="totalAmount">Loading...</span></h2>
                        <p class="m-b-0 text-right">Winnings:
                            {{ $totalWinnings }}</p>
                    </div>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-header">
                    <h5>{{ auth()->user()->username }}</h5>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="add-row-table" class="table table-striped table-bordered nowrap">
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
    {{-- <script>
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
                                player.SmsShortcode,
                                // data
                            ]).draw(false);
                            index = player.id;
                        });

                    })
                }

            }, 5000);

        })
    </script> --}}
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
