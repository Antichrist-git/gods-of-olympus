@extends('layouts.app')
@section('title', 'Лента друзей')
@section('content')
    <main id="main" class="container py-5">
        <h1 class="mb-4">Лента друзей</h1>
        @forelse ($gods as $god)
            <article class="border-top py-3">
                <a href="{{ route('collections.show', $god->user) }}">{{ $god->user->name }}</a>
                <time class="small text-secondary ms-2" datetime="{{ $god->created_at->toAtomString() }}">{{ $god->created_at->format('d.m.Y H:i') }}</time>
                <h2 class="h4 mt-2"><a href="{{ route('gods.show', $god) }}">{{ $god->name }}</a></h2>
                <p>{{ $god->short_description }}</p>
            </article>
        @empty
            <p>Здесь появятся карточки, созданные вашими друзьями.</p>
            <a href="{{ route('collections.index') }}">Открыть коллекции пользователей</a>
        @endforelse
        {{ $gods->links('pagination::simple-bootstrap-5') }}
    </main>
@endsection
