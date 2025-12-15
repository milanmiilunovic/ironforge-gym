let UserService = {
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) window.location.replace("index.html");

        $("#login-form").validate({
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            }
        });
    },
    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                localStorage.setItem("user_token", result.token);
                window.location.replace("index.html");
            },
            error: function (xhr) {
                toastr.error(xhr?.responseJSON?.message || 'Error');
            }
        });
    },
    logout: function () {
        localStorage.clear();
        window.location.replace("login.html");
    },
    generateMenuItems: function () {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;

        if (!user || !user.role) { window.location.replace("login.html"); return; }

        let nav = "", main = "";
        if (user.role === Constants.USER_ROLE) {
            nav = `<li class="nav-item"><a href="#students">Students</a></li>
                   <li><button class="btn btn-primary" onclick="UserService.logout()">Logout</button></li>`;
            main = `<section id="students" data-load="pages/students.html"></section>`;
        } else if (user.role === Constants.ADMIN_ROLE) {
            nav = `<li class="nav-item"><a href="#students">Students</a></li>
                   <li><button class="btn btn-primary" onclick="UserService.logout()">Logout</button></li>`;
            main = `<section id="students" data-load="pages/students.html"></section>`;
        }
        $("#tabs").html(nav);
        $("#spapp").html(main);
    }
};
