@extends('base')
@section('page-title', 'Incomings')
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
                        <h6 class="m-b-20">INCOMINGS</h6>
                        <h2 class="text-left"><span id="totalAmount">{{ $incomings->count() }}</span></h2>
                    </div>
                </div>
            </div>
            <div> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddIncomingModal">Add
                    Incoming</button>

                <div id="AddIncomingModal" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="AddIncomingModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddIncomingModalTitle">Add Incoming</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('create_incoming') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputOrganization">CSP Name</label>
                                        <input type="text" class="form-control" id="inputOrganization"
                                            aria-describedby="orgname" placeholder="Enter CSP Name" name="csp">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">Type</label>
                                        <select class="mb-3 form-control" name="type">
                                            <option value="USSD">USSD</option>
                                            <option value="SHORTCODE">SHORTCODE</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">Shortcode</label>
                                        <input type="text" class="form-control" id="inputShortcode"
                                            aria-describedby="Shortcode" placeholder="Enter Shortcode" name="shortcode">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiPass">Api Pass</label>
                                        <input type="text" class="form-control" id="inputApiPass"
                                            aria-describedby="ApiPass" placeholder="Enter Api Pass" name="api_pass">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiUser">Api User</label>
                                        <input type="text" class="form-control" id="inputApiUser"
                                            aria-describedby="ApiUser" placeholder="Enter ApiUser" name="api_user">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiUrl">Api Url</label>
                                        <input type="text" class="form-control" id="inputApiUrl"
                                            aria-describedby="ApiUrl" placeholder="Enter Api Url" name="api_url">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputApiKey">Api Key</label>
                                        <input type="text" class="form-control" id="inputApiKey"
                                            aria-describedby="ApiKey" placeholder="Enter ApiKey" name="api_key">
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
                        <table id="incomings-table" class="table  table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>CSP</th>
                                    <th>SHORTCODE</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incomings as $incoming)
                                    <tr>

                                        <th>{{ $incoming->csp }}</th>
                                        <th>{{ $incoming->shortcode }}</th>
                                        <th>{{ $incoming->created_at }}</th>
                                    </tr>
                                @endforeach
                            <tfoot>
                                <th>Username</th>
                                <th>SMS SHORTCODE</th>
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
            var t = $('#incomings-table').DataTable({
                order: [
                    [0, 'desc']
                ],
            });
        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
