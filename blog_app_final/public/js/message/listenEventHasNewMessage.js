function listenEventGroups(groups) {
    groups.forEach((group) => {
        window.Echo.private(`channelHasNewMessage.${group.id}`).listen(
            "HasNewMessageEvent",
            (data) => {
                console.log(data);
                if (window.location.pathname === "/message") {
                    AddNewMessage(data.message);
                } else {
                    showToast(data.message);
                    countNewMessage();
                }
            }
        );
    });
    if (window.location.pathname !== "/message") {
        countNewMessage();
    }
}
function countNewMessage() {
    $temp = document.querySelector("#notifiMessageIcon");
    $.ajax({
        url: "/count-group-has-new-message",
        success: function (data) {
            if (data > 0) {
                $temp.style.display = "inline-block";
                $temp.innerText = data;
            }
        },
    });
}
function showToast(message) {
    $.ajax({
        url: "/get-toast-for-message/" + message.id,
        success: function (toastHTML) {
            showNewToast(toastHTML);
        },
        error: function (e) {
            console.log(e);
        },
    });
}
function showNewToast(toastHTML) {
    var tempDiv = document.createElement("div");
    tempDiv.innerHTML = toastHTML.trim();
    var newToastElement = tempDiv.firstChild;
    var toastContainer = document.getElementById("toast-container");
    toastContainer.appendChild(newToastElement);
    var newToast = new bootstrap.Toast(newToastElement);
    newToast.show();
    newToastElement.addEventListener("hidden.bs.toast", function () {
        newToastElement.remove();
    });
}
