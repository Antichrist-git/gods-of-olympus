@extends('layouts.app')
@section('title', 'Боги Древней Греции')
@section('content')
    <main id="main" class="site-container main-content">
        <section class="hero" aria-labelledby="page-title">
            <div class="hero-copy">
                <p class="eyebrow">АНТИЧНОСТЬ БЛИЖЕ, ЧЕМ КАЖЕТСЯ</p>
                <h1 id="page-title">Боги<br>Древней Греции<span>.</span></h1>
                <p class="hero-description">Властители стихий, хранители мудрости и герои вечных историй. Откройте для себя
                    древнегреческий пантеон.</p>
                <a class="hero-link" href="#collection">Исследовать пантеон <span aria-hidden="true">↗</span></a>
            </div>
            <div class="hero-art rotating-art" aria-label="Иллюстрации пантеона">
                @foreach (['olympus.jpg' => 'Гора Олимп', 'Athena.jpg' => 'Афина', 'Poseidon.jpg' => 'Посейдон'] as $image => $caption)
                    <figure class="hero-slide {{ $loop->first ? 'current' : '' }}">
                        <img src="{{ asset('storage/gods/'.$image) }}" alt="">
                        <figcaption>{{ $caption }}</figcaption>
                    </figure>
                @endforeach
                <button type="button" class="slide-pause" aria-label="Приостановить смену изображений">Ⅱ</button>
            </div>
        </section>
        <section id="collection" class="collection" aria-labelledby="collection-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">КОЛЛЕКЦИЯ</p>
                    <h2 id="collection-title">Древнегреческий пантеон
                    </h2>
                </div>
            </div>
            @if ($gods->isNotEmpty())
                <div class="collection-toolbar">
                    <label class="search-box"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><input type="search" id="godSearch"
                            class="form-control" placeholder="Найти бога или сферу покровительства…"
                            aria-label="Поиск по имени и сфере покровительства"></label>
                    <div class="filter-box"><label for="godType" class="visually-hidden">Сфера
                            покровительства</label><select id="godType" class="form-select">
                            <option value="">Все сферы</option>
                            @foreach ($gods->pluck('type')->unique()->sort() as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select></div>
                    <span id="resultCount" class="result-count" role="status">Показано: {{ $gods->count() }}</span>
                </div>
            @endif
            <div class="row g-4" id="godsGrid">
                @forelse ($gods as $god)
                    <div class="god-column col-12 col-md-6 col-lg-4" data-search="{{ $god->name }} {{ $god->type }}"
                        data-type="{{ $god->type }}">
                        @include('gods.partials.card')
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state"><span aria-hidden="true">Ω</span>
                            <h3>Первая история — за вами</h3>
                            @auth
                            <p>Добавьте бога, чтобы начать свою коллекцию.</p><a class="btn btn-primary"
                                href="{{ route('gods.create') }}">Добавить бога</a>
                            @endauth
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="empty-state" id="noResults" hidden><span aria-hidden="true">⌕</span>
                <h3>Ничего не нашлось</h3>
                <p>Попробуйте другое имя или выберите все сферы.</p><button class="btn btn-outline-primary"
                    id="resetFilters" type="button">Сбросить поиск</button>
            </div>
        </section>
    </main>
    @include('gods.partials.modal')
@endsection
