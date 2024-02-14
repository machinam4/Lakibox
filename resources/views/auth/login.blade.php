<!DOCTYPE html>
<html lang="en">


<head>

    <title>LAKIBOX - Login To Dashboard</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="LAKIBOX, by evoton.co.ke, is the ultimate Mpesa Dashboard." />
    <meta name="keywords" content="LAKIBOX, Evoton, evoton.co.ke, evoton systems, evoton softwares, admin templates,">
    <meta name="author" content="evoton.co.ke" />

    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('assets/images/Ridhishajamii-icon-logo.svg') }}" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/animation/css/animate.min.css') }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


</head>

<!-- [ signin-img-tabs ] start -->
<div class="blur-bg-images"></div>
<div class="auth-wrapper">
    <div class="auth-content container">
        <div class="card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="card-body">
                        <h2 class="mb-4">Welcome to <span class="text-c-blue">LAKIBOX</span></h2>
                        <p>Login to Lakibox to access your account details.</p>
                        <div class="toggle-block">
                            <ol class="position-relative carousel-indicators justify-content-start">
                                <li class="active"></li>

                            </ol>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ Route('login') }}" method="post">
                                @csrf
                                <div class="form-group mb-2">
                                    <label class="form-label">Enter Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="username"
                                        required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Enter Password</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Allow only max 14 character" required>
                                </div>
                                <button class="btn btn-primary mb-4">Login</button>
                            </form>
                            <p class="mb-2 text-muted">Forgot password? <a href="/" class="f-w-400">Reset</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-none d-md-block">
                    <img src="{{ asset('assets/images/auth/Lakiboxicon.webp') }}" alt=""
                        class="img-fluid bd-placeholder-img bd-placeholder-img-lg d-block w-100">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ signin-img-tabs ] end -->

<!-- Required Js -->
<script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script>
    $('.toggle-btn').on('click', function() {
        $('.toggle-block').toggle();
    })
</script>



</body>

</html>
