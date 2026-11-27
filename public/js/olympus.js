const slides = Array.from(document.querySelectorAll('.hero-slide'));
const pause = document.querySelector('.slide-pause');
if (pause && slides.length > 1) {
    let index = 0;
    let timer;
    let paused = matchMedia('(prefers-reduced-motion: reduce)').matches;
    function schedule() {
        clearInterval(timer);
        pause.textContent = paused ? '▶' : 'Ⅱ';
        pause.setAttribute('aria-label', paused ? 'Продолжить смену изображений' : 'Приостановить смену изображений');
        if (!paused && !document.hidden) timer = setInterval(() => {
            slides[index].classList.remove('current');
            index = (index + 1) % slides.length;
            slides[index].classList.add('current');
        }, 15000);
    }
    pause.addEventListener('click', () => { paused = !paused; schedule(); });
    document.addEventListener('visibilitychange', schedule);
    schedule();
}
const openCard = document.getElementById('open-full-card');
const preview = document.getElementById('godModalBody');
if (openCard && preview) {
    new MutationObserver(() => {
        const title = document.getElementById('godModalTitle').textContent.trim();
        const link = Array.from(document.querySelectorAll('.god-card')).find(card => card.querySelector('.card-title')?.textContent.trim() === title)?.querySelector('.card-art');
        if (link) { openCard.href = link.href; openCard.hidden = false; }
    }).observe(preview, {childList:true});
}

const returnKey = 'olympus:return:' + location.pathname + location.search;
const saved = sessionStorage.getItem(returnKey);
if (saved && !location.hash) {
    sessionStorage.removeItem(returnKey);
    const state = JSON.parse(saved);
    const search = document.getElementById('godSearch');
    const type = document.getElementById('godType');
    if (search && type) {
        search.value = state.search;
        type.value = state.type;
        search.dispatchEvent(new Event('input', {bubbles:true}));
    }
    requestAnimationFrame(() => window.scrollTo(0, state.y));
}
function rememberList() {
    if (!document.querySelector('.god-column, .feed-story')) return;
    sessionStorage.setItem(returnKey, JSON.stringify({y:scrollY,
        search:document.getElementById('godSearch')?.value || '',
        type:document.getElementById('godType')?.value || ''}));
}
document.addEventListener('click', event => {
    const a = event.target.closest('a[href]');
    if (a && a.origin === location.origin && a.pathname !== location.pathname && !a.classList.contains('js-god-detail')) rememberList();
});
document.addEventListener('submit', rememberList);
