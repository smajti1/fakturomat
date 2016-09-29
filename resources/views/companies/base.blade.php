@extends('layouts.app')

@extends('breadcrumbs')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <a href="{{ route('company.index') }}">Twoje Firmy</a>
    </li>
@endsection