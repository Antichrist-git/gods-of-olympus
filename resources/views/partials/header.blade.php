<header class="site-header">
    <nav class="site-container nav-inner" aria-label="Основная навигация">
        <a class="brand" href="{{ route('gods.index') }}" aria-label="Олимп — главная">
            <span class="brand-mark" aria-hidden="true">Ω</span>
            <span>ОЛИМП<small>МИФЫ И ЛЕГЕНДЫ</small></span>
        </a>
        <div class="nav-links">
            <a href="{{ route('gods.index') }}"
                class="nav-catalog {{ request()->routeIs('gods.index') ? 'active' : '' }}"
                @if (request()->routeIs('gods.index')) aria-current="page" @endif>Пантеон</a>
            @auth
            <a class="btn btn-primary btn-sm" href="{{ route('gods.create') }}"><span aria-hidden="true">+</span>
                Добавить бога</a>
            @endauth
            @guest
                <a href="{{ route('login') }}">Войти</a>
                <a href="{{ route('register') }}">Регистрация</a>
            @else
                <a href="{{ route('feed.index') }}">Лента</a>
                <a href="{{ route('collections.index') }}">Коллекции</a>
                <a href="{{ route('collections.show', auth()->user()) }}">{{ auth()->user()->name }}</a>
                @can('manage-archive')
                    <a href="{{ route('archive.index') }}">Удалённые</a>
                @endcan
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-primary btn-sm" type="submit">Выйти</button>
                </form>
            @endguest
        </div>
    </nav>
</header>
