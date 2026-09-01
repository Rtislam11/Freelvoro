document.addEventListener("DOMContentLoaded", function () {

    "use strict";

    const sidebar =
        document.getElementById("sidebar");

    const menuButton =
        document.getElementById("menuButton");


    if (menuButton && sidebar) {

        menuButton.addEventListener("click", function (e) {

            e.preventDefault();
            e.stopPropagation();

            sidebar.classList.toggle("mobile-open");

        });

    }


    const searchButton =
        document.getElementById("searchButton");

    const searchOverlay =
        document.getElementById("searchOverlay");

    const skillSearch =
        document.getElementById("skillSearch");

    const searchTypeButton =
        document.getElementById("searchTypeButton");

    const searchTypeMenu =
        document.getElementById("searchTypeMenu");


    function openSearch(e) {

        if (e) {

            e.preventDefault();
            e.stopPropagation();

        }

        if (!searchOverlay) {
            return;
        }

        searchOverlay.classList.add("open");

        document.body.classList.add("search-open");

        if (skillSearch) {

            setTimeout(function () {

                skillSearch.focus();

            }, 100);

        }

    }


    function closeSearch() {

        if (!searchOverlay) {
            return;
        }

        searchOverlay.classList.remove("open");

        document.body.classList.remove("search-open");

        if (searchTypeMenu) {

            searchTypeMenu.classList.remove(
                "open"
            );

        }

    }


    if (searchButton) {

        searchButton.addEventListener(
            "click",
            openSearch
        );

    }


    if (searchOverlay) {

        searchOverlay.addEventListener(
            "click",
            function (e) {

                if (e.target === searchOverlay) {

                    closeSearch();

                }

            }
        );

    }


    if (
        searchTypeButton &&
        searchTypeMenu
    ) {

        searchTypeButton.addEventListener(
            "click",
            function (e) {

                e.preventDefault();
                e.stopPropagation();

                searchTypeMenu.classList.toggle(
                    "open"
                );

            }
        );


        searchTypeMenu
            .querySelectorAll("button")
            .forEach(function (button) {

                button.addEventListener(
                    "click",
                    function (e) {

                        e.preventDefault();
                        e.stopPropagation();

                        searchTypeButton.innerHTML =
                            this.textContent.trim() +
                            ' <span>˅</span>';

                        searchTypeMenu.classList.remove(
                            "open"
                        );

                    }
                );

            });

    }


    document
        .querySelectorAll(".suggestion")
        .forEach(function (suggestion) {

            suggestion.addEventListener(
                "click",
                function (e) {

                    e.preventDefault();

                    if (!skillSearch) {
                        return;
                    }

                    skillSearch.value =
                        this.dataset.search || "";

                    skillSearch.focus();

                }
            );

        });


    document.addEventListener(
        "keydown",
        function (e) {

            if (e.key === "Escape") {

                closeSearch();

            }

        }
    );


    const financeWrapper =
        document.querySelector(
            ".finance-nav-wrapper"
        );

    const financeNav =
        document.getElementById(
            "financeNavItem"
        );

    const financeMenu =
        document.getElementById(
            "financeSubmenu"
        );


    let financeTimer = null;


    function positionFinanceMenu() {

        if (
            !financeNav ||
            !financeMenu
        ) {

            return;

        }


        if (window.innerWidth <= 800) {
            return;
        }


        const rect =
            financeNav.getBoundingClientRect();

        const menuWidth =
            financeMenu.offsetWidth || 380;

        const gap = 8;

        let left =
            rect.right + gap;

        let top =
            rect.top;


        if (
            left + menuWidth >
            window.innerWidth - 10
        ) {

            left =
                rect.left -
                menuWidth -
                gap;

        }


        const menuHeight =
            financeMenu.offsetHeight || 280;


        if (
            top + menuHeight >
            window.innerHeight - 10
        ) {

            top =
                Math.max(
                    10,
                    window.innerHeight -
                    menuHeight -
                    10
                );

        }


        financeMenu.style.left =
            Math.round(left) + "px";

        financeMenu.style.top =
            Math.round(top) + "px";

    }


    function openFinance() {

        if (
            !financeWrapper ||
            !financeMenu
        ) {

            return;

        }


        if (window.innerWidth <= 800) {
            return;
        }


        clearTimeout(financeTimer);

        positionFinanceMenu();

        financeMenu.classList.add(
            "finance-hover-open"
        );

        financeNav.classList.add(
            "finance-active"
        );

    }


    function closeFinance() {

        if (!financeMenu) {
            return;
        }


        if (window.innerWidth <= 800) {
            return;
        }


        clearTimeout(financeTimer);


        financeTimer =
            setTimeout(function () {

                financeMenu.classList.remove(
                    "finance-hover-open"
                );


                const current =
                    financeMenu.querySelector(
                        ".finance-current"
                    );


                if (!current) {

                    financeNav.classList.remove(
                        "finance-active"
                    );

                }

            }, 100);

    }


    if (
        financeWrapper &&
        financeNav &&
        financeMenu
    ) {

        financeWrapper.addEventListener(
            "mouseenter",
            function () {

                openFinance();

            }
        );


        financeWrapper.addEventListener(
            "mouseleave",
            function () {

                closeFinance();

            }
        );


        financeMenu.addEventListener(
            "mouseenter",
            function () {

                clearTimeout(
                    financeTimer
                );

                financeMenu.classList.add(
                    "finance-hover-open"
                );

            }
        );


        financeMenu.addEventListener(
            "mouseleave",
            function () {

                closeFinance();

            }
        );


        financeNav.addEventListener(
            "click",
            function (e) {

                if (window.innerWidth <= 800) {

                    e.preventDefault();
                    e.stopPropagation();

                    financeMenu.classList.toggle(
                        "finance-mobile-open"
                    );

                    financeNav.classList.toggle(
                        "finance-active"
                    );

                }

            }
        );

    }


    if (financeMenu) {

        const currentPage =
            window.location.pathname
                .split("/")
                .pop()
                .toLowerCase();


        financeMenu
            .querySelectorAll(
                ".finance-submenu-item"
            )
            .forEach(function (link) {

                const page =
                    (
                        link.dataset.financePage ||
                        ""
                    ).toLowerCase();


                if (
                    page === currentPage
                ) {

                    link.classList.add(
                        "finance-current"
                    );


                    if (financeNav) {

                        financeNav.classList.add(
                            "finance-active"
                        );

                    }

                }


                link.addEventListener(
                    "click",
                    function () {

                        financeMenu
                            .querySelectorAll(
                                ".finance-submenu-item"
                            )
                            .forEach(
                                function (item) {

                                    item.classList.remove(
                                        "finance-current"
                                    );

                                }
                            );


                        this.classList.add(
                            "finance-current"
                        );

                    }
                );

            });

    }


    window.addEventListener(
        "resize",
        function () {

            if (
                window.innerWidth > 800
            ) {

                if (financeMenu) {

                    financeMenu.classList.remove(
                        "finance-mobile-open"
                    );

                }

                if (financeWrapper) {

                    financeWrapper.classList.remove(
                        "finance-mobile-open"
                    );

                }

            }


            if (
                financeMenu &&
                financeMenu.classList.contains(
                    "finance-hover-open"
                )
            ) {

                positionFinanceMenu();

            }

        }
    );


    window.addEventListener(
        "scroll",
        function () {

            if (
                financeMenu &&
                financeMenu.classList.contains(
                    "finance-hover-open"
                )
            ) {

                positionFinanceMenu();

            }

        },
        true
    );


    const onlineToggle =
        document.getElementById(
            "onlineToggle"
        );


    if (onlineToggle) {

        onlineToggle.addEventListener(
            "click",
            function (e) {

                e.preventDefault();
                e.stopPropagation();

                this.classList.toggle(
                    "active"
                );

            }
        );

    }


    const betaToggle =
        document.getElementById(
            "betaToggle"
        );


    if (betaToggle) {

        betaToggle.addEventListener(
            "click",
            function (e) {

                e.preventDefault();
                e.stopPropagation();

                this.classList.toggle(
                    "active"
                );

            }
        );

    }


    document
        .querySelectorAll(".plus-close")
        .forEach(function (button) {

            button.addEventListener(
                "click",
                function (e) {

                    e.preventDefault();
                    e.stopPropagation();


                    const card =
                        this.closest(
                            ".plus-card"
                        );


                    if (card) {

                        card.style.display =
                            "none";

                    }

                }
            );

        });


    const closeBanner =
        document.getElementById(
            "closeBanner"
        );

    const infoBanner =
        document.getElementById(
            "infoBanner"
        );


    if (
        closeBanner &&
        infoBanner
    ) {

        closeBanner.addEventListener(
            "click",
            function () {

                infoBanner.style.display =
                    "none";

            }
        );

    }


    document
        .querySelectorAll(".save-job")
        .forEach(function (button) {

            button.addEventListener(
                "click",
                function (e) {

                    e.preventDefault();
                    e.stopPropagation();

                    this.classList.toggle(
                        "saved"
                    );


                    this.textContent =
                        this.classList.contains(
                            "saved"
                        )
                            ? "♥"
                            : "♡";

                }
            );

        });


    const jobSearch =
        document.getElementById(
            "jobSearch"
        );

    const jobCards =
        document.querySelectorAll(
            ".job-card"
        );

    const noResults =
        document.getElementById(
            "noResults"
        );


    if (jobSearch) {

        jobSearch.addEventListener(
            "input",
            function () {

                const value =
                    this.value
                        .trim()
                        .toLowerCase();


                let count = 0;


                jobCards.forEach(
                    function (card) {

                        const text =
                            card.textContent
                                .toLowerCase();


                        if (
                            text.includes(value)
                        ) {

                            card.style.display =
                                "";

                            count++;

                        } else {

                            card.style.display =
                                "none";

                        }

                    }
                );


                if (noResults) {

                    noResults.classList.toggle(
                        "show",
                        count === 0
                    );

                }

            }
        );

    }


    document
        .querySelectorAll(".tab")
        .forEach(function (tab) {

            tab.addEventListener(
                "click",
                function () {

                    document
                        .querySelectorAll(
                            ".tab"
                        )
                        .forEach(
                            function (item) {

                                item.classList.remove(
                                    "active"
                                );

                            }
                        );


                    this.classList.add(
                        "active"
                    );


                    const selected =
                        this.dataset.tab;


                    let count = 0;


                    jobCards.forEach(
                        function (card) {

                            const category =
                                card.dataset.category;


                            if (
                                selected === "best"
                            ) {

                                card.style.display =
                                    "";

                                count++;

                            }

                            else if (
                                selected === "recent"
                            ) {

                                if (
                                    category ===
                                    "recent"
                                ) {

                                    card.style.display =
                                        "";

                                    count++;

                                } else {

                                    card.style.display =
                                        "none";

                                }

                            }

                            else if (
                                selected === "saved"
                            ) {

                                if (
                                    card.querySelector(
                                        ".save-job.saved"
                                    )
                                ) {

                                    card.style.display =
                                        "";

                                    count++;

                                } else {

                                    card.style.display =
                                        "none";

                                }

                            }

                            else if (
                                selected === "invites"
                            ) {

                                card.style.display =
                                    "none";

                            }

                        }
                    );


                    if (noResults) {

                        noResults.classList.toggle(
                            "show",
                            count === 0
                        );

                    }

                }
            );

        });


    const completeButton =
        document.getElementById(
            "completeProfileButton"
        );

    const completeOverlay =
        document.getElementById(
            "completeProfileOverlay"
        );

    const completeClose =
        document.getElementById(
            "completeProfileClose"
        );

    const completeBottomClose =
        document.getElementById(
            "completeProfileBottomClose"
        );


    function openCompleteProfile(e) {

        if (e) {

            e.preventDefault();
            e.stopPropagation();

        }


        if (!completeOverlay) {
            return;
        }


        completeOverlay.classList.add(
            "open"
        );


        completeOverlay.setAttribute(
            "aria-hidden",
            "false"
        );


        document.body.classList.add(
            "complete-profile-open"
        );

    }


    function closeCompleteProfile(e) {

        if (e) {

            e.preventDefault();
            e.stopPropagation();

        }


        if (!completeOverlay) {
            return;
        }


        completeOverlay.classList.remove(
            "open"
        );


        completeOverlay.setAttribute(
            "aria-hidden",
            "true"
        );


        document.body.classList.remove(
            "complete-profile-open"
        );

    }


    if (completeButton) {

        completeButton.addEventListener(
            "click",
            openCompleteProfile
        );

    }


    if (completeClose) {

        completeClose.addEventListener(
            "click",
            closeCompleteProfile
        );

    }


    if (completeBottomClose) {

        completeBottomClose.addEventListener(
            "click",
            closeCompleteProfile
        );

    }


    if (completeOverlay) {

        completeOverlay.addEventListener(
            "click",
            function (e) {

                if (
                    e.target ===
                    completeOverlay
                ) {

                    closeCompleteProfile(e);

                }

            }
        );

    }


    document
        .querySelectorAll(".dislike")
        .forEach(function (button) {

            button.addEventListener(
                "click",
                function (e) {

                    e.preventDefault();


                    const card =
                        this.closest(
                            ".job-card"
                        );


                    if (card) {

                        card.style.display =
                            "none";

                    }

                }
            );

        });


    console.log(
        "Freelvoro shared menu loaded successfully."
    );

});