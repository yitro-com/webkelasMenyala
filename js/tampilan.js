const preview = document.getElementById('preview');
const previewImage = document.getElementById('previewImage');
const previewVideo = document.getElementById('previewVideo');

let editActive = false;

/* OPEN PREVIEW */
function openPreview(src, type) {

    // jika edit aktif jangan buka preview
    if (editActive) return;

    preview.style.display = 'flex';

    if (type === 'image') {

        previewImage.style.display = 'block';
        previewVideo.style.display = 'none';

        previewImage.src = src;

    } else {

        previewImage.style.display = 'none';
        previewVideo.style.display = 'block';

        previewVideo.src = src;

    }

}

/* CLOSE */
function closePreview() {

    preview.style.display = 'none';

    previewVideo.pause();

}

/* CLICK OUTSIDE */
preview.addEventListener('click', (e) => {

    if (e.target === preview) {
        closePreview();
    }

});

/* UPLOAD */
function uploadFile() {

    document.getElementById('fileInput').click();

}

/* EDIT MODE */
function editMode() {

    editActive = !editActive;

    const editBtn =
        document.querySelectorAll('.top-action button')[1];

    const cards = document.querySelectorAll('.card');

    if (editActive) {
        editBtn.innerHTML = 'Selesai Edit';
    } else {
        editBtn.innerHTML = 'Edit';
    }

    cards.forEach(card => {

        let checkbox = card.querySelector('.delete-checkbox');

        if (!checkbox) {
            checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'delete-checkbox';
            card.appendChild(checkbox);
        }

        if (editActive) {
            checkbox.style.display = 'block';
        } else {
            checkbox.style.display = 'none';
            checkbox.checked = false;
        }

    });

    // Tambahkan tombol hapus multiple jika belum ada
    let deleteSelectedBtn = document.querySelector('.delete-selected-btn');
    if (!deleteSelectedBtn) {
        deleteSelectedBtn = document.createElement('button');
        deleteSelectedBtn.type = 'button';
        deleteSelectedBtn.innerHTML = 'OK';
        deleteSelectedBtn.className = 'delete-selected-btn';
        deleteSelectedBtn.onclick = deleteSelected;
        document.querySelector('.top-action').appendChild(deleteSelectedBtn);
    }

    if (editActive) {
        deleteSelectedBtn.style.display = 'inline-block';
    } else {
        deleteSelectedBtn.style.display = 'none';
    }

}

function deleteSelected() {
    const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih item yang ingin dihapus');
        return;
    }

    document.getElementById('galleryForm').submit();
}

/* BACKGROUND BALLS */
const bgBalls =
    document.getElementById("bgBalls");

function createBall() {

    const ball =
        document.createElement("div");

    ball.classList.add("ball");

    // ukuran random
    const size =
        Math.random() * 180 + 60;

    ball.style.width = `${size}px`;
    ball.style.height = `${size}px`;

    // posisi random
    ball.style.left =
        `${Math.random() * 100}%`;

    // warna random
    const colors = [
        "rgba(0, 195, 255, 0.4)",
        "rgba(140, 82, 255, 0.4)",
        "rgba(0, 255, 200, 0.3)",
        "rgba(255, 0, 200, 0.3)"
    ];

    ball.style.background =
        colors[Math.floor(Math.random() * colors.length)];

    // durasi animasi
    const duration =
        Math.random() * 15 + 10;

    ball.style.animationDuration =
        `${duration}s`;

    bgBalls.appendChild(ball);

    // hapus setelah selesai
    setTimeout(() => {

        ball.remove();

    }, duration * 1000);

}

// buat bola terus menerus
setInterval(createBall, 800);