{{-- @extends('base')
@section('page-title', 'Session Busy') --}}
@section('contents')
    <div class="auth-wrapper offline">
        @if ($sessions->count() != 0)
            @foreach ($sessions as $session)
                <div class="text-center">
                    <h1 class="mb-4">{{ $session->presenter->firstname . ' ' . $session->presenter->lastname }} IS LIVE
                        CURRENTLY
                    </h1>
                    <h5 class="text-muted mb-4">Kindly wait for session to end or Request session end</h5>
                </div>
            @endforeach
        @else
            <div class="text-center">
                <h1 class="mb-4">START SESSION</h1>
                <h5 class="text-muted mb-4">Click start session to view players</h5>
                <button class="btn btn-success mb-4 md-trigger" data-modal="modal-golive"><i
                        class="feather icon-clock"></i>Start
                    Session</button>
            </div>
        @endif

    </div>
@endsection
