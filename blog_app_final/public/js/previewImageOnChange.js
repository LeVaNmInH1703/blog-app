function setUpPreviewImageOnchange(inputId, previewId) {
    const fileInput = document.getElementById(inputId);
    const previewImage = document.getElementById(previewId);
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.hidden = false;
        }
        reader.readAsDataURL(file);

    });
}
document.addEventListener('DOMContentLoaded', () => {
    setUpPreviewImageOnchange('input-file', 'filePreview');
});