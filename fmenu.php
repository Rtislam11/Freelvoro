<!DOCTYPE html>
<html>
    <head>
        <link
        rel="stylesheet"
        href="fmenu.css"
    >
</head>
<body>
    


<?php
/* Shared Freelvoro sidebar, mobile header and search overlay. */
?>
<aside class="sidebar" id="sidebar">


        <!-- LOGO -->

        <div class="sidebar-logo">

            <a href="fdashboard.php">
                freelvoro
            </a>

        </div>


        <!-- PROFILE -->

        <div
            class="profile-box"
            id="profileBox"
        >

            <div class="profile-avatar">
                <?php if (!empty($freelancer["profile_pic"])): ?>
                    <img
                        src="<?= htmlspecialchars($freelancer["profile_pic"]) ?>"
                        alt="<?= htmlspecialchars($freelancer["name"]) ?>"
                    >
                <?php else: ?>
                    <?= htmlspecialchars($freelancer["initials"]) ?>
                <?php endif; ?>
            </div>

            <div class="profile-info">

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $freelancer["name"]
                    );
                    ?>
                </strong>

                <span>
                    <?php
                    echo htmlspecialchars(
                        $freelancer["plan"]
                    );
                    ?>
                </span>

            </div>

            <button class="profile-arrow">
                ›
            </button>


            <!-- =================================================
                 PROFILE HOVER MENU
            ================================================== -->

            <div
                class="profile-menu"
                id="profileMenu"
            >


                <!-- MENU HEADER -->

                <div class="profile-menu-header">

                    <div class="menu-avatar">
                        <?php if (!empty($freelancer["profile_pic"])): ?>
                            <img
                                src="<?= htmlspecialchars($freelancer["profile_pic"]) ?>"
                                alt="<?= htmlspecialchars($freelancer["name"]) ?>"
                            >
                        <?php else: ?>
                            <?= htmlspecialchars($freelancer["initials"]) ?>
                        <?php endif; ?>
                    </div>

                    <div>

                        <strong>
                            <?= htmlspecialchars($freelancer["name"]) ?>
                        </strong>

                        <span>
                            <?= htmlspecialchars($freelancer["plan"]) ?>
                        </span>

                    </div>

                </div>


                <!-- ONLINE -->

                <div class="online-row">

                    <span>
                        Online for messages
                    </span>

                    <button
                        class="toggle active"
                        id="onlineToggle"
                    >
                        <span></span>
                    </button>

                </div>


                <!-- BASIC LINKS -->

                <div class="profile-menu-links">


                    <a href="fprofile.php">

                        <span class="menu-icon">
                            ♙
                        </span>

                        My profile

                    </a>


                    <a href="#">

                        <span class="menu-icon">
                            ↗
                        </span>

                        Stats and trends

                    </a>


                    <a href="#">

                        <span class="menu-icon">
                            ▣
                        </span>

                        Membership

                    </a>


                </div>


                


                <!-- MORE LINKS -->

                <div class="profile-menu-links">


                    <a href="#">

                        <span class="menu-icon">
                            ©
                        </span>

                        Connects

                    </a>


                    <a href="#">

                        <span class="menu-icon">
                            ◷
                        </span>

                        Account health

                    </a>


                    <a
                        href="#"
                        class="theme-row"
                    >

                        <span class="menu-icon">
                            ☼
                        </span>

                        Theme: Light

                        <span class="theme-arrow">
                            ˅
                        </span>

                    </a>


                    <a href="#">

                        <span class="menu-icon">
                            ⚙
                        </span>

                        Settings

                    </a>

                </div>


                <!-- BETA

                <div class="beta-row">

                    <span class="beta-label">
                        Beta
                    </span>

                    <span>
                        New main menu
                    </span>

                    <button
                        class="toggle active"
                        id="betaToggle"
                    >
                        <span></span>
                    </button>

                </div> -->


                <!-- LOGOUT -->

                <div class="profile-menu-bottom">


                    <a href="logout.php">

                        <span class="menu-icon">
                            ⇥
                        </span>

                        Log out

                    </a>


                    <a href="#">

                        <span class="menu-icon">
                            ⋯
                        </span>

                        More

                    </a>


                </div>


            </div>

        </div>



       

        <nav class="sidebar-nav">


            <!-- SEARCH -->

            <button
                type="button"
                class="nav-item search-nav"
                id="searchButton"
                onclick="openFreelvoroSearch(event)"
            >

                <span class="nav-icon">
                    ⌕
                </span>

                <span>
                    Search
                </span>

            </button>


            <a
                href="fdashboard.php"
                class="nav-item"
            >

                <span class="nav-icon">
                    ⌂
                </span>

                <span>
                    Home
                </span>

            </a>


            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ♧
                </span>

                <span>
                    Notifications
                </span>

            </a>


            <a
                href="#"
                class="nav-item has-arrow"
            >

                <span class="nav-icon">
                    ↗
                </span>

                <span>
                    Opportunities
                </span>

                <span class="nav-arrow">
                    ›
                </span>

            </a>


            <a
                href="#"
                class="nav-item has-arrow"
            >

                <span class="nav-icon">
                    ▣
                </span>

                <span>
                    Contracts
                </span>

                <span class="nav-arrow">
                    ›
                </span>

            </a>


            

