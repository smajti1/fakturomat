@extends('layouts.app')

@section('content')
    <div class="wizard">
        <ol id="crumbs">
            @foreach($wizard->all() as $key => $_step)
                <li class="{{ $step->index == $_step->index ? 'current' : ($step->index > $_step->index ? 'prev' : 'next') }}">
                    @if($step->index > $_step->index)
                        <a href="{{ route('wizard.account', [$_step::$slug]) }}">{{ $_step::$label }}</a>
                    @else
                        {{ $_step::$label }}
                    @endif
                </li>
            @endforeach
        </ol>
        <form action="{{ route('wizard.account.post', [$step::$slug]) }}" method="POST">
            {{ csrf_field() }}
            @include($step::$view, compact('step', 'errors') + ['wizardData' => ($wizard->data()[$step::$slug] ?? [])])

            <div class="text-center">
                @if ($wizard->hasPrev())
                    <a href="{{ route('wizard.account', ['step' => $wizard->prevSlug()]) }}" class="float-left btn">Powrót</a>
                @else
                    <a href="#" class="float-left btn">Powrót</a>
                @endif

                <span>Krok {{ $step->number }}/{{ $wizard->limit() }}</span>

                @if ($wizard->hasNext())
                    <button type="submit" class="float-right btn btn-primary">Dalej</button>
                @else
                    <button type="submit" class="float-right btn btn-primary">Koniec</button>
                @endif
            </div>
        </form>
    </div>
@endsection
