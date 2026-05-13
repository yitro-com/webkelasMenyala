// ================= FOTO PREVIEW =================

const photoInput = document.getElementById('photoInput');
const photoPreview = document.getElementById('photoPreview');
const photoTitle = document.getElementById('photoTitle');
const photoFiles = [];

function renderPhotoPreview() {
    photoPreview.innerHTML = "";

    photoFiles.forEach((file) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const title = photoTitle.value.trim() || file.name;
            const item = document.createElement('div');
            item.classList.add('gallery-item');
            item.innerHTML = `
                <img src="${e.target.result}">
                <h4>${title}</h4>
            `;
            photoPreview.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
}

photoInput.addEventListener('change', function() {
    const newFiles = Array.from(this.files);
    newFiles.forEach(file => {
        const exists = photoFiles.some(f => f.name === file.name && f.size === file.size && f.type === file.type);
        if (!exists) {
            photoFiles.push(file);
        }
    });
    this.value = '';
    renderPhotoPreview();
});

const photoForm = photoInput.closest('form');
if (photoForm) {
    photoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (photoFiles.length === 0) {
            alert('Pilih minimal 1 foto terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('photoTitle', photoTitle.value.trim());
        photoFiles.forEach(file => formData.append('photos[]', file));
        formData.append('uploadFoto', '1');

        fetch(photoForm.action || window.location.pathname, {
            method: 'POST',
            body: formData
        }).then(response => {
            if (response.ok) {
                window.location.href = 'tampilan.php';
            } else {
                response.text().then(text => alert('Upload foto gagal. ' + text));
            }
        }).catch(() => {
            alert('Upload foto gagal. Coba lagi.');
        });
    });
}

// ================= VIDEO PREVIEW =================

const videoInput = document.getElementById('videoInput');
const videoPreview = document.getElementById('videoPreview');
const videoTitle = document.getElementById('videoTitle');
const videoFiles = [];

function renderVideoPreview() {
    videoPreview.innerHTML = "";

    videoFiles.forEach((file) => {
        const videoURL = URL.createObjectURL(file);
        const title = videoTitle.value.trim() || file.name;
        const item = document.createElement('div');
        item.classList.add('gallery-item');
        item.innerHTML = `
            <video controls>
                <source src="${videoURL}">
            </video>
            <h4>${title}</h4>
        `;
        videoPreview.appendChild(item);
    });
}

videoInput.addEventListener('change', function() {
    const newFiles = Array.from(this.files);
    newFiles.forEach(file => {
        const exists = videoFiles.some(f => f.name === file.name && f.size === file.size && f.type === file.type);
        if (!exists) {
            videoFiles.push(file);
        }
    });
    this.value = '';
    renderVideoPreview();
});

const videoForm = videoInput.closest('form');
if (videoForm) {
    videoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (videoFiles.length === 0) {
            alert('Pilih minimal 1 video terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('videoTitle', videoTitle.value.trim());
        videoFiles.forEach(file => formData.append('videos[]', file));
        formData.append('uploadVideo', '1');

        fetch(videoForm.action || window.location.pathname, {
            method: 'POST',
            body: formData
        }).then(response => {
            if (response.ok) {
                window.location.href = 'tampilan.php';
            } else {
                response.text().then(text => alert('Upload video gagal. ' + text));
            }
        }).catch(() => {
            alert('Upload video gagal. Coba lagi.');
        });
    });
}

// ================= 3D EFFECT =================

document.addEventListener('mousemove', (e) => {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const x = (window.innerWidth / 2 - e.pageX) / 25;
        const y = (window.innerHeight / 2 - e.pageY) / 25;
        card.style.transform = `rotateY(${x}deg) rotateX(${-y}deg)`;
    });
});

// ================= RESET =================
document.addEventListener('mouseleave', () => {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.transform = `rotateY(0deg) rotateX(0deg)`;
    });
});

// ================= ACTIVE CARD =================

const cards = document.querySelectorAll('.card');
const photoCard = photoInput.closest('.card');
const videoCard = videoInput.closest('.card');

photoInput.addEventListener('change', () => {
    cards.forEach(card => card.classList.remove('active'));
    photoCard.classList.add('active');
});

videoInput.addEventListener('change', () => {
    cards.forEach(card => card.classList.remove('active'));
    videoCard.classList.add('active');
});