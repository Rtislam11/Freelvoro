document.addEventListener(
    "DOMContentLoaded",
    function () {

        const password =
            document.getElementById(
                "password"
            );

        const passwordToggle =
            document.getElementById(
                "passwordToggle"
            );

        if (
            password &&
            passwordToggle
        ) {

            passwordToggle.addEventListener(
                "click",
                function () {

                    if (
                        password.type ===
                        "password"
                    ) {

                        password.type =
                            "text";

                        passwordToggle.textContent =
                            "🙈";

                        passwordToggle.setAttribute(
                            "aria-label",
                            "Hide password"
                        );

                    } else {

                        password.type =
                            "password";

                        passwordToggle.textContent =
                            "👁";

                        passwordToggle.setAttribute(
                            "aria-label",
                            "Show password"
                        );

                    }

                }
            );

        }



        const countryDropdown =
            document.getElementById(
                "countryDropdown"
            );

        const countrySelected =
            document.getElementById(
                "countrySelected"
            );

        const countryMenu =
            document.getElementById(
                "countryMenu"
            );

        const countrySearch =
            document.getElementById(
                "countrySearch"
            );

        const countryList =
            document.getElementById(
                "countryList"
            );

        const countryValue =
            document.getElementById(
                "countryValue"
            );

        const selectedCountry =
            document.getElementById(
                "selectedCountry"
            );

        if (
            countryDropdown &&
            countrySelected &&
            countryMenu &&
            countrySearch &&
            countryList &&
            countryValue &&
            selectedCountry
        ) {

            countrySelected.addEventListener(
                "click",
                function (event) {

                    event.stopPropagation();

                    countryDropdown.classList.toggle(
                        "open"
                    );

                    if (
                        countryDropdown.classList.contains(
                            "open"
                        )
                    ) {

                        setTimeout(
                            function () {

                                countrySearch.focus();

                            },
                            50
                        );

                    } else {

                        countrySearch.value =
                            "";

                        filterCountries(
                            ""
                        );

                    }

                }
            );


            const countryOptions =
                countryList.querySelectorAll(
                    ".country-option"
                );

            countryOptions.forEach(
                function (option) {

                    option.addEventListener(
                        "click",
                        function (event) {

                            event.stopPropagation();

                            const country =
                                this.dataset.country;

                            countryValue.value =
                                country;

                            selectedCountry.textContent =
                                country;

                            countryOptions.forEach(
                                function (item) {

                                    item.classList.remove(
                                        "selected"
                                    );

                                    const check =
                                        item.querySelector(
                                            ".check"
                                        );

                                    if (check) {

                                        check.textContent =
                                            "";

                                    }

                                }
                            );

                            this.classList.add(
                                "selected"
                            );

                            const currentCheck =
                                this.querySelector(
                                    ".check"
                                );

                            if (
                                currentCheck
                            ) {

                                currentCheck.textContent =
                                    "✓";

                            }

                            countrySelected.classList.remove(
                                "input-error"
                            );

                            const countryGroup =
                                countryDropdown.closest(
                                    ".form-group"
                                );

                            if (
                                countryGroup
                            ) {

                                countryGroup.classList.remove(
                                    "has-error"
                                );

                                const error =
                                    countryGroup.querySelector(
                                        ".js-error"
                                    );

                                if (error) {

                                    error.remove();

                                }

                            }

                            countryDropdown.classList.remove(
                                "open"
                            );

                            countrySearch.value =
                                "";

                            filterCountries(
                                ""
                            );

                        }
                    );

                }
            );


            countrySearch.addEventListener(
                "input",
                function () {

                    filterCountries(
                        this.value
                            .trim()
                            .toLowerCase()
                    );

                }
            );


            function filterCountries(
                searchText
            ) {

                const options =
                    countryList.querySelectorAll(
                        ".country-option"
                    );

                options.forEach(
                    function (option) {

                        const countryName =
                            option.dataset.country
                                .toLowerCase();

                        if (
                            countryName.includes(
                                searchText
                            )
                        ) {

                            option.style.display =
                                "flex";

                        } else {

                            option.style.display =
                                "none";

                        }

                    }
                );

            }


            document.addEventListener(
                "click",
                function (event) {

                    if (
                        !countryDropdown.contains(
                            event.target
                        )
                    ) {

                        countryDropdown.classList.remove(
                            "open"
                        );

                        countrySearch.value =
                            "";

                        filterCountries(
                            ""
                        );

                    }

                }
            );


            document.addEventListener(
                "keydown",
                function (event) {

                    if (
                        event.key ===
                        "Escape"
                    ) {

                        countryDropdown.classList.remove(
                            "open"
                        );

                        countrySearch.value =
                            "";

                        filterCountries(
                            ""
                        );

                    }

                }
            );

        }



        const form =
            document.getElementById(
                "signupForm"
            );

        if (!form) {
            return;
        }


        const firstName =
            document.getElementById(
                "firstName"
            );

        const lastName =
            document.getElementById(
                "lastName"
            );

        const email =
            document.getElementById(
                "email"
            );

        const passwordField =
            document.getElementById(
                "password"
            );

        const terms =
            document.getElementById(
                "terms"
            );


        function removeError(
            element
        ) {

            if (!element) {
                return;
            }

            element.classList.remove(
                "input-error"
            );

            element.removeAttribute(
                "aria-invalid"
            );

            const parent =
                element.closest(
                    ".form-group"
                );

            if (parent) {

                const error =
                    parent.querySelector(
                        ".js-error"
                    );

                if (error) {
                    error.remove();
                }

                parent.classList.remove(
                    "has-error"
                );

            }

        }


        function showError(
            element,
            message
        ) {

            if (!element) {
                return;
            }

            removeError(
                element
            );

            element.classList.add(
                "input-error"
            );

            element.setAttribute(
                "aria-invalid",
                "true"
            );

            const parent =
                element.closest(
                    ".form-group"
                );

            if (!parent) {
                return;
            }

            parent.classList.add(
                "has-error"
            );

            const error =
                document.createElement(
                    "div"
                );

            error.className =
                "error-message js-error";

            error.textContent =
                message;

            parent.appendChild(
                error
            );

        }


        function showTermsError(
            message
        ) {

            const termsWrapper =
                terms.closest(
                    ".terms-wrapper"
                );

            if (!termsWrapper) {
                return;
            }

            const oldError =
                termsWrapper.querySelector(
                    ".terms-error.js-error"
                );

            if (oldError) {
                oldError.remove();
            }

            terms.classList.add(
                "input-error"
            );

            terms.setAttribute(
                "aria-invalid",
                "true"
            );

            const error =
                document.createElement(
                    "div"
                );

            error.className =
                "terms-error js-error";

            error.textContent =
                message;

            termsWrapper.appendChild(
                error
            );

        }


        function clearErrors() {

            form
                .querySelectorAll(
                    ".js-error"
                )
                .forEach(
                    function (error) {

                        error.remove();

                    }
                );

            form
                .querySelectorAll(
                    ".input-error"
                )
                .forEach(
                    function (element) {

                        element.classList.remove(
                            "input-error"
                        );

                        element.removeAttribute(
                            "aria-invalid"
                        );

                    }
                );

            form
                .querySelectorAll(
                    ".has-error"
                )
                .forEach(
                    function (element) {

                        element.classList.remove(
                            "has-error"
                        );

                    }
                );

        }


        form.addEventListener(
            "submit",
            function (event) {

                let valid =
                    true;

                clearErrors();


                if (
                    !firstName.value.trim()
                ) {

                    showError(
                        firstName,
                        "First name is required"
                    );

                    valid =
                        false;
                }


                if (
                    !lastName.value.trim()
                ) {

                    showError(
                        lastName,
                        "Last name is required"
                    );

                    valid =
                        false;
                }


                const emailValue =
                    email.value.trim();

                if (!emailValue) {

                    showError(
                        email,
                        "Email address is required"
                    );

                    valid =
                        false;

                } else {

                    const emailPattern =
                        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (
                        !emailPattern.test(
                            emailValue
                        )
                    ) {

                        showError(
                            email,
                            "Enter a valid email address"
                        );

                        valid =
                            false;
                    }

                }


                if (
                    !passwordField.value
                ) {

                    showError(
                        passwordField,
                        "Password is required"
                    );

                    valid =
                        false;

                } else if (
                    passwordField.value.length < 8
                ) {

                    showError(
                        passwordField,
                        "Password must be at least 8 characters"
                    );

                    valid =
                        false;
                }


                if (
                    !countryValue.value
                ) {

                    countrySelected.classList.add(
                        "input-error"
                    );

                    countrySelected.setAttribute(
                        "aria-invalid",
                        "true"
                    );

                    const countryGroup =
                        document.getElementById(
                            "countryGroup"
                        );

                    countryGroup.classList.add(
                        "has-error"
                    );

                    const error =
                        document.createElement(
                            "div"
                        );

                    error.className =
                        "error-message js-error";

                    error.textContent =
                        "Please select a country";

                    countryGroup.appendChild(
                        error
                    );

                    valid =
                        false;
                }


                if (
                    !terms.checked
                ) {

                    showTermsError(
                        "Please accept the Terms of Service before continuing"
                    );

                    valid =
                        false;
                }


                if (!valid) {

                    event.preventDefault();

                    const firstError =
                        form.querySelector(
                            ".input-error"
                        );

                    if (firstError) {

                        firstError.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });

                    }

                    return;
                }

            }
        );


        [
            firstName,
            lastName,
            email,
            passwordField
        ].forEach(
            function (element) {

                if (!element) {
                    return;
                }

                element.addEventListener(
                    "input",
                    function () {

                        removeError(
                            element
                        );

                    }
                );

                element.addEventListener(
                    "change",
                    function () {

                        removeError(
                            element
                        );

                    }
                );

            }
        );


        if (terms) {

            terms.addEventListener(
                "change",
                function () {

                    if (
                        terms.checked
                    ) {

                        terms.classList.remove(
                            "input-error"
                        );

                        terms.removeAttribute(
                            "aria-invalid"
                        );

                        const termsWrapper =
                            terms.closest(
                                ".terms-wrapper"
                            );

                        if (termsWrapper) {

                            const error =
                                termsWrapper.querySelector(
                                    ".terms-error.js-error"
                                );

                            if (error) {
                                error.remove();
                            }

                        }

                    }

                }
            );

        }


        const passwordStrength =
            document.getElementById(
                "passwordStrength"
            );

        if (
            passwordField &&
            passwordStrength
        ) {

            passwordField.addEventListener(
                "input",
                function () {

                    const value =
                        passwordField.value;

                    if (!value) {

                        passwordStrength.style.width =
                            "0";

                        passwordStrength.style.background =
                            "transparent";

                        return;
                    }

                    if (
                        value.length < 8
                    ) {

                        passwordStrength.style.width =
                            "33%";

                        passwordStrength.style.background =
                            "#e51a1a";

                    } else if (
                        value.length < 12
                    ) {

                        passwordStrength.style.width =
                            "66%";

                        passwordStrength.style.background =
                            "#f0a000";

                    } else {

                        passwordStrength.style.width =
                            "100%";

                        passwordStrength.style.background =
                            "#14a800";

                    }

                }
            );

        }

    }
);