<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Fakturomat') }}</title>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
</head>
<body>

    <div class="collapse" id="navbar-header" aria-expanded="false" style="height: 0">
        <div class="container-fluid bg-inverse p-a-1">
            <ul class="nav-list">
                @if (Auth::guest())
                    <li><a class="btn btn-outline-secondary" href="{{ url('/login') }}">Zaloguj się</a></li>
                    <li><a class="btn btn-outline-secondary" href="{{ url('/register') }}">Rejestracja</a></li>
                @else
                    <li><a class="btn btn-outline-secondary" href="{{ route('buyer.index') }}">Kontrachenci</a></li>
                    <li><a class="btn btn-outline-secondary" href="{{ route('product.index') }}">Produkty</a></li>
                    <li><a class="btn btn-outline-secondary" href="{{ route('invoices.index') }}">Faktury</a></li>
                    <li class="float-xs-right">
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-outline-secondary">
                                Wyloguj
                            </button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="navbar navbar-light bg-faded navbar-static-top">
        <a href="{{ url('/') }}" class="btn pull-left">Fakturomat</a>
        <button class="navbar-toggler collapsed pull-right" type="button" data-toggle="collapse" data-target="#navbar-header" aria-expanded="false"></button>
        <span class="btn nohover pull-right">
            @if (Auth::check())
                {{ Auth::user()->email }}
            @endif
        </span>
    </div>

    <div class="container">
        @if($flash = session('flash'))
            <div class="alert alert-{{ key($flash) }} alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {!! current($flash) !!}
            </div>
        @endif
        @yield('breadcrumbs')
        @yield('content')
    </div>

    <script src="{{ asset('/js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
