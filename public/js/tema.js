const tombol = document.getElementById('tombol-tema');

function pasangTema(tema) {
    document.documentElement.dataset.theme = tema;
    document.documentElement.dataset.bsTheme = tema;
    tombol.setAttribute('aria-pressed', tema === 'dark');
    tombol.querySelector('i').className = tema === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
}

pasangTema(document.documentElement.dataset.theme);

tombol.addEventListener('click', () => {
    const tema = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
    localStorage.setItem('tema', tema);

    const tanpaAnimasi = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!document.startViewTransition || tanpaAnimasi) {
        pasangTema(tema);
        return;
    }

    // noda tinta berpusat di tombol dan harus cukup besar untuk menutup sudut terjauh
    const kotak = tombol.getBoundingClientRect();
    const x = kotak.left + kotak.width / 2;
    const y = kotak.top + kotak.height / 2;
    const terjauh = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));

    const html = document.documentElement;
    html.style.setProperty('--tinta-x', x + 'px');
    html.style.setProperty('--tinta-y', y + 'px');
    html.style.setProperty('--tinta-lebar', terjauh * 2.8 + 'px');

    document.startViewTransition(() => pasangTema(tema));
});
