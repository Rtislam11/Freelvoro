document.addEventListener("DOMContentLoaded", function () {

    const form =
        document.getElementById("loginForm");

    const email =
        document.getElementById("email");

    const password =
        document.getElementById("password");

    const passwordToggle =
        document.getElementById("passwordToggle");

    const closeError =
        document.getElementById("closeError");


    if (password && passwordToggle) {

        passwordToggle.addEventListener(
            "click",
            function () {

                if (password.type === "password") {

                    password.type = "text";

                    passwordToggle.textContent = "🙈";

                    passwordToggle.setAttribute(
                        "aria-label",
                        "Hide password"
                    );

                } else {

                    password.type = "password";

                    passwordToggle.textContent = "👁";

                    passwordToggle.setAttribute(
                        "aria-label",
                        "Show password"
                    );

                }

            }
        );

    }


    function clearFieldError(field) {

        const group =
            field.closest(".form-group");

        if (!group) return;

        group.classList.remove(
            "has-error"
        );

        const error =
            group.querySelector(".js-error");

        if (error) {

            error.remove();

        }

    }


    function showFieldError(
        field,
        message
    ) {

        const group =
            field.closest(".form-group");

        if (!group) return;

        group.classList.add(
            "has-error"
        );


        let error =
            group.querySelector(".js-error");


        if (!error) {

            error =
                document.createElement("div");

            error.className =
                "field-error js-error";

            group.appendChild(
                error
            );

        }


        error.innerHTML =
            "<span>ⓘ</span>" +
            message;

    }


    function showTopError() {

        let topError =
            document.querySelector(".top-error");


        if (!topError) {

            topError =
                document.createElement("div");

            topError.className =
                "top-error";


            topError.innerHTML = `
                <span class="error-icon">!</span>
                <span>
                    Please fix the errors below.
                </span>
                <button type="button">
                    ×
                </button>
            `;


            form.parentNode.insertBefore(
                topError,
                form
            );


            topError
                .querySelector("button")
                .addEventListener(
                    "click",
                    function () {

                        topError.remove();

                    }
                );

        }

    }


    if (form) {

        form.addEventListener(
            "submit",
            function (event) {

                let valid = true;


                clearFieldError(email);

                clearFieldError(password);


                if (email.value.trim() === "") {

                    showFieldError(
                        email,
                        "This field is required"
                    );

                    valid = false;

                } else if (
                    !isValidEmail(
                        email.value.trim()
                    )
                ) {

                    showFieldError(
                        email,
                        "Enter a valid email address"
                    );

                    valid = false;

                }


                if (password.value === "") {

                    showFieldError(
                        password,
                        "This field is required"
                    );

                    valid = false;

                } else if (
                    password.value.length < 8
                ) {

                    showFieldError(
                        password,
                        "Password must be at least 8 characters"
                    );

                    valid = false;

                }


                if (!valid) {

                    event.preventDefault();

                    showTopError();


                    if (
                        email.value.trim() === ""
                    ) {

                        email.focus();

                    } else {

                        password.focus();

                    }

                }

            }
        );

    }


    function isValidEmail(value) {

        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            .test(value);

    }


    if (email) {

        email.addEventListener(
            "input",
            function () {

                clearFieldError(email);

            }
        );

    }


    if (password) {

        password.addEventListener(
            "input",
            function () {

                clearFieldError(password);

            }
        );

    }


    if (closeError) {

        closeError.addEventListener(
            "click",
            function () {

                const error =
                    document.querySelector(
                        ".top-error"
                    );

                if (error) {

                    error.remove();

                }

            }
        );

    }


    const googleBtn =
        document.getElementById("googleBtn");

    const appleBtn =
        document.getElementById("appleBtn");


    if (googleBtn) {

        googleBtn.addEventListener(
            "click",
            function () {

                alert(
                    "Google login will be connected later."
                );

            }
        );

    }


    if (appleBtn) {

        appleBtn.addEventListener(
            "click",
            function () {

                alert(
                    "Apple login will be connected later."
                );

            }
        );

    }

});