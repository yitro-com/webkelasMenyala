// ==========================================
// BACKGROUND ANIMASI BOLA FLOATING
// ==========================================

// jumlah bola
const totalCircles = 25;

// warna random
const colors = [
    "#3b82f6",
    "#06b6d4",
    "#8b5cf6",
    "#2563eb",
    "#9333ea"
];

// buat bola otomatis
for (let i = 0; i < totalCircles; i++) {

    const circle = document.createElement("div");
    circle.classList.add("circle");

    // ukuran random
    const size = Math.random() * 120 + 40;

    circle.style.width = `${size}px`;
    circle.style.height = `${size}px`;

    // posisi awal random
    circle.style.left = `${Math.random() * 100}%`;

    // mulai dari bawah layar
    circle.style.top = `${window.innerHeight + Math.random() * 100}px`;

    // warna random
    circle.style.background =
        colors[Math.floor(Math.random() * colors.length)];

    // opacity random
    circle.style.opacity = Math.random() * 0.4 + 0.2;

    // blur random
    circle.style.filter = `blur(${Math.random() * 3}px)`;

    // simpan speed
    circle.dataset.speed = Math.random() * 1.5 + 0.5;

    // gerakan samping
    circle.dataset.moveX = (Math.random() - 0.5) * 1.5;

    document.body.appendChild(circle);
}

// ambil semua circle
const circles = document.querySelectorAll(".circle");

// animasi
function animateCircles() {

    circles.forEach(circle => {

        let top = parseFloat(circle.style.top);
        let left = parseFloat(circle.style.left);

        // naik ke atas
        top -= parseFloat(circle.dataset.speed);

        // gerak kanan kiri
        left += parseFloat(circle.dataset.moveX) * 0.05;

        // reset kalau sudah keluar atas
        if (top < -200) {

            top = window.innerHeight + 100;
            left = Math.random() * 100;

            // random ulang ukuran
            const size = Math.random() * 120 + 40;

            circle.style.width = `${size}px`;
            circle.style.height = `${size}px`;

            // random ulang opacity
            circle.style.opacity = Math.random() * 0.4 + 0.2;
        }

        circle.style.top = `${top}px`;
        circle.style.left = `${left}%`;
    });

    requestAnimationFrame(animateCircles);
}

animateCircles();