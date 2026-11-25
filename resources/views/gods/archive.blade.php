@extends('layouts.app')
@section('title', 'Удалённые карточки')
@section('content')
    <main id="main" class="container py-5">
        <h1>Удалённые карточки</h1>
        @error('restore') <p class="alert alert-warning" role="alert">{{ $message }}</p> @enderror
        <ul class="list-group">
            @forelse ($gods as $god)
                <li class="list-group-item">
                    <strong>{{ $god->name }}</strong> · {{ $god->user?->name ?? 'Без владельца' }}
                    <span>· Удалено {{ $god->deleted_at->format('d.m.Y') }}</span>
                    @include('gods.partials.archive-actions')
                </li>
            @empty
                <li class="list-group-item">Удалённых карточек пока нет.</li>
            @endforelse
        </ul>
    </main>
@endsection
