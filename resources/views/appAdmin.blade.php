<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <meta name="twitter:card" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:url" content="{{URL::to('/')}}"/>
    <meta property="og:type" content="proxy"/>
    <meta property="og:title" content=""/>
    <meta property="og:description" content=""/>



    <title></title>

    <link href="{{ asset('css/styles2.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="icon" type="image/x-icon" href="{{URL::to('/')}}/assets/img/lllogo3333.png"/>
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.27.0/feather.min.js" crossorigin="anonymous"></script>
    @yield('headers')
</head>
<body class="nav-fixed">
<nav class="topnav navbar navbar-expand shadow navbar-light bg-white" id="sidenavAccordion">
    <a class="navbar-brand" href="/home"></a>
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#"><i data-feather="menu"></i></button>
    {{--<form class="form-inline mr-auto d-none d-md-block">
        <div class="input-group input-group-joined input-group-solid">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-append">
                <div class="input-group-text"><i data-feather="search"></i></div>
            </div>
        </div>
    </form>--}}
    <ul class="navbar-nav align-items-center ml-auto ">

        <li class="nav-item dropdown no-caret " style="padding-top: 2px">


        </li>


            <li class="nav-item dropdown no-caret " style="padding-top: 2px">
                <a class="nav-link dropdown-toggle" id="navbarDropdownDocs" href="#" role="button"
                   data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false"><small>الاعدادات <i
                            class="fas fa-chevron-right dropdown-arrow"></i></small></a>
                <div class="dropdown-menu dropdown-menu-right animated--fade-in-up"
                     aria-labelledby="navbarDropdownDocs">


                    <a class="dropdown-item py-3" href="/profile"
                    >
                        <div class="icon-stack bg-primary-soft text-primary mr-4"><i
                                class="fas fa-user"></i></div>
                        <div>
                            <div class="small text-gray">معلومات الحساب</div>

                        </div>
                    </a
                    >

                    <div class="dropdown-divider m-0"></div>
                    <a class="dropdown-item py-3"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"

                    >
                        <div class="icon-stack bg-primary-soft text-primary mr-4"><i
                                class="fas fa-sign-out-alt"></i></div>
                        <div>
                            <div class="small text-gray">تسجيل الخروج</div>

                        </div>
                    </a>
                    <form id="logout-form" action="" method="POST"
                          style="display: none;">
                        @csrf
                    </form>

                </div>
            </li>

    </ul>
</nav>
<div id="layoutSidenav">
    @include('navbar_admin')
    <div id="layoutSidenav_content">

        @yield('content')

        <footer class="footer mt-auto footer-light">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 small">Copyright &copy; .com 2020</div>
                    <div class="col-md-6 text-md-right small">
                        <a href="#!">Privacy Policy</a>
                        &middot;
                        <a href="#!">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>


    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts2.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/demo/datatables-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/date-range-picker-demo.js"></script>
</body>
</html>



















{{--
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Setting::where('key', '=', 'site_name')->first()->value }}
        - {{ \App\Setting::where('key', '=', 'desc')->first()->value }}</title>

    <!-- Scripts -->


--}}
{{--    <script src="{{ asset('js/app.js') }}" defer></script> --}}{{--

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <!-- Styles -->
--}}
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}{{--


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->


    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="{{ \App\Setting::where('key', '=', 'desc')->first()->value }}"/>
    <meta name="author" content="{{ \App\Setting::where('key', '=', 'site_name')->first()->value }}"/>
    <meta name="twitter:card" content="{{ \App\Setting::where('key', '=', 'desc')->first()->value }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:url" content="{{URL::to('/')}}"/>
    <meta property="og:type" content="proxy"/>
    <meta property="og:title" content="{{ \App\Setting::where('key', '=', 'site_name')->first()->value }}"/>
    <meta property="og:description" content="{{ \App\Setting::where('key', '=', 'desc')->first()->value }}"/>
    <meta property="og:image" content="{{URL::to('/')}}/assets/img/lllogo3333.png"/>

    <meta name="twitter:card" content="{{ \App\Setting::where('key', '=', 'desc')->first()->value }}"/>
    <meta name="twitter:site" content="{{URL::to('/')}}"/>
    <meta name="twitter:title" content="{{ \App\Setting::where('key', '=', 'site_name')->first()->value }}"/>
    <meta name="twitter:description" content="{{ \App\Setting::where('key', '=', 'desc')->first()->value }}"/>
    <meta name="twitter:image" content="{{URL::to('/')}}/assets/img/lllogo3333.png"/>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet"/>
    <link rel="icon" type="image/x-icon" href="{{URL::to('/')}}/assets/img/lllogo3333.png"/>
    <script data-search-pseudo-elements defer
            src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"
            crossorigin="anonymous"></script>


    @yield('headers')
</head>
<body>
<div id="layoutDefault">
    <div id="layoutDefault_content">
        <main>


            @if (Auth::check())
                @include('layouts.navbar_user')
                <div class="container" style="margin-top: 110px">
                    @yield('content')
                </div>
                @else
                @include('layouts.navbar_guest')
                @yield('content')
                @endif

        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>

@yield('footers')
</body>
</html>
--}}
