
function handleClickToComment(e) {
    e.stopPropagation();
    const form = document.querySelector("#form_container");
    if(!form) return;
    const button = e.target.closest(".btn_comment");
    const commentWrap = button.closest(".comment_wrap");
    let commentId = commentWrap?commentWrap.getAttribute("data-id"):'';
    form.querySelector("#form_rep").setAttribute(
        "action",
        "/create-comment/" +
            document.querySelector(".blog_wrap").getAttribute("data-id") +
            "/" +
            commentId
    );
    //show form after comment or blog
    (commentWrap
        ? commentWrap.querySelector(".comment-footer")
        : document.querySelector(".blog_container")
    ).insertAdjacentElement("afterend", form);

    form.style.display = "block";
    form.querySelector("#form_rep .content").focus();
}
