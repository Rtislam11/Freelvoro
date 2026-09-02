<?php

session_start();

require_once "config.php";

$loginEmail = $_SESSION["email"]
    ?? $_SESSION["user_email"]
    ?? $_SESSION["login_email"]
    ?? "";

if ($loginEmail === "") {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("
    SELECT
        email,
        first_name,
        last_name,
        country,
        profile_pic
    FROM users
    WHERE email = ?
    LIMIT 1
");

if (!$stmt) {
    die(
        "Database query failed: " .
        htmlspecialchars(
            $conn->error,
            ENT_QUOTES,
            "UTF-8"
        )
    );
}

$stmt->bind_param(
    "s",
    $loginEmail
);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

$stmt->close();

if (!$user) {
    header("Location: index.php");
    exit;
}

$firstName = trim(
    (string)($user["first_name"] ?? "")
);

$lastName = trim(
    (string)($user["last_name"] ?? "")
);

$fullName = trim(
    $firstName . " " . $lastName
);

if ($fullName === "") {
    $fullName = "Freelancer";
}

$initials = "";

if ($firstName !== "") {
    $initials .= strtoupper(
        substr($firstName, 0, 1)
    );
}

if ($lastName !== "") {
    $initials .= strtoupper(
        substr($lastName, 0, 1)
    );
}

if ($initials === "") {
    $initials = "U";
}

$profilePic = trim(
    (string)($user["profile_pic"] ?? "")
);

$country = trim(
    (string)($user["country"] ?? "")
);

$freelancer = [
    "email" => $user["email"] ?? "",
    "name" => $fullName,
    "initials" => $initials,
    "plan" => "Freelancer Basic",
    "title" => "web developer",
    "rate" => "$9.00/hr",
    "location" => $country,
    "hours" => "More than 30 hrs/week",
    "english" => "Conversational",
    "skills" => [
        "Web API"
    ],
    "profile_pic" => $profilePic
];

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Public Profile - Freelvoro</title>

    <link
        rel="stylesheet"
        href="fdashboard.css"
    >

    <link
        rel="stylesheet"
        href="vprofile.css?v=1"
    >

</head>

<body>

<div class="app">

    <?php include "fmenu.php"; ?>

    <main class="public-profile-main">

        <div class="public-profile-page">

            <section class="public-profile-card">

                <div class="public-profile-header">

                    <div class="public-profile-identity">

                        <div class="public-avatar">

                            <?php if ($profilePic !== ""): ?>

                                <img
                                    src="<?= htmlspecialchars(
                                        $profilePic,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                        $fullName,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>"
                                >

                            <?php else: ?>

                                <?= htmlspecialchars(
                                    $initials,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>

                            <?php endif; ?>

                        </div>

                        <div class="public-identity-text">

                            <div class="public-name-row">

                                <h1>
                                    <?= htmlspecialchars(
                                        $fullName,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </h1>

                                <span class="public-verified">
                                    ✓
                                </span>

                            </div>

                            <div class="public-location">

                                <span class="location-icon">
                                    ⌖
                                </span>

                                <span>
                                    <?= htmlspecialchars(
                                        $country,
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>
                                </span>

                                <span class="location-separator">
                                    •
                                </span>

                                <span id="publicLocalTime">
                                    local time
                                </span>

                            </div>

                        </div>

                    </div>

                    <div class="public-profile-menu-wrap">

                        <button
                            type="button"
                            class="public-more-button"
                            id="publicMoreButton"
                            aria-label="More options"
                        >
                            ⋯
                        </button>

                        <div
                            class="public-more-menu"
                            id="publicMoreMenu"
                        >

                            <button
                                type="button"
                                id="copyProfileButton"
                            >
                                Copy profile link
                            </button>

                            <button
                                type="button"
                                id="reportProfileButton"
                            >
                                Report profile
                            </button>

                        </div>

                    </div>

                </div>

                <div class="public-share-row">

                    <button
                        type="button"
                        class="public-share-button"
                        id="publicShareButton"
                    >

                        <span>
                            Share
                        </span>

                        <span class="share-icon">
                            ↗
                        </span>

                    </button>

                </div>

                <div class="public-profile-info">

                    <div class="public-info-left">

                        <div class="public-info-block">

                            <h2>
                                Hours per week
                            </h2>

                            <p>
                                <?= htmlspecialchars(
                                    $freelancer["hours"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </p>

                        </div>

                        <div class="public-info-block language-block">

                            <h2>
                                Languages
                            </h2>

                            <p>
                                English:
                                <?= htmlspecialchars(
                                    $freelancer["english"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </p>

                        </div>

                    </div>

                    <div class="public-info-right">

                        <div class="public-title-row">

                            <h2>
                                <?= htmlspecialchars(
                                    $freelancer["title"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </h2>

                            <strong>
                                <?= htmlspecialchars(
                                    $freelancer["rate"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </strong>

                        </div>

                        <div class="public-divider"></div>

                        <div class="public-skills">

                            <h2>
                                Skills
                            </h2>

                            <div class="public-skill-list">

                                <?php foreach (
                                    $freelancer["skills"] as $skill
                                ): ?>

                                    <span class="public-skill">
                                        <?= htmlspecialchars(
                                            $skill,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>
                                    </span>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <footer class="public-profile-footer">

                <span>
                    © 2015 - 2026 Freelvoro Global LLC
                </span>

                <a href="#">
                    Terms
                </a>

                <a href="#">
                    Privacy
                </a>

                <a href="#">
                    Your Privacy Choices
                </a>

                <span class="privacy-toggle">
                    ✓
                </span>

            </footer>

        </div>

    </main>

</div>

<script src="vprofile.js?v=1"></script>

</body>
</html>
