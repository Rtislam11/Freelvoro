"use strict";

window.openFreelvoroSearch = function (event) {

    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const overlay =
        document.getElementById("searchOverlay");

    if (!overlay) {
        return;
    }

    overlay.classList.add("open");

    const input =
        document.getElementById("skillSearch");

    if (input) {
        setTimeout(function () {
            input.focus();
        }, 80);
    }
};


window.closeFreelvoroSearch = function () {

    const overlay =
        document.getElementById("searchOverlay");

    if (overlay) {
        overlay.classList.remove("open");
    }
};


document.addEventListener("click", function (event) {

    const overlay =
        document.getElementById("searchOverlay");

    if (
        overlay &&
        event.target === overlay
    ) {
        window.closeFreelvoroSearch();
    }

});


document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {
        window.closeFreelvoroSearch();
    }

});


document.addEventListener("click", function (event) {

    const button =
        event.target.closest("#searchTypeButton");

    if (!button) {
        return;
    }

    event.preventDefault();
    event.stopPropagation();

    const menu =
        document.getElementById("searchTypeMenu");

    if (menu) {
        menu.classList.toggle("open");
    }

});


document.addEventListener("click", function (event) {

    const option =
        event.target.closest(
            "#searchTypeMenu button"
        );

    if (!option) {
        return;
    }

    event.preventDefault();
    event.stopPropagation();

    const button =
        document.getElementById("searchTypeButton");

    const menu =
        document.getElementById("searchTypeMenu");

    if (button) {
        button.innerHTML =
            option.textContent.trim() +
            ' <span>˅</span>';
    }

    if (menu) {
        menu.classList.remove("open");
    }

});


