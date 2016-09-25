@extends('layouts.app')

@extends('breadcrumbs')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <a href="{{ route('invoices.index') }}">Faktury</a>
    </li>
@endsection