<div class="finance-nav-wrapper">

    <a
        href="foverview.php"
        class="nav-item has-arrow finance-nav-item"
        id="financeNavItem"
    >

        <span class="nav-icon">
            $
        </span>

        <span>
            Finances
        </span>

        <span class="nav-arrow">
            ›
        </span>

    </a>


    

    <div
        class="finance-submenu"
        id="financeSubmenu"
    >

        <a
            href="foverview.php"
            class="finance-submenu-item"
            data-finance-page="foverview.php"
        >
            Overview
        </a>


        <a
            href="ftransactions.php"
            class="finance-submenu-item"
            data-finance-page="ftransactions.php"
        >
            Transactions
        </a>


        <a
            href="fwithdraw.php"
            class="finance-submenu-item"
            data-finance-page="fwithdraw.php"
        >
            Withdraw earnings
        </a>


        <a
            href="fbilling.php"
            class="finance-submenu-item"
            data-finance-page="fbilling.php"
        >
            Billings and earnings
        </a>


        <a
            href="freports.php"
            class="finance-submenu-item"
            data-finance-page="freports.php"
        >
            My reports
        </a>


        <a
            href="ftaxes.php"
            class="finance-submenu-item"
            data-finance-page="ftaxes.php"
        >
            Taxes
        </a>

    </div>

</div>


            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ▱
                </span>

                <span>
                    Messages
                </span>

            </a>


            <!-- <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ◉
                </span>

                <span>
                    Uma
                </span>

            </a>

        </nav> -->


        <!-- HELP -->

        <div class="sidebar-bottom">

            <a href="#" class="help-link">

                <span class="help-icon">
                    ?
                </span>

                Help

                <span>
                    ›
                </span>

            </a>

        </div>

    </aside>



    <header class="mobile-header">

        <button
            id="menuButton"
            class="menu-button"
        >
            ☰
        </button>

        <div class="mobile-logo">
            freelvoro
        </div>

        <button class="mobile-profile">
            <?php if (!empty($freelancer["profile_pic"])): ?>
                <img
                    src="<?= htmlspecialchars($freelancer["profile_pic"]) ?>"
                    alt="<?= htmlspecialchars($freelancer["name"]) ?>"
                >
            <?php else: ?>
                <?= htmlspecialchars($freelancer["initials"]) ?>
            <?php endif; ?>
        </button>

    </header>



    <div
        class="search-overlay"
        id="searchOverlay"
    >

        <div
            class="search-popup"
            id="searchPopup"
        >


            <!-- SEARCH INPUT -->

            <div class="search-popup-top">

                <span class="search-popup-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    id="skillSearch"
                    placeholder="Describe your skills"
                    autocomplete="off"
                >


                <button
                    class="search-type"
                    id="searchTypeButton"
                >

                    Jobs

                    <span>
                        ˅
                    </span>

                </button>

            </div>


            <!-- SEARCH TYPE DROPDOWN -->

            <div
                class="search-type-menu"
                id="searchTypeMenu"
            >

                <button>
                    Jobs
                </button>

                <button>
                    Talent
                </button>

            </div>


            <!-- SUGGESTIONS -->

            <div class="search-suggestions">

                <div class="suggestion-heading">

                    <span>
                        ♧
                    </span>

                    Try searching for

                </div>


                <button
                    class="suggestion"
                    data-search="virtual assistant"
                >
                    virtual assistant
                </button>


                <button
                    class="suggestion"
                    data-search="graphic designer"
                >
                    graphic designer
                </button>


                <button
                    class="suggestion"
                    data-search="video editor"
                >
                    video editor
                </button>


                <button
                    class="suggestion"
                    data-search="logo design"
                >
                    logo design
                </button>


                <button
                    class="suggestion"
                    data-search="social media manager"
                >
                    social media manager
                </button>


                <button
                    class="suggestion"
                    data-search="How do I post a job"
                >
                    How do I post a job
                </button>

            </div>

        </div>

    </div>


<script src="fmenu.js?v=30"></script>
            </body>
</html>


<!-- change -->
