<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Fakturomat') }}</title>
    <link href="{{ mix('/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>

<nav class="navbar navbar-light bg-faded rounded navbar-toggleable-md">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="{{ url('/') }}" class="webpage-link navbar-brand">Fakturomat</a>
        <span class="btn nohover pull-right d-none d-md-table-cell d-lg-table-cell d-xl-table-cell">
            @if (Illuminate\Support\Facades\Auth::check())
                {{ Illuminate\Support\Facades\Auth::user()->email }}
            @endif
        </span>
    </div>
</nav>

<div class="collapse" id="navbarToggleExternalContent">
    <div class="card" style="width: 18rem;">
        <ul class="navbar-nav mr-auto list-group list-group-flush">
            @if (Illuminate\Support\Facades\Auth::guest())
                <li class="nav-item list-group-item">
                    <a class="nav-link" href="{{ url('/login') }}">Zaloguj się</a>
                </li>
                <li class="nav-item list-group-item">
                    <a class="nav-link" href="{{ url('/register') }}">Rejestracja</a>
                </li>
            @else
                <li class="nav-item list-group-item">
                    <a class="nav-link" href="{{ route('buyer.index') }}">Kontrahenci</a>
                </li>
                <li class="nav-item list-group-item">
                    <a class="nav-link" href="{{ route('product.index') }}">Produkty</a>
                </li>
                <li class="nav-item list-group-item">
                    <a class="nav-link" href="{{ route('invoices.index') }}">Faktury</a>
                </li>
            @endif
            @if (!Illuminate\Support\Facades\Auth::guest())
                <li class="list-group-item">
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                        {{ csrf_field() }}
                        <button class="nav-link">
                            Wyloguj
                        </button>
                    </form>
                </li>
            @endif
        </ul>
    </div>
</div>


<div class="container">
    @if($flash = session('flash'))
        <div class="alert alert-{{ key($flash) }} alert-dismissible fade in" role="alert">
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
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
