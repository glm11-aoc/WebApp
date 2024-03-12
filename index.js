$('form').submit( function (e) {
    e.preventDefault()

    grecaptcha.ready(function() {
        grecaptcha.execute('6LdwAmEpAAAAAFVUlRTHbp4bL3NFswBc5nn53Wal', { action: 'login_to_site' } ).then(function (token) {
            $.ajax({
                url: "./php/auth.php",
                type: "POST",
                data: {
                    txtUser: $('#username').val(),
                    txtPass: $('#password').val(),
                    token: token
                },
                success: function (res) {
                    if (res == "true")
                    {
                        window.location.href = "./dashboard.html";
                    }
                    else
                    {
                        alert(res);
                    }
                }
            })
        });
    });
});