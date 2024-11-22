function filterList(e) {
    const input = e.target;
    const filter = input.value.toLowerCase(); // Lấy giá trị đã nhập và chuyển thành chữ thường
    const chatList = document.querySelectorAll("ul.chat-list li"); // Lấy tất cả các mục trong danh sách

    chatList.forEach((item) => {
        const name = item.querySelector(".about .name").innerText.toLowerCase(); // Lấy tên người dùng trong từng mục

        // Kiểm tra xem tên có chứa giá trị tìm kiếm không
        if (name.includes(filter)) {
            item.style.display = "block"; // Hiển thị mục nếu có
        } else {
            item.style.display = "none"; // Ẩn mục nếu không có
        }
    });
}
