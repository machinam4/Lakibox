@extends('base')
@section('page-title', 'Winners')
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
                        <h6 class="m-b-20">Total Winnings</h6>
                        <h2 class="text-left"><span id="totalAmount">{{ $totalWinnings }}</span></h2>
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
                                    <th>Date</th>
                                    <th>TransactionID</th>
                                    <th>Names</th>
                                    <th>Amount</th>
                                    <th>SMS SHORTCODE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($winners as $winner)
                                    <tr>
                                        <th>{{ $winner->created_at }}</th>
                                        <th>{{ $winner->transaction_id }}</th>
                                        <th>{{ $winner->receiver_party_public_name }}</th>
                                        <th>{{ $winner->transaction_amount }}</th>
                                        <th>{{ $winner->platform->platform }}</th>
                                    </tr>
                                @endforeach
                            <tfoot>
                                <th>Date</th>
                                <th>TransactionID</th>
                                <th>Names</th>
                                <th>Amount</th>
                                <th>SMS SHORTCODE</th>
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
            var t = $('#add-row-table').DataTable({
                order: [
                    [0, 'desc']
                ],
            });
        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
