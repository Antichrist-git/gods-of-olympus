<footer class="site-footer">
    <div class="site-container footer-inner">
        <a class="footer-brand" href="{{ route('gods.index') }}">Ω <span>ОЛИМП</span></a>
        <span>Мифы, которые живут тысячелетиями.</span>
        <span>Зырянов Кирилл · {{ date('Y') }}</span>
        @if(config('app.contact_url'))<a href="{{ config('app.contact_url') }}">Связаться с автором</a>@endif
    </div>
</footer>
