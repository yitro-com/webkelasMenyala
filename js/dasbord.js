function showSlide(id) {

    // Semua slide
    const slides = document.querySelectorAll('.slide-section');

    // Slide aktif sekarang
    const currentSlide = document.querySelector('.slide-section.active');

    // Jika klik halaman yang sama
    if (currentSlide && currentSlide.id === id) {
        return;
    }

    // Animasi keluar halaman lama
    if (currentSlide) {

        currentSlide.classList.add('reverse');

        setTimeout(() => {

            // Hapus semua active
            slides.forEach(slide => {
                slide.classList.remove('active');
                slide.classList.remove('reverse');
            });

            // Tampilkan halaman baru
            const nextSlide = document.getElementById(id);

            nextSlide.classList.add('active');

            // RESET ANIMASI TITLE
            const title = nextSlide.querySelector('.welcome-title');

            const text = nextSlide.querySelector('p');

            if (title) {

                title.style.animation = "none";

                void title.offsetWidth;

                title.style.animation = "fadeZoom 4s ease";
            }

            if (text) {

                text.style.animation = "none";

                void text.offsetWidth;

                text.style.animation = "fadeUp 5s ease";
            }

        }, 500);

    } else {

        document.getElementById(id).classList.add('active');
    }
}


/* BUTTON POPUP */
function openPopup(page, button) {

    // Reset animasi tombol
    button.classList.remove("clicked");

    void button.offsetWidth;

    // Tambahkan animasi klik
    button.classList.add("clicked");

    // Pindah halaman
    setTimeout(() => {

        showSlide(page);

    }, 300);
}