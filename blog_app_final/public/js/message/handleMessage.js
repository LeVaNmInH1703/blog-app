function continueChatWithOldContact(oldContact) {
    if (!oldContact) return;
    document.querySelectorAll(".chat-list li.clearfix").forEach((li) => {
        li.classList.remove("active");
    });
    const liOldContact = document.querySelector(
        `.chat-list li.clearfix[data-id='${oldContact}']`
    );
    if (liOldContact) liOldContact.classList.add("active");
    sessionStorage.setItem("numberOfMessage", 20);
    loadChatHistory(oldContact, sessionStorage.getItem("numberOfMessage"));
}

function handleChangeChatHistory(li, e) {
    e.preventDefault();
    document.querySelectorAll(".chat-list li.clearfix").forEach((li) => {
        li.classList.remove("active");
    });
    li.classList.add("active");
    var chatHistoryId = li.dataset.id;
    sessionStorage.setItem("numberOfMessage", 20);
    loadChatHistory(chatHistoryId, sessionStorage.getItem("numberOfMessage"));
}

function loadChatHistory(chatHistoryId) {
    $.ajax({
        url: "/chat-history/" + chatHistoryId,
        success: function (data) {
            document.querySelector("#chat-container").innerHTML = data.view;
            if (data.isCanContinueRender) {
                document.querySelector("#newest-message").scrollIntoView();
            }
            document.querySelector("#notify_" + chatHistoryId).style.display =
                "none";
            scrollToEndChatHistory();
        },
    });
    sessionStorage.setItem("chatCurrent", chatHistoryId);
}
function scrollToEndChatHistory() {
    document.querySelector(".chat-history").scrollTop =
        document.querySelector(".chat-history").scrollHeight;
}

function AddNewMessage(message) {
    //add to user receive
    
    if (!sessionStorage.getItem("chatCurrent")) return;
    if (
        message.chat_id == sessionStorage.getItem("chatCurrent")
    ) {
        getNewMessage(message.id);
        $.ajax({
            url: "/update-seen-message-in-group/" + message.chat_id,
        });
    } else
        document.querySelector(
            `#notify_${message.chat_id}`
        ).style.display = "inline-block";
}

function inputMediaFileOnChange(event) {
    const input = event.target;
    const files = input.files; // Lấy danh sách tệp được chọn
    const previewContainer = document.querySelector(
        "#files-post-preview #media"
    );
    previewContainer.innerHTML = ""; // Xóa các preview cũ
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        const fileInfoDiv = document.createElement("div");
        reader.onload = function (e) {
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "X";
            deleteButton.classList.add("btn", "btn-sm", "btn-secondary"); // Thêm lớp Bootstrap
            deleteButton.onclick = function () {
                fileInfoDiv.remove();
                const dataTransfer = new DataTransfer();
                Array.from(input.files).forEach((fileItem, fileIndex) => {
                    if (fileIndex !== index) {
                        dataTransfer.items.add(fileItem);
                    }
                });
                input.files = dataTransfer.files; // Cập nhật input files
            };
            fileInfoDiv.appendChild(deleteButton);
            if (file.type.startsWith("image/")) {
                const img = document.createElement("img");
                img.src = e.target.result; // Đặt đường dẫn hình ảnh
                fileInfoDiv.appendChild(img);
            } else if (file.type.startsWith("video/")) {
                const video = document.createElement("video");
                video.controls = true; // Hiển thị các điều khiển video
                const source = document.createElement("source");
                source.src = e.target.result; // Đặt đường dẫn video
                source.type = file.type;
                video.appendChild(source);
                fileInfoDiv.appendChild(video);
            }
        };
        reader.readAsDataURL(file); // Đọc tệp dưới dạng URL
        previewContainer.appendChild(fileInfoDiv);
    });
}

function inputOtherFileOnChange(event) {
    const input = event.target; // Lưu input để có thể xóa tệp
    const files = input.files; // Lấy danh sách tệp được chọn
    const previewContainer = document.querySelector(
        "#files-post-preview #other"
    );
    previewContainer.innerHTML = ""; // Xóa các preview cũ

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            // Tạo div hiển thị tên tệp và kích thước
            const fileInfoDiv = document.createElement("div");

            // Tạo nội dung cho div
            const fileName = document.createElement("small");
            fileName.style.overflow = "hidden"; // Ẩn phần nội dung vượt ra ngoài
            fileName.style.textOverflow = "ellipsis"; // Thêm "..." khi bị cắt
            fileName.style.whiteSpace = "nowrap"; // Ngăn dòng xuống
            fileName.style.display = "block"; // Để áp dụng hiệu ứng cho phần tử block

            // Thiết lập kích thước cho phần tử chứa tên tệp
            fileName.style.width = "100%"; // Chiều rộng tối đa cho tên tệp
            fileName.textContent = `${file.name}`;

            const fileSize = document.createElement("small");
            fileSize.textContent = `${(file.size / 1024).toFixed(2)} KB`; // Chuyển đổi kích thước sang KB

            // Tạo nút xóa
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "X";
            deleteButton.classList.add("btn", "btn-sm", "btn-secondary"); // Thêm lớp Bootstrap
            deleteButton.onclick = function () {
                fileInfoDiv.remove();
                const dataTransfer = new DataTransfer();
                Array.from(input.files).forEach((fileItem, fileIndex) => {
                    if (fileIndex !== index) {
                        dataTransfer.items.add(fileItem);
                    }
                });
                input.files = dataTransfer.files; // Cập nhật input files
            };

            // Thêm tên, kích thước và nút vào div
            fileInfoDiv.appendChild(fileName);
            fileInfoDiv.appendChild(fileSize);
            fileInfoDiv.appendChild(deleteButton);

            // Thêm div vào container preview
            previewContainer.appendChild(fileInfoDiv);
        };
        reader.readAsDataURL(file); // Đọc tệp dưới dạng URL
    });
}
function handleScrollChatHistory(e, isCanContinueRender, chatCurrent) {
    //load more when scroll
    if (e.target.scrollTop != 0) return;
    if (!isCanContinueRender) return;
    sessionStorage.setItem(
        "numberOfMessage",
        parseInt(sessionStorage.getItem("numberOfMessage")) + 10
    );
    if (chatCurrent)
        loadPartialView(
            chatCurrent,
            sessionStorage.getItem("numberOfMessage")
        );
}

