@extends('layouts.app')

@section('content')
    <div id="welcome-page" class="row">
        <div class="content">
            <div class="text-xs-center title">
                Fakturomat
            </div>
            <ul>
                <li>Proste faktury</li>
                <li>Lista twoich firm</li>
                <li>Lista twoich kontrachentów</li>
                <li>Lista produktów</li>
                <li>Faktury w formie pdf</li>
            </ul>

            @if(Illuminate\Support\Facades\Auth::check())
                @include('panel-body')
            @else
                <div class="pull-right">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-color-636b6f">Zaloguj się</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-color-636b6f">Rejestracja</a>
                </div>
            @endif

        </div>
    </div>
@endsection
