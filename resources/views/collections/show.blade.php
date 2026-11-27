@extends('layouts.app')
@section('title', 'Коллекция — ' . $owner->name)
@section('content')
    <main id="main" class="container py-5">
        <a href="{{ route('collections.index') }}">Все коллекции</a>
        <h1 class="my-3">Коллекция — {{ $owner->name }}</h1>
        @if (auth()->user()->is($owner))
            <a class="back-link" href="{{ route('profile.access') }}">Доступ к API</a>
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
        <div class="row g-4 my-2">
            @forelse ($gods as $god)
                <div class="god-column col-12 col-md-6 col-lg-4">
                    @if ($god->trashed())
                        @include('gods.partials.archived-card')
                    @else
                        @include('gods.partials.card')
                    @endif
                </div>
            @empty
                <p>В коллекции пока нет карточек.</p>
            @endforelse
        </div>
    </main>
@include('gods.partials.modal')
@endsection
