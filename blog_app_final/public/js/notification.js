function togglePopup(e) {
    e.stopPropagation();
    document.querySelector("#countNotifyIcon").innerText = 0;
    document.querySelector("#countNotifyIcon").style.display = "none";
    const popup = document.getElementById("notificationPopup");
    popup.style.display = popup.style.display == "block" ? "none" : "block";
    $.ajax({
        url: "/see-all-notification",
    });
}

// Đóng popup khi click ra ngoài
window.onclick = function (event) {
    const popup = document.getElementById("notificationPopup");
    if (
        !event.target.matches(".notification-button") &&
        popup.style.display === "block"
    ) {
        popup.style.display = "none";
    }
};
function openLink(link, keyWord) {
    const newTab = window.open(link);
    console.log(newTab);
    if (!newTab || !keyWord) return;
    newTab.onload = function () {
        // test
        const parts = keyWord.split("-");
        const name = parts[0]; // 'comment'
        if (name != "comment") return;
        const id = parseInt(parts[1], 10); // 213
        let obj = newTab.document.querySelector(
            `.model_wrap[data-id='${id}'][data-name='${name}']`
        );
        const rootObj=obj;
        while (obj) {
            obj = obj.parentElement.closest(".model_wrap");
            if (!obj) break;
            const toggleButton = obj.querySelector(".toggle_comment_child");
            const temp = obj.querySelector(".comment_children").style.display;
            if (toggleButton && (temp == "none" || temp == "")) {
                toggleButton.click();
            }
        }
        rootObj.scrollIntoView();
    };
}
function listenEventHasNewNotify(authId) {
    window.Echo.private(`channelHasNewNotification.${authId}`).listen(
        "HasNewNotificationEvent",
        (data) => {
            getNotification(data.notificationId);
        }
    );
}
function getNotification(id) {
    $.ajax({
        url: "/get-notification/" + id,
        success: function (response) {
            document.querySelector("#countNotifyIcon").style.display = "block";
            document.querySelector("#countNotifyIcon").innerText =
                parseInt(
                    document.querySelector("#countNotifyIcon").innerText,
                    10
                ) + 1;
            document.querySelector("#notificationPopup").innerHTML =
                response +
                document.querySelector("#notificationPopup").innerHTML;
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });
}
