@extends('layouts.app')

@extends('breadcrumbs')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <a href="{{ route('product.index') }}">Produkty</a>
    </li>
@endsection