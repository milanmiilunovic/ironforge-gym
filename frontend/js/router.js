

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
            '/bookings': 'views/bookings.html'
        },

        init: function() {
            
            window.addEventListener('hashchange', () => this.handleRoute());
            
            window.addEventListener('load', () => this.handleRoute());

            this.attachNavListeners();
        },

        handleRoute: function() {
            const hash = window.location.hash.slice(1) || '/';
            const route = this.routes[hash];

            if (route) {
                this.loadView(route, hash);
            } else {
                this.loadView('views/home.html', '/');
            }

            
            this.updateActiveNav(hash);
        },

        loadView: function(viewPath, route) {
            const contentDiv = document.getElementById('app-content');
            
            contentDiv.innerHTML = '<div class="loader" style="margin: 100px auto;"></div>';

            fetch(viewPath)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('View not found');
                    }
                    return response.text();
                })
                .then(html => {
                    contentDiv.innerHTML = html;
                    
                    
                    window.scrollTo(0, 0);
                    
                    
                    this.reinitPlugins();
                    
                    
                    this.closeMobileMenu();
                })
                .catch(error => {
                    console.error('Error loading view:', error);
                    contentDiv.innerHTML = `
                        <div style="text-align: center; padding: 100px 20px; background: #151515;">
                            <h2 style="color: #f36100;">Page Not Found</h2>
                            <p style="color: #c4c4c4;">The page you're looking for doesn't exist.</p>
                            <a href="#/" class="primary-btn" style="margin-top: 20px;">Go Home</a>
                        </div>
                    `;
                });
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
            
            const navLinks = document.querySelectorAll('.nav-menu ul li');
            navLinks.forEach(li => li.classList.remove('active'));

            
            const activeLink = document.querySelector(`.nav-menu a[href="#${currentRoute}"]`);
            if (activeLink) {
                activeLink.parentElement.classList.add('active');
            }
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

})();