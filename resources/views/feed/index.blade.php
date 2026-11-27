@extends('layouts.app')
@section('title', 'Лента друзей')
@section('content')
    <main id="main" class="container feed-page py-5">
        <h1 class="mb-4">Лента друзей</h1>
        @forelse ($gods as $god)
            <article class="feed-story">
                <a class="feed-photo" href="{{ route('gods.show', [$god, 'from' => request()->getRequestUri()]) }}">@if($god->image_path)<img loading="lazy" src="{{ asset('storage/'.$god->image_path) }}" alt="{{ $god->name }}">@else Ω @endif</a><div>
                <a href="{{ route('collections.show', $god->user) }}">{{ $god->user->name }}</a>
                <time class="small text-secondary ms-2" datetime="{{ $god->created_at->toAtomString() }}">{{ $god->created_at->format('d.m.Y H:i') }}</time>
                <h2 class="h4 mt-2"><a href="{{ route('gods.show', [$god, 'from' => request()->getRequestUri()]) }}">{{ $god->name }}</a></h2>
                <p>{{ $god->short_description }}</p>
            <a href="{{ route('gods.show', [$god, 'from' => request()->getRequestUri()]) }}#comments">Обсуждение</a></div></article>
        @empty
            <p>Здесь появятся карточки, созданные вашими друзьями.</p>
            <a href="{{ route('collections.index') }}">Открыть коллекции пользователей</a>
        @endforelse
        {{ $gods->links('pagination::simple-bootstrap-5') }}
    </main>
@endsection
