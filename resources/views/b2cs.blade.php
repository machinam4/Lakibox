@extends('base')
@section('page-title', 'B2C')
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
                        <h6 class="m-b-20">B2C</h6>
                        <h2 class="text-left"><span id="totalAmount">{{ $b2cs->count() }}</span></h2>
                    </div>
                </div>
            </div>
            <div> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddB2CModal">Add
                    B2C</button>

                <div id="AddB2CModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="AddB2CModalTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddB2CModalTitle">Add B2C Wallet</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('create_b2c') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputOrganization">Organization Name</label>
                                        <input type="text" class="form-control" id="inputOrganization"
                                            aria-describedby="orgname" placeholder="Enter Organization Name" name="orgname">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputShortcode">Shortcode</label>
                                        <input type="text" class="form-control" id="inputShortcode"
                                            aria-describedby="shortcode" placeholder="Enter Shortcode" name="shortcode">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputInititator">Inititator</label>
                                        <input type="text" class="form-control" id="inputInititator"
                                            aria-describedby="initiator" placeholder="Enter Inititator" name="initiator">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputSecurityCredential">Security Credential</label>
                                        <input type="text" class="form-control" id="inputSecurityCredential"
                                            aria-describedby="SecurityCredential" placeholder="Enter Security Credential"
                                            name="SecurityCredential">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputkey">Consumer Key</label>
                                        <input type="password" class="form-control" id="inputkey" aria-describedby="key"
                                            placeholder="Enter Consumer Key" name="key">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputsecret">Consumer Secret</label>
                                        <input type="password" class="form-control" id="inputsecret"
                                            aria-describedby="secret" placeholder="Enter Consumer Secret" name="secret">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPasskey">Passkey</label>
                                        <input type="passkey" class="form-control" id="inputPasskey"
                                            aria-describedby="passkey" placeholder="Enter Consumer Passkey" name="passkey">
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
                        <table id="b2cs-table" class="table  table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>B2C Name</th>
                                    <th>SHORTCODE</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($b2cs as $b2c)
                                    <tr>

                                        <th>{{ $b2c->name }}</th>
                                        <th>{{ $b2c->shortcode }}</th>
                                        <th>{{ $b2c->created_at }}</th>
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
            var t = $('#b2cs-table').DataTable({
                order: [
                    [0, 'desc']
                ],
            });
        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
