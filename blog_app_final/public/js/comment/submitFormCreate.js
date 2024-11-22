async function handleFormCommentSubmit(e) {
    e.preventDefault(); // Ngăn không cho form submit bình thường

    const form = e.target;
    const buttonSubmit = form.querySelector(".btn-submit");

    // Kiểm tra nếu button đã bị disabled
    if (buttonSubmit.disabled) return;

    // Tắt button và thay đổi con trỏ
    buttonSubmit.disabled = true;
    buttonSubmit.style.cursor = "wait";

    try {
        // Gửi request với Fetch API
        const response = await fetch(form.action, {
            method: "POST",
            body: new FormData(form),
        });

        // Chuyển response thành JSON
        const data = await response.json();

        // Kích hoạt lại button
        buttonSubmit.disabled = false;
        buttonSubmit.style.cursor = "default";

        const commentWrap = form.closest(".comment_wrap");
        // console.log(data);
        if (commentWrap) {
            const commentCount = commentWrap.querySelector(".comment_count");
            commentCount.innerHTML = data.parentCountCommentInnerHTML;
            const commentChildren =
                commentWrap.querySelector(".comment_children");
            // Hiển thị các comment con (bỏ qua thao tác click)
            const toggleCommentChild = commentCount.querySelector(
                ".toggle_comment_child"
            );
            if (toggleCommentChild) {
                if (commentChildren.style.display == "block")
                    commentChildren.style.display = "none";
                toggleCommentChild.style.display = "block";
                toggleCommentChild.click();
            }
            console.log(toggleCommentChild);
        } else {
            const commentContainer =
                document.querySelector(".comment_container");

            // Thêm comment vào container
            commentContainer.innerHTML =
                data.newCommentInnerHTML + commentContainer.innerHTML;
        }

        // Cập nhật tổng số comment của blog
        const blogCountComment = document.querySelector(
            ".blog_container .count_comment"
        );
        blogCountComment.innerText = data.blogCountCommentInnerText;

        // Reset form sau khi gửi thành công
        form.reset();
    } catch (error) {
        // Kích hoạt lại button nếu có lỗi
        buttonSubmit.disabled = false;
        buttonSubmit.style.cursor = "default";
        console.error("Error:", error);
    }
}
