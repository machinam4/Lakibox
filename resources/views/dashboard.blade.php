@extends('base')
@section('page-title', 'Dashboard')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/data-tables/css/datatables.min.css') }}">
@endsection
@section('contents')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Add rows table start -->
        <div class="col-sm-12 col-md-12">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card statustic-card">
                            <div class="card-header borderless pb-0">
                                <h5>Total Paid In</h5>
                            </div>
                            <div class="card-body text-center">
                                <span class="d-block text-c-blue f-36">{{ $totalToday }}</span>
                                <p class="m-b-0">Total</p>
                                <div class="progress">
                                    <div class="progress-bar bg-c-blue" style="width:56%"></div>
                                </div>
                            </div>
                            <div class="card-footer bg-c-blue border-0">
                                <h6 class="text-white m-b-0">Stakes: 22</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card statustic-card">
                            <div class="card-header borderless pb-0">
                                <h5>Winnings</h5>
                            </div>
                            <div class="card-body text-center">
                                <span class="d-block text-c-green f-36">{{ $totalWinnings }}</span>
                                <p class="m-b-0">Total</p>
                                <div class="progress">
                                    <div class="progress-bar bg-c-green" style="width:85%"></div>
                                </div>
                            </div>
                            <div class="card-footer bg-c-green border-0">
                                <h6 class="text-white m-b-0">Winners: 85</h6>
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
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dailyTotals as $total)
                                    <tr>
                                        <td>{{ $total->TransTime }}</td>
                                        <td>{{ $total->TransAmount }}</td>
                                    </tr>
                                @endforeach
                            <tfoot>
                                <th>Date</th>
                                <th>Amount</th>
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
                    [2, 'desc']
                ],
            });
        })
    </script>
    {{-- <script src="{{asset('assets/js/pages/data-api-custom.js')}}"></script> --}}
@endsection
