function handleToggleCommentChildButton(e) {
    const toggleButtons = e.target.closest(".toggle_comment_child");
    const commentChild = toggleButtons
        .closest(".comment_wrap")
        .querySelector(".comment_children");
    if (
        commentChild.style.display == "none" ||
        commentChild.style.display == ""
    ) {
        commentChild.style.display = "block";
        toggleButtons.querySelector(".show").style.display = "none";
        toggleButtons.querySelector(".hide").style.display = "block";
        $.ajax({
            url:
                "/get-replies/" +
                toggleButtons.closest(".comment_wrap").getAttribute("data-id"),
            success: function (response) {
                console.log(response);
                commentChild.innerHTML = response.commentChildrenInnerHTML;
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    } else {
        commentChild.style.display = "none";
        toggleButtons.querySelector(".show").style.display = "block";
        toggleButtons.querySelector(".hide").style.display = "none";
    }
}
