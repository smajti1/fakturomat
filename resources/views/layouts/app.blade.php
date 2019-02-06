<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Fakturomat') }}</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>

    <nav class="navbar navbar-light bg-faded rounded navbar-toggleable-md">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar-header" aria-controls="navbar-header" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="{{ url('/') }}" class="webpage-link navbar-brand">Fakturomat</a>
        <div class="collapse navbar-collapse" id="navbar-header" aria-expanded="false">
            <ul class="navbar-nav mr-auto">
                @if (Auth::guest())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login') }}">Zaloguj się</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/register') }}">Rejestracja</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('buyer.index') }}">Kontrachenci</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('product.index') }}">Produkty</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('invoices.index') }}">Faktury</a>
                    </li>
                @endif
            </ul>
            @if (!Auth::guest())
                <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                    {{ csrf_field() }}
                    <button class="btn btn-outline-secondary btn-color-636b6f nav-link pull-right">
                        Wyloguj
                    </button>
                </form>
            @endif
        </div>
        <span class="btn nohover pull-right d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">
            @if (Auth::check())
                {{ Auth::user()->email }}
            @endif
        </span>
    </nav>

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

    <script src="{{ mix('/js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
