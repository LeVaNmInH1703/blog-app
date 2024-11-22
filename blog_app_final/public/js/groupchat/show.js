function PreviewImage(e) {
    const previewImg = document.getElementById("previewImg");
    const file = e.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImg.src = e.target.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        previewImg.src = "#";
    }
}
