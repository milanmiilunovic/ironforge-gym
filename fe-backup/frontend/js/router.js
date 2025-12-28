(function() {
    'use strict';

    const Router = {
        routes: {
            '/': 'views/home.html',
            '/login': 'views/login.html',
            '/register': 'views/register.html',
            '/dashboard': 'views/dashboard.html',
            '/classes': 'views/classes.html',
            '/trainers': 'views/trainers.html',
            '/profile': 'views/profile.html',
            '/bookings': 'views/bookings.html',
            '/admin': 'views/admin.html'
        },

        publicRoutes: ['/login', '/register'],

        init: function() {
            window.addEventListener('hashchange', () => this.handleRoute());
            window.addEventListener('load', () => this.handleRoute());
            this.attachNavListeners();
        },

        handleRoute: function() {
            const hash = window.location.hash.slice(1) || '/';

            const isAuthenticated = this.checkAuth();

            if (isAuthenticated && (hash === '/login' || hash === '/register')) {
                console.log('Already authenticated, redirecting to home');
                window.location.hash = '#/';
                return;
            }

            const route = this.routes[hash];

            if (route) {
                this.loadView(route, hash);
            } else {
                this.loadView('views/home.html', '/');
            }

            this.updateActiveNav(hash);
            this.updateNavForAuth(isAuthenticated);
        },

        checkAuth: function() {
            const token = localStorage.getItem('user_token');
            if (!token || token === 'undefined') {
                return false;
            }

            try {
                if (typeof Utils !== 'undefined' && Utils.parseJwt) {
                    const parsed = Utils.parseJwt(token);
                    if (parsed && parsed.exp) {
                        if (parsed.exp * 1000 < Date.now()) {
                            console.log('Token expired');
                            localStorage.removeItem('user_token');
                            return false;
                        }
                    }
                }
                return true;
            } catch (e) {
                console.error('Error checking token:', e);
                return false;
            }
        },

        updateNavForAuth: function(isAuthenticated) {
            const mainNav = document.getElementById('main-nav');
            const mobileNav = document.querySelector('.canvas-menu ul');


            const token = localStorage.getItem('user_token');
            const parsedToken = Utils.parseJwt(token);
            const userRole = parsedToken.user.role;


            console.log("USRROLE", userRole)

            if (!mainNav) return;

            if (!isAuthenticated) {


                mainNav.innerHTML = `
                    <li class="active"><a href="#/" class="nav-link">Home TESTs</a></li>
                    <li><a href="#/classes" class="nav-link">Classes</a></li>
                    <li><a href="#/trainers" class="nav-link">Trainers</a></li>
                    <li><a href="#/login" class="nav-link">Login</a></li>
                    <li><a href="#/register" class="nav-link">Register</a></li>
                `;

                if (mobileNav) {
                    mobileNav.innerHTML = `
                        <li><a href="#/" class="nav-link">Home</a></li>
                        <li><a href="#/classes" class="nav-link">Classes</a></li>
                        <li><a href="#/trainers" class="nav-link">Trainers</a></li>
                        <li><a href="#/login" class="nav-link">Login</a></li>
                        <li><a href="#/register" class="nav-link">Register</a></li>
                    `;
                }


                return;
            }


            if(userRole === "admin") {
                mainNav.innerHTML = `
                    <li class="active"><a href="#/" class="nav-link">Home - ADMIN</a></li>
                    <li><a href="#/classes" class="nav-link">Classes</a></li>
                    <li><a href="#/trainers" class="nav-link">Trainers</a></li>
                    <li><a href="#/profile" class="nav-link">Profile</a></li>
                    <li><a href="#/bookings" class="nav-link">Bookings</a></li>
                    <li><a href="#/admin" class="nav-link">Admin</a></li>
                    
                    <li><a href="#" onclick="UserService.logout(); return false;" style="color: #f36100;">Logout</a></li>
                `;

                if (mobileNav) {
                    mobileNav.innerHTML = `
                        <li><a href="#/" class="nav-link">Home</a></li>
                        <li><a href="#/classes" class="nav-link">Classes</a></li>
                        <li><a href="#/trainers" class="nav-link">Trainers</a></li>
                        <li><a href="#/profile" class="nav-link">Profile</a></li>
                        <li><a href="#/bookings" class="nav-link">Bookings</a></li>
                        
                        <li><a href="#" onclick="UserService.logout(); return false;" style="color: #f36100;">Logout</a></li>
                    `;
                }

                return;
            }



            mainNav.innerHTML = `
                    <li class="active"><a href="#/" class="nav-link">Home</a></li>
                    <li><a href="#/classes" class="nav-link">Classes</a></li>
                    <li><a href="#/trainers" class="nav-link">Trainers</a></li>
                    <li><a href="#/profile" class="nav-link">Profile</a></li>
                    <li><a href="#/bookings" class="nav-link">Bookings</a></li>
                    <li><a href="#" onclick="UserService.logout(); return false;" style="color: #f36100;">Logout</a></li>
                `;

            if (mobileNav) {
                mobileNav.innerHTML = `
                        <li><a href="#/" class="nav-link">Home</a></li>
                        <li><a href="#/classes" class="nav-link">Classes</a></li>
                        <li><a href="#/trainers" class="nav-link">Trainers</a></li>
                        <li><a href="#/profile" class="nav-link">Profile</a></li>
                        <li><a href="#/bookings" class="nav-link">Bookings</a></li>
                        <li><a href="#" onclick="UserService.logout(); return false;" style="color: #f36100;">Logout</a></li>
                    `;
            }

        },

        loadView: function(viewPath, route) {
            const contentDiv = document.getElementById('app-content');
            
            contentDiv.innerHTML = '<div class="loader" style="margin: 100px auto; text-align: center; color: #f36100;"><i class="fa fa-spinner fa-spin" style="font-size: 48px;"></i></div>';

            fetch(viewPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('View not found');
                    }
                    return response.text();
                })
                .then(html => {
                    // Use a temporary div to parse the fetched HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    // Clear the actual content div
                    contentDiv.innerHTML = '';
                    
                    // Append all child nodes from the temporary div, which separates scripts from other HTML
                    Array.from(tempDiv.childNodes).forEach(node => {
                        // Scripts inserted this way won't execute, so we handle them separately
                        if (node.nodeName !== 'SCRIPT') {
                            contentDiv.appendChild(node.cloneNode(true));
                        }
                    });

                    // Find all script tags in the temporary div
                    const scripts = tempDiv.querySelectorAll('script');
                    
                    // Create new script elements and append them to the content div to execute them
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        
                        // Copy all attributes from the old script to the new one
                        Array.from(oldScript.attributes).forEach(attr => {
                            newScript.setAttribute(attr.name, attr.value);
                        });
                        
                        // Copy the inline script's content
                        newScript.textContent = oldScript.textContent;
                        
                        // Append the new script to the content area, which forces it to execute
                        contentDiv.appendChild(newScript);
                    });

                    window.scrollTo(0, 0);
                    
                    // Initialize page-specific logic after a brief delay
                    setTimeout(() => {
                        if (route === '/login') {
                            this.initLoginPage();
                        } else if (route === '/register') {
                            this.initRegisterPage();
                        }
                    }, 100);
                    
                    this.reinitPlugins();
                    this.closeMobileMenu();
                })
                .catch(error => {
                    console.error('Error loading view:', error);
                    contentDiv.innerHTML = `
                        <div style="text-align: center; padding: 100px 20px; background: #151515;">
                            <h2 style="color: #f36100;">Page Not Found</h2>
                            <p style="color: #c4c4c4;">The page you're looking for doesn't exist.</p>
                            <a href="#/" class="primary-btn nav-link" style="margin-top: 20px; display: inline-block; padding: 15px 40px; background: #f36100; color: #fff; text-decoration: none;">Go Home</a>
                        </div>
                    `;
                });
        },

        initLoginPage: function() {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                console.log('Initializing login form');

                const newForm = loginForm.cloneNode(true);
                loginForm.parentNode.replaceChild(newForm, loginForm);

                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Login form submitted');

                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;

                    console.log('Email:', email);

                    if (typeof UserService !== 'undefined') {
                        UserService.login({ email, password });
                    } else {
                        console.error('UserService not found');
                        alert('Login service not available. Please check console.');
                    }
                });
            } else {
                console.error('Login form not found');
            }
        },

        initRegisterPage: function() {
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                console.log('Initializing register form');

                const newForm = registerForm.cloneNode(true);
                registerForm.parentNode.replaceChild(newForm, registerForm);

                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Register form submitted');

                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;

                    if (password !== confirmPassword) {
                        alert('Passwords do not match!');
                        return;
                    }

                    if (password.length < 6) {
                        alert('Password must be at least 6 characters long!');
                        return;
                    }

                    const formData = {
                        full_name: document.getElementById('full_name').value,
                        email: document.getElementById('email').value,
                        password: password
                    };


                    console.log('Registration data:', formData);

                    if (typeof UserService !== 'undefined' && typeof UserService.register === 'function') {
                        UserService.register(formData);
                    } else {
                        console.error('UserService.register not found');
                        alert('Registration service not available. Please check console.');
                    }
                });
            } else {
                console.error('Register form not found');
            }
        },

        attachNavListeners: function() {
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('nav-link')) {
                    e.preventDefault();
                    const href = e.target.getAttribute('href');
                    if (href && href.startsWith('#/')) {
                        window.location.hash = href.slice(1);
                    }
                }
            });
        },

        updateActiveNav: function(currentRoute) {
            const navLinks = document.querySelectorAll('.nav-menu ul li, .canvas-menu ul li');
            navLinks.forEach(li => li.classList.remove('active'));

            const activeLinks = document.querySelectorAll(`a[href="#${currentRoute}"]`);
            activeLinks.forEach(link => {
                if (link.parentElement && link.parentElement.tagName === 'LI') {
                    link.parentElement.classList.add('active');
                }
            });
        },

        closeMobileMenu: function() {
            const menuWrapper = document.querySelector('.offcanvas-menu-wrapper');
            const overlay = document.querySelector('.offcanvas-menu-overlay');

            if (menuWrapper) {
                menuWrapper.classList.remove('show-offcanvas-menu-wrapper');
            }
            if (overlay) {
                overlay.classList.remove('active');
            }
        },

        reinitPlugins: function() {
            if (typeof $ !== 'undefined') {
                $('.set-bg').each(function() {
                    var bg = $(this).data('setbg');
                    $(this).css('background-image', 'url(' + bg + ')');
                });

                if ($.fn.owlCarousel) {
                    $('.owl-carousel').each(function() {
                        $(this).owlCarousel('destroy');
                    });

                    var hero_s = $(".hs-slider");
                    if (hero_s.length) {
                        hero_s.owlCarousel({
                            loop: true,
                            margin: 0,
                            nav: true,
                            items: 1,
                            dots: false,
                            animateOut: 'fadeOut',
                            animateIn: 'fadeIn',
                            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                            smartSpeed: 1200,
                            autoHeight: false,
                            autoplay: true
                        });
                    }

                    var ts_slider = $(".ts-slider");
                    if (ts_slider.length) {
                        ts_slider.owlCarousel({
                            loop: true,
                            margin: 0,
                            items: 3,
                            dots: true,
                            dotsEach: 2,
                            smartSpeed: 1200,
                            autoHeight: false,
                            autoplay: true,
                            responsive: {
                                320: { items: 1 },
                                768: { items: 2 },
                                992: { items: 3 }
                            }
                        });
                    }
                }
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Router.init());
    } else {
        Router.init();
    }

    window.GymRouter = Router;

})();