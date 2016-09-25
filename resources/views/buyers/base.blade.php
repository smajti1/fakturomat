@extends('layouts.app')

@extends('breadcrumbs')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <a href="{{ route('buyer.index') }}">Kontrahent</a>
    </li>
@endsection