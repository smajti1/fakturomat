<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fakturomat') }}</title>

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>

    <div class="collapse" id="navbar-header" aria-expanded="false" style="height: 0px;">
        <div class="container-fluid bg-inverse p-a-1">
            <ul>
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
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
                @endif
            </ul>
        </div>
    </div>
    <div class="navbar navbar-light bg-faded navbar-static-top">
        <button class="navbar-toggler collapsed pull-right" type="button" data-toggle="collapse" data-target="#navbar-header" aria-expanded="false">
            ☰
        </button>
        <span class="pull-right">
            @if (Auth::check())
                {{ Auth::user()->email }}
            @endif
        </span>
    </div>

    @yield('content')

    <script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
