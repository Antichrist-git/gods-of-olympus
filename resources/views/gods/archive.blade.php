@extends('layouts.app')
@section('title', 'Корзина')
@section('content')
    <main id="main" class="container py-5">
        <h1>Корзина</h1>
        @error('restore') <p class="alert alert-warning" role="alert">{{ $message }}</p> @enderror
        <form class="my-3" method="GET"><label for="archive-q">Поиск по имени</label>
<input class="form-control" name="q" id="archive-q" value="{{ request('q') }}"><button class="btn btn-outline-primary mt-2">Найти</button></form>
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
                <p>Карточки не найдены.</p>
            @endforelse
        </div>{{ $gods->links('pagination::bootstrap-5') }}
    </main>
@endsection
