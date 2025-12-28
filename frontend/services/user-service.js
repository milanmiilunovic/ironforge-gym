let UserService = {
    init: function () {
        console.log('UserService initialized');
        var token = this.getToken();
        if (token && token !== 'undefined') {
            const parsed = Utils.parseJwt(token);
            if (parsed && parsed.exp && parsed.exp * 1000 > Date.now()) {
                console.log('User is logged in:', parsed);
            } else {
                console.log('Token expired, clearing');
                this.clearToken();
            }
        }
    },

    getToken: function() {
        return localStorage.getItem("user_token");
    },

    setToken: function(token) {
        localStorage.setItem("user_token", token);
    },

    clearToken: function() {
        localStorage.removeItem("user_token");
    },

    login: function (entity) {
        console.log('UserService.login called with:', entity);

        const submitBtn = document.querySelector('#loginForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'LOGGING IN...';
        }


        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {
                console.log('Login successful, response:', result);

                if (result.token) {
                    UserService.setToken(result.token);
                    console.log('Token stored:', result.token);

                    alert('Login successful! Welcome back.');

                    window.location.hash = '#/';
                    if (window.GymRouter) {
                        window.GymRouter.handleRoute();
                    }
                } else {
                    console.error('No token in response');
                    alert('Login failed: No token received');
                }

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'LOGIN';
                }
            },
            error: function(xhr) {
                console.error('Login error:', xhr);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);

                let errorMessage = 'Login failed. Please check your credentials.';

                if (xhr.status === 401) {
                    errorMessage = 'Invalid email or password.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                } else if (xhr.status === 0) {
                    errorMessage = 'Cannot connect to server. Please check if backend is running.';
                }

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                alert(errorMessage);

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'LOGIN';
                }
            }
        });
    },

    register: function(entity) {
        console.log('UserService.register called with:', entity);

        const submitBtn = document.querySelector('#registerForm button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'CREATING ACCOUNT...';
        }

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {
                console.log('Registration successful:', result);

                alert('Registration successful! Please login with your credentials.');

                window.location.hash = '#/login';

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'CREATE ACCOUNT';
                }
            },
            error: function(xhr) {
                console.error('Registration error:', xhr);

                let errorMessage = 'Registration failed. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                alert(errorMessage);

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'CREATE ACCOUNT';
                }
            }
        });
    },

    logout: function () {
        console.log('Logging out');
        this.clearToken();
        alert('You have been logged out.');
        window.location.hash = '#/login';

        if (window.GymRouter) {
            window.GymRouter.handleRoute();
        }
    },

    isAuthenticated: function() {
        const token = this.getToken();
        if (!token) return false;

        const parsed = Utils.parseJwt(token);
        if (!parsed) return false;

        if (parsed.exp && parsed.exp * 1000 < Date.now()) {
            this.clearToken();
            return false;
        }

        return true;
    },

    getCurrentUser: function() {
        const token = this.getToken();
        if (!token) return null;

        const parsed = Utils.parseJwt(token);
        return parsed ? parsed.user : null;
    },

    getUserInfo: function (){
        const token = this.getToken();
        if (!token) return;

        const parsedToken = Utils.parseJwt(token);
        const userId = parsedToken.user.user_id; // Ensure your JWT structure matches this

        fetch(Constants.PROJECT_BASE_URL + 'user/info/' + userId)
            .then((res) => res.json())
            .then((data) => {
                console.log("User Info Received:", data);

                // 1. Select Elements by correct HTML IDs
                const email_input = document.getElementById('email-form');
                const full_name_input = document.getElementById('full-name-form');
                const full_name_text = document.getElementById('full-name-h4');
                const membership_text = document.getElementById('membership-date');

                // 2. Set Values correctly

                // Inputs use .value
                if (email_input) email_input.value = data.email;
                if (full_name_input) full_name_input.value = data.full_name;

                // Text elements (h4, span, div) use .innerText or .innerHTML
                if (full_name_text) full_name_text.innerText = data.full_name;
                if (membership_text) membership_text.innerText = data.join_date;
            })
            .catch(err => console.error("Error loading user info:", err));
    },

    generateMenuItems: function () {

        if (!token) {
            console.log('No token found, user not logged in');
            return;
        }

        const user = Utils.parseJwt(token)?.user;

        if (!user || !user.role) {
            console.log('Invalid token or no user role');
            return;
        }

        console.log('Generating menu for user:', user);


    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => UserService.init());
} else {
    UserService.init();
}

window.UserService = UserService;