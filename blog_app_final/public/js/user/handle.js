function clickToProfile(wrap) {
    window.open("/profile/" + wrap.getAttribute("data-id"), "_blank");
}
function seeMoreUser(e) {
    const divs = e.target
        .closest(".user-container")
        .querySelectorAll(".user-wrap.hidden");
    divs.forEach((div, index) => {
        if (index < 5) div.classList.remove("hidden");
    });
    if (divs.length <= 5) e.target.classList.add("hidden");
}
document.addEventListener("DOMContentLoaded", () => {
    document
        .querySelectorAll(".btn-see-more")
        .forEach((button, index, array) => {
            button.click();
        });
});
document.addEventListener("DOMContentLoaded", () => {
    window.Echo.private("channelReloadUsersPage.{{ Auth::id() }}").listen(
        "requestReloadPage",
        (e) => {
            location.reload(true);
        }
    );
    window.Echo.channel("channelReloadUsersPage").listen(
        "requestReloadPage",
        (e) => {
            location.reload(true);
        }
    );
});
let timeout; // Declare timeout in a wider scope

function InputSearchHandle(e) {
    const input = e.target;
    clearTimeout(timeout);
    timeout = setTimeout(function () {
        const query = input.value;
        if (query.length > 0) {
            $.ajax({
                url: "/search-users",
                method: "GET",
                data: {
                    search: query,
                },
                success: function (response) {
                    document.querySelector("#searchResults").innerHTML =
                        response;
                    document
                        .querySelector(
                            "#searchResults  .user-container .btn-see-more"
                        )
                        .click();
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                },
            });
        } else {
            document.querySelector("#searchResults").innerHTML = "";
        }
    }, 1000);
}
function handleRequest(e, url) {
    e.stopPropagation();
    $.ajax({
        url: url,
        success: function(response) {
            console.log(response);
            e.target.closest('.user-wrap').outerHTML = response.cardUserOuterHTML; 
        },
        error: function(xhr, status, error) {
            console.error(error);
        },
    });
}