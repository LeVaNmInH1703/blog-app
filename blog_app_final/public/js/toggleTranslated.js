let textHideTranslated='';
let textShowTranslated='';
function ToggleTranslated(e) {
    e.stopPropagation();
    // Lấy phần tử translated-container
    const translatedContainer = e.target.nextElementSibling; // Sử dụng nextElementSibling để lấy div kế tiếp
    const button = e.target; // Lấy nút bấm

    if (translatedContainer.style.display === 'none' || translatedContainer.style.display === '') {
        translatedContainer.style.display = 'block'; // Hiện bản dịch
        button.textContent = textHideTranslated; // Đổi nội dung nút
    } else {
        translatedContainer.style.display = 'none'; // Ẩn bản dịch
        button.textContent = textShowTranslated; // Đổi nội dung nút
    }
}