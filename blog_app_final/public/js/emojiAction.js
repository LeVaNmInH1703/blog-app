let holdTimeout, leaveTimeout;
const emojisContainer = document.querySelector("#emojis_container");
function handleMouseEnterButtonLike(e) {
    e.stopPropagation();
    const button = e.target.closest(".btn_like");
    clearTimeout(leaveTimeout);
    holdTimeout = setTimeout(() => {
        // show emojis_container
        if (button.disabled) return;
        button
            .querySelector(".btn_like_text")
            .insertAdjacentElement("afterend", emojisContainer);
        emojisContainer.style.display = "flex";
        emojisContainer.setAttribute(
            "data-model-id",
            button.getAttribute("data-id")
        );
    }, 500);
}
function handleClickButtonLike(e) {
    e.stopPropagation();
    const button = e.target.closest(".btn_like");
    if (button.disabled) return;
    emojisContainer.style.display = "none";
    createEmoji(
        button.getAttribute("data-name"),
        button.getAttribute("data-id"),
        "love"
    );
    //disable button like
    button.disabled = true;
    button.style.cursor = "wait";
}
function handleMouseLeaveButtonLike(e) {
    e.stopPropagation();

    leaveTimeout = setTimeout(() => {
        clearTimeout(holdTimeout);
        emojisContainer.style.display = "none";
    }, 200);
}

function handleClickATag(e) {
    e.stopPropagation();
    const aTag = e.target.closest("a");
    document.querySelector("#emojis_container").style.display = "none";
    const button = e.target.closest("button");
    createEmoji(
        button.getAttribute("data-name"),
        button.getAttribute("data-id"),
        aTag.getAttribute("data-emoji_name")
    );
    //disable button like
    button.disabled = true;
    button.style.cursor = "wait";
}

function lockButton(button, time) {
    button.disabled = true;
    setTimeout(() => {
        button.disabled = false;
    }, time);
}
function createEmoji(modelName, modelId, emojiName) {
    if (modelId != null) {
        let formData = new FormData();
        formData.append("model_name", modelName);
        formData.append("model_id", modelId);
        formData.append("emoji_name", emojiName);

        $.ajax({
            url: "/emoji",
            type: "POST",
            data: formData,
            processData: false, // Không xử lý dữ liệu
            contentType: false, // Không thiết lập kiểu Content-Type
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // CSRF token cho Laravel
            },
            success: function (response) {
                // console.log(response);
                let model = document.querySelector(
                    `.model_wrap[data-id='${modelId}'][data-name='${modelName}']`
                );
                model.querySelector(".count_emoji").innerHTML =
                    response.countEmojiInnerHTML;
                model.querySelector(".btn_like_text").innerHTML =
                    response.btnLikeTextInnerHTML;
                //enable button like
                model.querySelector(".btn_like").disabled = false;
                model.querySelector(".btn_like").style.cursor = "pointer";
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    }
}
