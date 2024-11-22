let mouseDown = 0;
function clickToBlogDetail(e) {
    e.stopPropagation();
    const blog = e.target.closest('.blog_wrap');
    if (Date.now() - mouseDown > 75) return;
    window.location.assign("/blog-detail/" + blog.getAttribute("data-id"));
}
function handleMouseDownBlog(e) {
    mouseDown = Date.now();
}
