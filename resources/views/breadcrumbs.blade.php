@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ url('/') }}">Strona główna</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('panel') }}">Panel</a>
        </li>
        @yield('breadcrumb')
    </ol>
@endsection