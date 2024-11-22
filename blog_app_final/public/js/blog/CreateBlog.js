function handleInputChange(e) {
    const input=e.target;
    const previewContainer = document.querySelector(".container-preview");

    previewContainer.innerHTML = "";
    let hasFiles = false;

    Array.from(input.files).forEach((file) => {
        if (!file) return;
        hasFiles = true;
        const fileURL = URL.createObjectURL(file);
        const isImage = file.type.startsWith("image/");
        const isVideo = file.type.startsWith("video/");

        // Tạo phần tử xem trước
        const previewElement = document.createElement("div");
        previewElement.classList.add("preview-item");

        // Tạo phần tử để hiển thị tệp
        let mediaElement;
        if (isImage) {
            mediaElement = document.createElement("img");
            mediaElement.src = fileURL;
            mediaElement.alt = "Xem trước hình ảnh";
            mediaElement.classList.add("preview-image");
        } else if (isVideo) {
            mediaElement = document.createElement("video");
            mediaElement.src = fileURL;
            mediaElement.controls = true;
            mediaElement.classList.add("preview-video");
        }

        // Tạo nút xóa
        const removeButton = document.createElement("button");
        removeButton.textContent = "x";
        removeButton.classList.add("remove-button");
        removeButton.addEventListener("click", () => {
            previewContainer.removeChild(previewElement);
            // Kiểm tra xem còn tệp nào không
            hasFiles = previewContainer.children.length > 0;
            document.querySelector(".post-button").disabled = !hasFiles; // Bật/tắt nút đăng
        });

        // Thêm media và nút vào phần tử xem trước
        previewElement.appendChild(mediaElement);
        previewElement.appendChild(removeButton);
        previewContainer.appendChild(previewElement);
    });
    document.querySelector(".post-button").disabled = !hasFiles;
}
function handleInputDateTime(e){
    e.target.closest('.option').querySelector('input').showPicker();
}
function handleChangeDatetime(e){
    const container=e.target.closest('.option');
    container.title=container.querySelector('input').value;
}
function handleContentChange(e){
    const textarea=e.target;
    textarea.rows = Math.min(Math.max(Math.floor(textarea.scrollHeight / 24), 2), 15);
    document.querySelector(".post-button").disabled = textarea.value=="";
}