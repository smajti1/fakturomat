@extends('layouts.app')

@extends('breadcrumbs')

@section('breadcrumb')
    <li class="breadcrumb-item active">
        <a href="{{ route('import.index') }}">Import</a>
    </li>
@endsection