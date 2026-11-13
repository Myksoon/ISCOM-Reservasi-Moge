// header: solid setelah digulir, sembunyi saat menggulir ke bawah, muncul lagi saat ke atas
const kepala = document.querySelector('.kepala');
let posisiTerakhir = window.scrollY;

function aturKepala() {
    const y = window.scrollY;
    kepala.classList.toggle('kepala-gulir', y > 40);
    kepala.classList.toggle('kepala-sembunyi', y > posisiTerakhir && y > 400);
    posisiTerakhir = y;
}

window.addEventListener('scroll', aturKepala, { passive: true });
aturKepala();

// elemen bertanda data-muncul tampil pelan saat masuk layar
const daftarMuncul = document.querySelectorAll('[data-muncul]');

if ('IntersectionObserver' in window) {
    const pengamat = new IntersectionObserver((entri) => {
        entri.forEach((e) => {
            if (e.isIntersecting) {
                e.target.classList.add('terlihat');
                pengamat.unobserve(e.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px' });

    daftarMuncul.forEach((el) => pengamat.observe(el));
} else {
    daftarMuncul.forEach((el) => el.classList.add('terlihat'));
}
