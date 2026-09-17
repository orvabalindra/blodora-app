import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Password Show / Hide
    |--------------------------------------------------------------------------
    */

    const passwordToggles = document.querySelectorAll(
        '[data-password-toggle]'
    );

    passwordToggles.forEach(function (toggle) {

        toggle.addEventListener('click', function () {

            const inputId = this.dataset.passwordToggle;
            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            this.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';

            this.setAttribute(
                'aria-label',
                isPassword
                    ? 'Sembunyikan password'
                    : 'Tampilkan password'
            );
        });

    });


    /*
    |--------------------------------------------------------------------------
    | Submit Loading
    |--------------------------------------------------------------------------
    */

    const authForms = document.querySelectorAll('.auth-form');

    authForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            /*
             * Jangan mengaktifkan loading jika browser
             * menemukan input HTML yang tidak valid.
             */
            if (!form.checkValidity()) {
                return;
            }

            const submitButton = form.querySelector(
                'button[type="submit"]'
            );

            if (!submitButton) {
                return;
            }

            const normalText = submitButton.querySelector('.btn-text');
            const loadingText = submitButton.querySelector('.btn-loading');

            submitButton.disabled = true;
            submitButton.classList.add('is-loading');

            if (normalText) {
                normalText.classList.add('d-none');
            }

            if (loadingText) {
                loadingText.classList.remove('d-none');
            }

        });

    });

});

document.addEventListener("DOMContentLoaded", function () {

    const navbar = document.querySelector(".blodora-navbar");

    const navLinks = document.querySelectorAll(
        ".blodora-navbar .nav-link"
    );

    const sections = document.querySelectorAll(
        "section[id]"
    );


    /* =========================
       NAVBAR SCROLL EFFECT
    ========================= */

    window.addEventListener("scroll", function () {

        if (window.scrollY > 50) {

            navbar.classList.add("scrolled");

        } else {

            navbar.classList.remove("scrolled");

        }


        /* =========================
           ACTIVE MENU
        ========================= */

        let currentSection = "";

        sections.forEach(function (section) {

            const sectionTop = section.offsetTop - 150;

            const sectionHeight = section.offsetHeight;

            if (
                window.scrollY >= sectionTop &&
                window.scrollY < sectionTop + sectionHeight
            ) {

                currentSection = section.getAttribute("id");

            }

        });


        navLinks.forEach(function (link) {

            link.classList.remove("active");

            const href = link.getAttribute("href");

            if (href === "#" + currentSection) {

                link.classList.add("active");

            }

        });

    });

});