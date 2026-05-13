const preview = document.getElementById('preview');
const previewImage = document.getElementById('previewImage');
const previewVideo = document.getElementById('previewVideo');

function openPreview(src, type) {
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

function closePreview() {
    preview.style.display = 'none';
    previewVideo.pause();
}

preview.addEventListener('click', (e) => {
    if (e.target === preview) {
        closePreview();
    }
});