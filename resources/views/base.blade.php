<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('page-title') | Laki Box LTD</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="@yield('page-description')" />
    <meta name="keywords" content="@yield('page-contents')">
    <meta name="author" content="machina:evoton.co.ke" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('assets/images/Ridhishajamii-icon-logo.svg') }}" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/animation/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/modal-window-effects/css/md-modal.css') }}">


    @yield('page-css')
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar menupos-fixed">
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="{{ Route('dashboard') }}" class="b-brand">
                    <div class="b-bg">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <span class="b-title">LAKIBOX</span>
                    {{-- <img src="{{ asset('assets/images/ridhishajamii-word-logo.svg') }}" alt=""
                        class="logo images">
                    <img src="{{ asset('assets/images/ridhishajamii-icon-logo.svg') }}" alt=""
                        class="logo-thumb images"> --}}
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigation</label>
                    </li>
                    <li data-username="dashboard lakibox evoton emartmobile" class="nav-item">
                        <a href="{{ Route('dashboard') }}" class="nav-link"><span class="pcoded-micon"><i
                                    class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li data-username="players lakibox evoton emartmobile" class="nav-item">
                        <a href="{{ Route('players') }}" class="nav-link"><span class="pcoded-micon"><i
                                    class="feather icon-home"></i></span><span class="pcoded-mtext">Players</span></a>
                    </li>
                    <li data-username="winners lakibox evoton emartmobile" class="nav-item">
                        <a href="{{ Route('winners') }}" class="nav-link"><span class="pcoded-micon"><i
                                    class="feather icon-home"></i></span><span class="pcoded-mtext">Winners</span></a>
                    </li>
                    </li>
                    <li class="nav-item pcoded-menu-caption">
                        <label>Support</label>
                    </li>
                    <li data-username="Need Support" class="nav-item"><a href="https://evoton.co.ke/contact"
                            class="nav-link" target="_blank"><span class="pcoded-micon"><i
                                    class="feather icon-help-circle"></i></span><span class="pcoded-mtext">Need
                                support ?</span></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
            <a href="index.html" class="b-brand">
                <!-- <div class="b-bg">
                    <i class="fas fa-bolt"></i>
                </div>
                <span class="b-title">Dasho</span> -->
                <img src="{{ asset('assets/images/ridhishajamii-word-logo.svg') }}" alt="" class="logo images">
                <img src="{{ asset('assets/images/ridhishajamii-icon-logo.svg') }}" alt=""
                    class="logo-thumb images">
            </a>
        </div>
        <a class="mobile-menu" id="mobile-header" href="#!">
            <i class="feather icon-more-horizontal"></i>
        </a>
        <div class="collapse navbar-collapse">
            <a href="#!" class="mob-toggler"></a>

            <ul class="navbar-nav ml-auto">
                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon feather icon-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                <span>{{ auth()->user()->username }}</span>

                            </div>
                            <ul class="pro-body">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="feather icon-log-out"></i>
                                        Logout</button>
                                </form>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <!-- [ Header ] end -->


    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ breadcrumb ] start -->
                            <div class="page-header">
                                <div class="page-block">
                                    <div class="row align-items-center">
                                        <div class="col-md-12">
                                            <div class="page-header-title">
                                                <h5 class="m-b-10">@yield('page-title')</h5>
                                                @if (session('message'))
                                                    <div class="alert alert-success alert-dismissible fade show"
                                                        role="alert">
                                                        <strong>{{ session('message') }}</strong>
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close"><span
                                                                aria-hidden="true">&times;</span></button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [ breadcrumb ] end -->
                            <!-- [ Main Content ] start -->
                            @yield('contents')
                            <!-- [ Main Content ] end -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/modal-window-effects/js/classie.js') }}"></script>
    <script src="{{ asset('assets/plugins/modal-window-effects/js/modalEffects.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/js/sweetalert.min.js') }}"></script>

    @yield('page-js')
</body>

</html>
