function showMessageAndChatWithGroupId(groupId){

    $.ajax({
        url: "/set-session/chatCurrent/" + groupId,
        success: function (response) {
            window.open("/message", "_blank");
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });
}