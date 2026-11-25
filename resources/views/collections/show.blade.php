@extends('layouts.app')
@section('title', 'Коллекция — ' . $owner->name)
@section('content')
    <main id="main" class="container py-5">
        <a href="{{ route('collections.index') }}">Все коллекции</a>
        <h1 class="my-3">Коллекция — {{ $owner->name }}</h1>
        <ul class="list-group">
            @forelse ($gods as $god)
                <li class="list-group-item"><a href="{{ route('gods.show', $god) }}">{{ $god->name }}</a></li>
            @empty
                <li class="list-group-item">В коллекции пока нет богов.</li>
            @endforelse
        </ul>
    </main>
@endsection
