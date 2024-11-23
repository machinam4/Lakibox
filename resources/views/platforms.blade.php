@extends('base')
@section('page-title', 'Platforms')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/data-tables/css/datatables.min.css') }}">
@endsection
@section('contents')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Add rows table start -->
        <div class="col-sm-12 col-md-12">
            <div class="col-md-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-body">
                        <h6 class="m-b-20">Platforms</h6>
                        <h2 class="text-left"><span id="totalAmount">{{ $platforms->count() }}</span></h2>
                    </div>
                </div>
            </div>
            <div> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddplatformModal">Add
                    Platform</button>

                <div id="AddplatformModal" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="AddplatformModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddplatformModalTitle">Add Platform</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('create_platform') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputOrganization">Platform Name</label>
                                        <input type="text" class="form-control" id="inputOrganization"
                                            aria-describedby="orgname" placeholder="Enter platform Name" name="platform">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">Incoming Shortcode</label>
                                        <select class="mb-3 form-control" name="mobile_incoming">
                                            @foreach ($incomings as $incoming)
                                                <option value="{{ $incoming->id }}">{{ $incoming->shortcode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">Outgoing Shortcode</label>
                                        <select class="mb-3 form-control" name="mobile_outgoing">
                                            @foreach ($senders as $sender)
                                                <option value="{{ $sender->id }}">{{ $sender->shortcode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">Paybill Wallet</label>
                                        <select class="mb-3 form-control" name="paybill_wallet">
                                            @foreach ($paybills as $paybill)
                                                <option value="{{ $paybill->id }}">{{ $paybill->shortcode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">B2C Wallet</label>
                                        <select class="mb-3 form-control" name="b2c_wallet">
                                            @foreach ($b2cs as $b2c)
                                                <option value="{{ $b2c->id }}">{{ $b2c->shortcode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">Bet minimum</label>
                                        <input type="number" class="form-control" id="inputShortcode"
                                            aria-describedby="Shortcode" placeholder="Enter Bet Minimum" name="bet_minimum">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiPass">Bet Maximum</label>
                                        <input type="number" class="form-control" id="inputApiPass"
                                            aria-describedby="ApiPass" placeholder="Enter Bet Maximum" name="bet_maximum">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiUser">Win Ratio</label>
                                        <input type="number" class="form-control" id="inputApiUser"
                                            aria-describedby="ApiUser" placeholder="Enter Win Ratio" name="win_ratio">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiUrl">Win Maximum</label>
                                        <input type="number" class="form-control" id="inputApiUrl"
                                            aria-describedby="ApiUrl" placeholder="Enter Win Maximum" name="win_maximum">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiKey">Win Minimum</label>
                                        <input type="number" class="form-control" id="inputApiKey"
                                            aria-describedby="ApiKey" placeholder="Enter Win Minimum" name="win_minimum">
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
                        <table id="platforms-table" class="table  table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Platform</th>
                                    <th>Incoming</th>
                                    <th>Outgoing</th>
                                    <th>paybill</th>
                                    <th>b2c</th>
                                    <th>bet_minimum</th>
                                    <th>bet_maximum</th>
                                    <th>win_ratio</th>
                                    <th>win_maximum</th>
                                    <th>win_minimum</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($platforms as $platform)
                                    <tr>

                                        <th>{{ $platform->platform }}</th>
                                        <th>{{ $platform->incoming->shortcode }}</th>
                                        <th>{{ $platform->outgoing->shortcode }}</th>
                                        <th>{{ $platform->paybill->shortcode }}</th>
                                        <th>{{ $platform->b2c->shortcode }}</th>
                                        <th>{{ $platform->bet_minimum }}</th>
                                        <th>{{ $platform->bet_maximum }}</th>
                                        <th>{{ $platform->win_ratio }}</th>
                                        <th>{{ $platform->win_maximum }}</th>
                                        <th>{{ $platform->win_minimum }}</th>
                                        <th>{{ $platform->created_at }}</th>
                                    </tr>
                                @endforeach
                            <tfoot>
                                <th>Platform</th>
                                <th>Incoming</th>
                                <th>Outgoing</th>
                                <th>paybill</th>
                                <th>b2c</th>
                                <th>bet_minimum</th>
                                <th>bet_maximum</th>
                                <th>win_ratio</th>
                                <th>win_maximum</th>
                                <th>win_minimum</th>
                                <th>Created At</th>
                            </tfoot>
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
            var t = $('#platforms-table').DataTable({
                order: [
                    [0, 'desc']
                ],
            });
        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
