<header class="site-header">
    <nav class="site-container nav-inner" aria-label="Основная навигация">
        <a class="brand" href="{{ route('gods.index') }}" aria-label="Олимп — главная">
            <span class="brand-mark" aria-hidden="true">Ω</span>
            <span>ОЛИМП<small>МИФЫ И ЛЕГЕНДЫ</small></span>
        </a>
        <div class="nav-links">
            @foreach (['gods.index' => 'Пантеон', 'feed.index' => 'Лента', 'collections.index' => 'Коллекции'] as $route => $label)
                @if ($loop->first || auth()->check())
                    <a href="{{ route($route) }}" @if(request()->routeIs($route) || ($loop->first && request()->routeIs('gods.show')) || ($route === 'collections.index' && request()->routeIs('collections.show'))) aria-current="page" @endif>{{ $label }}</a>
                @endif
            @endforeach
            @auth
                <a class="btn btn-primary btn-sm" href="{{ route('gods.create', ['from' => request()->getRequestUri()]) }}">+ Добавить бога</a>
                <details class="profile-menu"><summary @if(request()->routeIs("profile.*", "archive.*")) aria-current="page" @endif><i class="fa-solid fa-circle-user"></i> {{ auth()->user()->name }}</summary>
                    <div><a href="{{ route('collections.show', auth()->user()) }}">Моя коллекция</a>
                    <a href="{{ route('profile.access') }}">Доступ к API</a>
                    @can('manage-archive')<a href="{{ route('archive.index') }}">Корзина</a>@endcan
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Выйти</button></form></div>
                </details>
            @else
                <a href="{{ route('login') }}">Войти</a><a href="{{ route('register') }}">Регистрация</a>
            @endauth
        </div>
    </nav>
</header>
