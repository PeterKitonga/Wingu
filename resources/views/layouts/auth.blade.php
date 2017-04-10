<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <!-- Styles -->
        <link href="{!! asset('css/app.css') !!}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Maven+Pro" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
        @stack('styles')
        <link href="{!! asset('css/custom.css') !!}" rel="stylesheet">

        <!-- Scripts -->
        <script>
            window.Laravel = '{!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!}'
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="wrapper">
                    <div class="side-bar">
                        <ul>
                            <li class="menu-head">
                                Wingu
                                <a class="push_menu">
                                    <i class="fa fa-bars pull-right" aria-hidden="true"></i>
                                </a>
                            </li>
                            <div class="menu">
                                <li>
                                    <a href="{!! route('home') !!}" class="{{ Request::is('home*') ? 'active' : '' }}">Dashboard
                                        <i class="fa fa-home pull-right" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{!! route('forecasts.index') !!}" class="{{ Request::is('forecasts*') ? 'active' : '' }}">Forecasts
                                        <i class="fa fa-cloud pull-right"></i>
                                    </a>
                                </li>
                            </div>

                        </ul>
                    </div>

                    <div class="content">
                        <nav class="navbar navbar-transparent navbar-static-top">
                            <div class="container-fluid">
                                <div class="navbar-header">

                                    <!-- Collapsed Hamburger -->
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                                        <span class="sr-only">Toggle navigation</span>
                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                    </button>

                                    <!-- Branding Image -->
                                    <a class="navbar-brand" href="{{ URL::previous() }}">
                                        {{ ucwords(str_replace("/", " > ", Request::path())) }}
                                    </a>
                                </div>

                                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                                    <!-- Left Side Of Navbar -->
                                    <ul class="nav navbar-nav">
                                        &nbsp;
                                    </ul>

                                    <!-- Right Side Of Navbar -->
                                    <ul class="nav navbar-nav navbar-right">
                                        <!-- Authentication Links -->
                                        @if (Auth::guest())
                                            <li><a href="{{ url('/login') }}">Login</a></li>
                                            <li><a href="{{ url('/register') }}">Register</a></li>
                                        @else
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                                    {{ Auth::user()->name }} <span class="caret"></span>
                                                </a>

                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="{{ url('/logout') }}"
                                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                            Logout
                                                        </a>

                                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                            {{ csrf_field() }}
                                                        </form>
                                                    </li>
                                                </ul>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </nav>
                        <div class="col-md-12">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="{!! asset('js/app.js') !!}"></script>
        @stack('scripts')
    </body>
</html>
