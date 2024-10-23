@extends('base')
@section('page-title', 'Radios')
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
                        <h6 class="m-b-20">Radios</h6>
                        <h2 class="text-left"><span id="totalAmount">{{ $radios->count() }}</span></h2>
                    </div>
                </div>
            </div>
            <div> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddRadioModal">Add
                    Radio</button>

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
                                <form method="POST" action="{{ route('create_radio') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputUsername">Username</label>
                                        <input type="text" class="form-control" id="inputUsername"
                                            aria-describedby="username" placeholder="Enter username" name="username">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword">Password</label>
                                        <input type="password" class="form-control" id="inputPassword"
                                            placeholder="Password" name="password">
                                    </div>
                                    <div class="form-group">
                                        <select class="mb-3 form-control" name="role">
                                            <option value="24119">24119</option>
                                            <option value="23086">23086</option>
                                        </select>
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
                        <table id="radios-table" class="table  table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>SMS SHORTCODE</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($radios as $radio)
                                    <tr>

                                        <th>{{ $radio->username }}</th>
                                        <th>{{ $radio->role }}</th>
                                        <th>{{ $radio->created_at }}</th>
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
            var t = $('#radios-table').DataTable({
                order: [
                    [0, 'desc']
                ],
            });
        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
