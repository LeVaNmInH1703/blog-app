let nextPageUrl='load-more-blog';
let isLoading = false;
let isLocking = false;
function loadMorePost(){
    $(window).on('scroll', function() {
        if (!((document.documentElement.scrollHeight - (window.scrollY + window.innerHeight) <
                    1000) && !
                isLoading && !isLocking)) return;
        loadHandel();        
    });
    loadHandel(); // Tải dữ liệu ban đầu khi vào trang
};
function loadHandel(){
    if(!nextPageUrl) return;
    isLoading = true; // Đánh dấu đang tải
    console.log(nextPageUrl);
    $.ajax({
        url: nextPageUrl,
        data: {
            loadedBlogs: Array.from(document.querySelectorAll('.blog_wrap')).map(
                element =>
                element.getAttribute('data-id'))
        },
        success: function(response) {
            if (!response) return;
            document.querySelector('.blog_container').innerHTML += response.innerHTML;
            nextPageUrl = response.nextPageUrl;
            isLoading = false; // Đánh dấu đã tải xong
            isLocking = true;
            setTimeout(() => {
                isLocking = false;
            }, 1500);
        }
    });
}