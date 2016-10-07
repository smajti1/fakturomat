@extends('invoices.base')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Edytuj fakturę</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" action="{{ route('invoices.store') }}" method="POST">
        {{ csrf_field() }}

        <button type="submit" class="btn btn-primary">
            Zapisz zmiany
        </button>
    </form>
@endsection