function handleSubmitNewMessage(e, text = {}) {
    e.preventDefault(); // Ngăn không cho form submit bình thường
    const button = document.querySelector(".btn-send-message");
    $.ajax({
        type: "POST",
        url: e.target.action,
        data: new FormData(e.target),
        processData: false, // Ngăn jQuery xử lý dữ liệu
        contentType: false, // Ngăn jQuery đặt Content-Type
        beforeSend: function (xhr) {
            button.disabled = true;
        },
        xhr: function () {
            const xhr = new window.XMLHttpRequest();
            // Theo dõi tiến trình tải lên
            xhr.upload.addEventListener(
                "progress",
                function (event) {
                    if (event.lengthComputable) {
                        const percentComplete =
                            (event.loaded / event.total) * 100;
                        button.innerText = percentComplete.toFixed(2) + "%";
                    } else {
                        button.innerText = text.sending;
                    }
                },
                false
            );
            return xhr;
        },
        success: function (data) {
            button.innerText = text.send;
            button.disabled = false;
            document
                .querySelectorAll("#message-form input")
                .forEach((input, index, array) => {
                    if (input.getAttribute("name") != "_token")
                        input.value = "";
                });
            document.querySelector("#files-post-preview #media").innerHTML = "";
            document.querySelector("#files-post-preview #other").innerHTML = "";
        },
        error: function (xhr, status, error) {
            button.innerText = text.send;
            button.disabled = false;
            if (xhr.status === 413) {
                alert(text.contentTooLarge);
            } else {
                alert("Có lỗi xảy ra: " + error);
            }
        },
    });
}
function getNewMessage(id) {
    $.ajax({
        url: "/get-chat-item-patial-view/" + id,
        success: function (view) {
            document.querySelector("#chat-history-container").innerHTML += view;
            scrollToEndChatHistory();
        },
        error: function (e) {
            console.log(e);
        },
    });
}
function keyDownEvent(event) {
    if (!(event.key === "Enter")) return;
    document.querySelector("#message-form button").click;
}
function createChatMessage(
    isMe = false,
    time,
    content,
    id = null,
    dataId = null
) {
    const messageElement = document.createElement("li");
    messageElement.classList.add(
        "message-data",
        "d-flex",
        isMe ? "justify-content-end" : "justify-content-start",
        "align-items-center"
    );
    if (id) messageElement.id = id;
    if (dataId) messageElement.dataset.id = dataId;

    const timeElement = document.createElement("span");
    timeElement.classList.add("message-data-time");
    timeElement.textContent = time;

    const messageContentElement = document.createElement("div");
    messageContentElement.classList.add(
        "message",
        isMe ? "bg-info" : "bg-white"
    );
    messageContentElement.textContent = content;

    if (isMe) {
        messageElement.appendChild(timeElement);
        messageElement.appendChild(messageContentElement);
    } else {
        messageElement.appendChild(messageContentElement);
        messageElement.appendChild(timeElement);
    }

    document
        .querySelector("#chat-history-container")
        .appendChild(messageElement);
    document
        .querySelector("#chat-history-container")
        .appendChild(document.createElement("br"));
}
function clickMessageItem(aTag) {
    window.open(aTag.href.replace("/images_resize/", "/images/"), "_blank");
}
function filterFriends() {
    const input = document.getElementById("searchToCreateGroupInput");

    const filter = input.value.toLowerCase();
    const friendList = document.getElementById("friendList");
    const friends = friendList.getElementsByClassName("friend");

    let hasResults = false; // Biến để kiểm tra xem có kết quả hay không

    for (let i = 0; i < friends.length; i++) {
        const friendName =
            friends[i].getElementsByTagName("span")[0].textContent;
        if (friendName.toLowerCase().includes(filter)) {
            friends[i].style.display = "flex"; // Hiện nếu khớp
            hasResults = true; // Đã tìm thấy ít nhất một kết quả
        } else {
            friends[i].style.display = "none"; // Ẩn nếu không khớp
        }
    }

    friendList.querySelector(".li-no-result").style.display = hasResults
        ? "none"
        : "block";
}
