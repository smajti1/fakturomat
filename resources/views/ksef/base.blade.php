@extends('layouts.app')

@extends('breadcrumbs')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <a href="{{ route('ksef.index') }}">Ksef</a>
    </li>
@endsection