document.getElementById('cover-add')?.addEventListener('change', e =>
    showCoverPreview(e.target.files[0], 'cover-preview-add')
);

document.getElementById('cover-edit')?.addEventListener('change', e =>
    showCoverPreview(e.target.files[0], 'cover-preview-edit')
);

function clearPreviews() {
    const ids = ['cover-preview-add', 'file-preview-add', 'cover-preview-edit', 'file-preview-edit'];
    ids.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.innerHTML = '';
    });
}

function addPreview(type, path, target) {
    const box = document.getElementById(target);
    box.innerHTML = '';

    if (!path) return;

    if (type === 'cover') {
        const img = document.createElement('img');
        img.src = path;
        img.alt = 'Превью обложки';
        img.className = 'preview-image';
        box.appendChild(img);
    } else {
        const link = document.createElement('a');
        link.href = path;
        link.textContent = '📄 Открыть файл';
        link.target = '_blank';
        box.appendChild(link);
    }
}

function showCoverPreview(file, previewId) {
    const box = document.getElementById(previewId);
    box.innerHTML = '';
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.className = 'preview-image';
    img.onload = () => URL.revokeObjectURL(img.src);
    box.appendChild(img);
}