function initFreelvoroFinanceMenu() {

    const wrapper =
        document.querySelector(
            ".finance-nav-wrapper"
        );

    const financeButton =
        document.getElementById(
            "financeNavItem"
        );

    const submenu =
        document.getElementById(
            "financeSubmenu"
        );

    if (
        !wrapper ||
        !financeButton ||
        !submenu
    ) {
        return;
    }

    if (
        wrapper.dataset.financeInitialized === "1"
    ) {
        return;
    }

    wrapper.dataset.financeInitialized = "1";

    const submenuHome =
        submenu.parentNode;

    const submenuNext =
        submenu.nextSibling;

    let closeTimer = null;

    function isMobile() {
        return window.innerWidth <= 800;
    }

    function moveSubmenuToBody() {

        if (isMobile()) {
            return;
        }

        if (submenu.parentElement !== document.body) {
            document.body.appendChild(submenu);
        }

        submenu.classList.add(
            "finance-desktop-portal"
        );
    }

    function moveSubmenuBackToMenu() {

        if (!isMobile()) {
            return;
        }

        if (
            submenu.parentElement !== submenuHome
        ) {

            if (submenuNext) {
                submenuHome.insertBefore(
                    submenu,
                    submenuNext
                );
            } else {
                submenuHome.appendChild(
                    submenu
                );
            }
        }

        submenu.classList.remove(
            "finance-desktop-portal"
        );
    }

    const currentPage =
        window.location.pathname
            .split("/")
            .pop()
            .toLowerCase();

    submenu
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

                financeButton.classList.add(
                    "finance-active"
                );
            }

        });

    function positionFinanceMenu() {

        if (isMobile()) {
            return;
        }

        moveSubmenuToBody();

        const rect =
            financeButton.getBoundingClientRect();

        const menuWidth =
            submenu.offsetWidth || 380;

        const menuHeight =
            submenu.offsetHeight || 280;

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

        if (top < 10) {
            top = 10;
        }

        submenu.style.left =
            Math.round(left) + "px";

        submenu.style.top =
            Math.round(top) + "px";
    }

    function openFinance() {

        if (isMobile()) {
            return;
        }

        clearTimeout(closeTimer);

        moveSubmenuToBody();

        submenu.classList.add(
            "finance-hover-open"
        );

        financeButton.classList.add(
            "finance-active"
        );

        financeButton.setAttribute(
            "aria-expanded",
            "true"
        );

        positionFinanceMenu();
    }

    function closeFinance() {

        if (isMobile()) {
            return;
        }

        clearTimeout(closeTimer);

        closeTimer =
            setTimeout(function () {

                submenu.classList.remove(
                    "finance-hover-open"
                );

                if (
                    !submenu.querySelector(
                        ".finance-current"
                    )
                ) {

                    financeButton.classList.remove(
                        "finance-active"
                    );
                }

                financeButton.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }, 180);
    }

    wrapper.addEventListener(
        "mouseenter",
        openFinance
    );

    wrapper.addEventListener(
        "mouseleave",
        closeFinance
    );

    submenu.addEventListener(
        "mouseenter",
        function () {

            if (!isMobile()) {
                clearTimeout(closeTimer);
            }

        }
    );

    submenu.addEventListener(
        "mouseleave",
        function () {

            if (!isMobile()) {
                closeFinance();
            }

        }
    );

    financeButton.addEventListener(
        "click",
        function (event) {

            if (!isMobile()) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            moveSubmenuBackToMenu();

            const isOpen =
                submenu.classList.contains(
                    "finance-mobile-open"
                );

            const newState =
                !isOpen;

            submenu.classList.toggle(
                "finance-mobile-open",
                newState
            );

            wrapper.classList.toggle(
                "finance-mobile-open",
                newState
            );

            financeButton.classList.toggle(
                "finance-active",
                newState
            );

            financeButton.setAttribute(
                "aria-expanded",
                String(newState)
            );

            submenu.setAttribute(
                "aria-hidden",
                String(!newState)
            );

        }
    );

    submenu
        .querySelectorAll(
            ".finance-submenu-item"
        )
        .forEach(function (link) {

            link.addEventListener(
                "click",
                function () {

                    submenu.classList.remove(
                        "finance-mobile-open"
                    );

                    wrapper.classList.remove(
                        "finance-mobile-open"
                    );

                }
            );

        });

    document.addEventListener(
        "click",
        function (event) {

            if (
                !financeButton.contains(
                    event.target
                ) &&
                !submenu.contains(
                    event.target
                )
            ) {

                if (isMobile()) {

                    submenu.classList.remove(
                        "finance-mobile-open"
                    );

                    wrapper.classList.remove(
                        "finance-mobile-open"
                    );

                } else {

                    closeFinance();

                }

            }

        }
    );

    document.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Escape") {

                if (isMobile()) {

                    submenu.classList.remove(
                        "finance-mobile-open"
                    );

                    wrapper.classList.remove(
                        "finance-mobile-open"
                    );

                } else {

                    closeFinance();

                }

            }

        }
    );

    window.addEventListener(
        "resize",
        function () {

            clearTimeout(closeTimer);

            if (isMobile()) {

                submenu.classList.remove(
                    "finance-hover-open"
                );

                moveSubmenuBackToMenu();

            } else {

                submenu.classList.remove(
                    "finance-mobile-open"
                );

                wrapper.classList.remove(
                    "finance-mobile-open"
                );

                if (
                    submenu.classList.contains(
                        "finance-hover-open"
                    )
                ) {

                    positionFinanceMenu();
                }

            }

        }
    );

    window.addEventListener(
        "scroll",
        function () {

            if (
                !isMobile() &&
                submenu.classList.contains(
                    "finance-hover-open"
                )
            ) {

                positionFinanceMenu();

            }

        },
        true
    );

    if (isMobile()) {
        moveSubmenuBackToMenu();
    } else {
        moveSubmenuToBody();
    }

}


if (
    document.readyState ===
    "loading"
) {

    document.addEventListener(
        "DOMContentLoaded",
        initFreelvoroFinanceMenu
    );

} else {

    initFreelvoroFinanceMenu();

}


document.addEventListener(
    "click",
    function (event) {

        const suggestion =
            event.target.closest(
                ".suggestion"
            );

        if (!suggestion) {
            return;
        }

        const input =
            document.getElementById(
                "skillSearch"
            );

        if (input) {

            input.value =
                suggestion.dataset.search ||
                "";

            input.focus();

        }

    }
);