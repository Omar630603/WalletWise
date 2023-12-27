// check if the username is valid and available

$(document).ready(function () {
    $("#username").blur(function () {
        var username = $(this).val();
        if (username != "") {
            $.ajax({
                url: "/check-username",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: { username: username },
                success: function (data) {
                    if (data.available) {
                    } else {
                    }
                },
            });
        }
    });
});
