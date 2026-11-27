@extends('layouts.app')
@section('title', 'Коллекция — ' . $owner->name)
@section('content')
    <main id="main" class="container py-5">
        <a href="{{ route('collections.index') }}">Все коллекции</a>
        <h1 class="my-3">Коллекция — {{ $owner->name }}</h1>
        @if (auth()->user()->is($owner))
            <form method="POST" action="{{ route('profile.api-token') }}" class="mb-3">
                @csrf
                <button class="btn btn-primary">Создать API-токен</button>
            </form>
        @endif
        @unless (auth()->user()->is($owner))
            <form class="mb-3" method="POST" action="{{ route($isFriend ? 'friends.destroy' : 'friends.store', $owner) }}">
                @csrf
                @if ($isFriend)
                    @method('DELETE')
                    <button class="btn btn-outline-primary">Удалить из друзей</button>
                @else
                    <button class="btn btn-primary">Добавить в друзья</button>
                @endif
            </form>
        @endunless
        @error('restore') <p class="alert alert-warning" role="alert">{{ $message }}</p> @enderror
        <ul class="list-group">
            @forelse ($gods as $god)
                <li class="list-group-item">
                    @if ($god->trashed())
                        {{ $god->name }} · Удалено {{ $god->deleted_at->format('d.m.Y') }}
                        @include('gods.partials.archive-actions')
                    @else
                        <a href="{{ route('gods.show', $god) }}">{{ $god->name }}</a>
                    @endif
                </li>
            @empty
                <li class="list-group-item">В коллекции пока нет богов.</li>
            @endforelse
        </ul>
    </main>
@endsection
