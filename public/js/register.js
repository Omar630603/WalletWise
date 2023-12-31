$(document).ready(function () {
    $("#username").blur(function () {
        var username = $(this).val();
        if (username != "") {
            $.ajax({
                url: "/check-username-or-email",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: { username: username },
                success: function (data) {
                    if (data.available) {
                        $("#usernameCheckCorrect").show();
                        $("#usernameCheckWrong").hide();
                    } else {
                        $("#usernameCheckCorrect").hide();
                        $("#usernameCheckWrong").show();
                    }
                },
            });
        }
    });

    $("#email").blur(function () {
        var email = $(this).val();
        if (email != "") {
            $.ajax({
                url: "/check-username-or-email",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                data: { email: email },
                success: function (data) {
                    if (data.available) {
                        $("#emailCheckCorrect").show();
                        $("#emailCheckWrong").hide();
                    } else {
                        $("#emailCheckCorrect").hide();
                        $("#emailCheckWrong").show();
                    }
                },
            });
        }
    });

    const googleBTN = document.getElementById("googleBTN");
    googleBTN.addEventListener("click", () => {
        window.location.href = "{{ route('auth.google') }}";
    });
});
