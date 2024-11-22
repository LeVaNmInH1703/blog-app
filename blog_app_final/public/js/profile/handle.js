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
    document
        .querySelector(".user-info-name")
        .addEventListener("blur", function (e) {
            e.target.setAttribute("readonly", true);
            setTimeout(() => {
                e.target.classList.remove("input-active");
            }, 1000);
        });
});
let isChanged = false;

function changeName(input) {
    isChanged = input.value != "{{ Auth::user()->name }}";
    const button = document.querySelector(".btn-edit-name");
    button.type = isChanged ? "submit" : "button";
    if (isChanged) {
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-outline-primary");
        button.querySelector("i").classList.remove("fa-pen");
        button.querySelector("i").classList.add("fa-check");
    } else {
        button.classList.remove("btn-outline-primary");
        button.classList.add("btn-outline-secondary");
        button.querySelector("i").classList.remove("fa-check");
        button.querySelector("i").classList.add("fa-pen");
    }
}

function showEditName() {
    const input = document.querySelector(".user-info-name");
    if (input.hasAttribute("readonly")) {
        input.removeAttribute("readonly");
        input.focus();
        input.select();
        input.setSelectionRange(input.value.length, input.value.length);
        input.classList.add("input-active");
    }
}

function solveEditName() {
    if (isChanged) return;
    showEditName();
}

//modal
function handleDragOver(event) {
    const dropArea = document.getElementById("drop-area");
    event.preventDefault();
    dropArea.style.borderColor = "#0056b3";
}
function handleDragLeave(event) {
    const dropArea = document.getElementById("drop-area");
    event.preventDefault();
    dropArea.style.borderColor = "#0087F7";
}
function handleDrop(event) {
    const fileAvatar = document.getElementById("fileAvatar");
    const dropArea = document.getElementById("drop-area");
    event.preventDefault();
    dropArea.style.borderColor = "#0087F7";
    const files = event.dataTransfer.files;
    if (files.length) {
        fileAvatar.files = files;
        handleFiles({
            target: fileAvatar,
        });
    }
}

function handleFiles(event) {
    const previewImg = document.getElementById("preview-img");
    const files = event.target.files;
    if (files.length) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = "block";
        };
        reader.readAsDataURL(files[0]);
    }
}
