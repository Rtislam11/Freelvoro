<?php

session_start();

require_once "config.php";

$user = null;

if (!empty($_SESSION["user_id"])) {

    $userId = (int) $_SESSION["user_id"];

    $stmt = $conn->prepare("
        SELECT
            id,
            email,
            first_name,
            last_name,
            country,
            profile_pic
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    if ($stmt) {

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();
    }
}

if (!$user) {

    $userEmail =
        $_SESSION["email"]
        ?? $_SESSION["user_email"]
        ?? null;

    if ($userEmail) {

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

        if ($stmt) {

            $stmt->bind_param(
                "s",
                $userEmail
            );

            $stmt->execute();

            $result =
                $stmt->get_result();

            $user =
                $result->fetch_assoc();

            $stmt->close();
        }
    }
}

if (!$user) {

    die(
        "User not found. Please login first."
    );

}

$firstName =
    trim(
        $user["first_name"] ?? ""
    );

$lastName =
    trim(
        $user["last_name"] ?? ""
    );

$fullName =
    trim(
        $firstName . " " . $lastName
    );

if ($fullName === "") {

    $fullName =
        "Freelancer";

}

$initials = "";

if ($firstName !== "") {

    $initials .=
        strtoupper(
            substr(
                $firstName,
                0,
                1
            )
        );

}

if ($lastName !== "") {

    $initials .=
        strtoupper(
            substr(
                $lastName,
                0,
                1
            )
        );

}

if ($initials === "") {

    $initials = "U";

}

$profilePic =
    trim(
        $user["profile_pic"] ?? ""
    );

$freelancer = [

    "email" =>
        $user["email"] ?? "",

    "name" =>
        $fullName,

    "initials" =>
        $initials,

    "plan" =>
        "Freelancer Basic",

    "title" =>
        "web developer",

    "rate" =>
        "$9.00/hr",

    "location" =>
        $user["country"] ?? "",

    "profile_visibility" =>
        "Public",

    "profile_complete" =>
        40,

    "availability" =>
        "Off",

    "hours" =>
        "More than 30 hrs/week",

    "english" =>
        "Conversational",

    "skills" =>
        [
            "Web API"
        ],

    "profile_pic" =>
        $profilePic

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

    <title>
        My Profile - Freelvoro
    </title>

    <link
        rel="stylesheet"
        href="fprofile.css?v=102"
    >

</head>

<body>

<div class="app">

    <?php include "fmenu.php"; ?>

    <main class="profile-main">

        <div class="profile-alert">

            <div>

                <span class="alert-icon">
                    !
                </span>

                <strong>
                    Action required:
                </strong>

                Verify your identity to build trust and
                connect with freelancers.

            </div>

            <a href="#verification">
                Get started.
            </a>

        </div>

        <div class="profile-page">

            <section class="profile-header-card">

                <div class="profile-header-top">

                    <div
                        class="profile-hero profile-edit-trigger"
                        id="profileEditTrigger"
                        role="button"
                        tabindex="0"
                        aria-label="Edit profile"
                    >

                        <div class="hero-avatar">

                            <?php if (!empty($freelancer["profile_pic"])): ?>

                                <img
                                    src="<?= htmlspecialchars(
                                        $freelancer["profile_pic"]
                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                        $freelancer["name"]
                                    ) ?>"
                                >

                            <?php else: ?>

                                <span>
                                    <?= htmlspecialchars(
                                        $freelancer["initials"]
                                    ) ?>
                                </span>

                            <?php endif; ?>

                        </div>

                        <div>

                            <div class="hero-name-row">

                                <h1>
                                    <?= htmlspecialchars(
                                        $freelancer["name"]
                                    ) ?>
                                </h1>

                                <span class="verified-small">
                                    ✓
                                </span>

                                <a
                                    href="#verification"
                                    class="verify-link"
                                    onclick="event.stopPropagation();"
                                >
                                    Verify your identity
                                </a>

                            </div>

                            <div class="hero-location">

                                <span>
                                    ⌖
                                </span>

                                <?= htmlspecialchars(
                                    $freelancer["location"]
                                ) ?>

                                <span class="dot-separator">
                                    •
                                </span>

                                <span id="localTime">
                                    5:49 pm local time
                                </span>

                            </div>

                        </div>

                    </div>

                    <div class="profile-header-actions">

                        <button
                            class="outline-button"
                            type="button"
                            id="publicViewButton"
                        >
                            See public view
                        </button>

                        <button
                            class="green-button"
                            type="button"
                            id="profileSettingsButton"
                        >
                            Profile settings
                        </button>

                    </div>

                </div>

                <div class="profile-title-row">

                    <div>

                        <h2>

                            <?= htmlspecialchars(
                                $freelancer["title"]
                            ) ?>

                            <button
                                class="tiny-edit"
                                type="button"
                                data-edit="title"
                            >
                                ✎
                            </button>

                        </h2>

                    </div>

                    <div class="rate-wrap">

                        <strong>

                            <?= htmlspecialchars(
                                $freelancer["rate"]
                            ) ?>

                        </strong>

                        <button
                            class="tiny-edit"
                            type="button"
                            data-edit="rate"
                        >
                            ✎
                        </button>

                        <button
                            class="link-icon"
                            type="button"
                        >
                            ↗
                        </button>

                    </div>

                </div>

            </section>

            <div class="profile-grid">

                <div class="profile-left">

                    <section
                        class="profile-section portfolio-section"
                    >

                        <div class="section-heading">

                            <h2>
                                Portfolio
                            </h2>

                            <div class="section-tabs">

                                <button
                                    class="section-tab active"
                                    type="button"
                                >
                                    Published
                                </button>

                                <button
                                    class="section-tab"
                                    type="button"
                                >
                                    Drafts
                                </button>

                            </div>

                            <button
                                class="circle-add"
                                type="button"
                                aria-label="Add portfolio"
                            >
                                +
                            </button>

                        </div>

                        <div class="empty-portfolio">

                            <div class="portfolio-art">

                                <div class="folder-back"></div>

                                <div class="folder-front"></div>

                            </div>

                            <p>
                                Add a project. Talent are hired
                                9x more often if they've published
                                a portfolio.
                            </p>

                            <button
                                class="green-text-button"
                                type="button"
                            >
                                Add project
                            </button>

                        </div>

                    </section>

                    <section
                        class="profile-section details-section"
                    >

                        <div class="detail-column">

                            <div class="detail-title-row">

                                <h3>
                                    Video introduction
                                </h3>

                                <button
                                    class="circle-add"
                                    type="button"
                                >
                                    +
                                </button>

                            </div>

                            <div class="detail-title-row">

                                <h3>
                                    Hours per week
                                </h3>

                                <button
                                    class="tiny-edit"
                                    type="button"
                                    data-edit="hours"
                                >
                                    ✎
                                </button>

                            </div>

                            <p class="detail-value">

                                <?= htmlspecialchars(
                                    $freelancer["hours"]
                                ) ?>

                            </p>

                            <p class="muted-line">
                                No contract-to-hire preference set
                            </p>

                            <div
                                class="detail-title-row language-title"
                            >

                                <h3>
                                    Languages
                                </h3>

                                <div>

                                    <button
                                        class="circle-add"
                                        type="button"
                                    >
                                        +
                                    </button>

                                    <button
                                        class="tiny-edit"
                                        type="button"
                                    >
                                        ✎
                                    </button>

                                </div>

                            </div>

                            <p class="detail-value">

                                English:
                                <?= htmlspecialchars(
                                    $freelancer["english"]
                                ) ?>

                            </p>

                            <div class="detail-title-row">

                                <h3>
                                    Verifications
                                </h3>

                            </div>

                            <div
                                class="verification-line"
                                id="verification"
                            >

                                <span>
                                    ID: Unverified
                                </span>

                                <a href="#">
                                    Verify your identity
                                </a>

                            </div>

                        </div>

                        <div class="detail-column right-details">

                            <div class="detail-title-row">

                                <h3>
                                    Work history
                                </h3>

                            </div>

                            <div class="empty-work">

                                <span class="briefcase-icon">
                                    ▣
                                </span>

                                <p>
                                    No items
                                </p>

                            </div>

                            <div
                                class="detail-title-row skills-heading"
                            >

                                <h3>
                                    Skills
                                </h3>

                                <button
                                    class="tiny-edit"
                                    type="button"
                                >
                                    ✎
                                </button>

                            </div>

                            <div class="skill-list">

                                <?php foreach (
                                    $freelancer["skills"]
                                    as $skill
                                ): ?>

                                    <span class="profile-skill">

                                        <?= htmlspecialchars(
                                            $skill
                                        ) ?>

                                    </span>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    </section>

                    <section
                        class="profile-section catalog-section"
                    >

                        <div class="catalog-copy">

                            <h2>
                                Your project catalog
                            </h2>

                            <p>
                                Projects are a new way to earn on
                                Freelvoro that helps you do more of
                                the work you love to do. Create
                                project offerings that highlight
                                your strengths and attract clients.
                            </p>

                            <button
                                class="outline-button"
                                type="button"
                            >
                                Manage projects
                            </button>

                        </div>

                    </section>

                    <section
                        class="profile-section lower-section"
                    >

                        <div class="lower-column">

                            <h2>
                                Certifications
                            </h2>

                            <div class="empty-card">

                                <div class="trophy">
                                    🏆
                                </div>

                                <p>
                                    Listing your certifications can
                                    help prove your specific knowledge
                                    or abilities. (+10%)
                                </p>

                                <button
                                    class="green-text-button"
                                    type="button"
                                >
                                    Add certification
                                </button>

                            </div>

                        </div>

                        <div class="lower-column">

                            <h2>
                                Employment history
                            </h2>

                            <div class="empty-card">

                                <div class="briefcase-art">
                                    ▣
                                </div>

                                <p>
                                    Add employment history to
                                    showcase your past work to clients
                                </p>

                                <button
                                    class="green-text-button"
                                    type="button"
                                >
                                    Add employment
                                </button>

                            </div>

                        </div>

                    </section>

                    <section
                        class="profile-section other-section"
                    >

                        <h2>
                            Other experiences
                        </h2>

                        <div
                            class="empty-card other-empty"
                        >

                            <div class="folder-art">
                                ▰
                            </div>

                            <p>
                                Add any other experiences that
                                help you stand out
                            </p>

                            <button
                                class="green-text-button"
                                type="button"
                            >
                                Add an experience
                            </button>

                        </div>

                    </section>

                    <section
                        class="profile-section testimonials-section"
                    >

                        <h2>
                            Testimonials
                        </h2>

                        <p>
                            Endorsements from past clients
                        </p>

                        <button
                            class="circle-add"
                            type="button"
                        >
                            +
                        </button>

                    </section>

                </div>

                <aside class="profile-right">

                    <div class="freelancer-plus-card">

                        <div class="plus-label">
                            ☆ FREELANCER PLUS OFFER
                        </div>

                        <h3>
                            Get Freelancer Plus for 50% off
                            one month and keep your profile
                            visible during breaks.
                        </h3>

                        <p>
                            Limited time only.
                        </p>

                        <button type="button">
                            →
                        </button>

                    </div>

                    <div class="side-card promote-card">

                        <h3>
                            Promote with ads
                        </h3>

                        <div class="side-setting">

                            <div>

                                <strong>
                                    Availability badge
                                </strong>

                                <span>
                                    Off
                                </span>

                            </div>

                            <button
                                class="tiny-edit"
                                type="button"
                            >
                                ✎
                            </button>

                        </div>

                        <div class="side-setting">

                            <div>

                                <strong>
                                    Boost your profile
                                </strong>

                                <span>
                                    Off
                                </span>

                            </div>

                            <button
                                class="tiny-edit"
                                type="button"
                            >
                                ✎
                            </button>

                        </div>

                    </div>

                    <div class="side-card connects-card">

                        <div class="connects-top">

                            <strong>
                                Connects: 0
                            </strong>

                            <button type="button">
                                View details
                            </button>

                            <button type="button">
                                Buy Connects
                            </button>

                        </div>

                    </div>

                </aside>

            </div>

        </div>

    </main>

    <footer class="footer">

        <span>
            © 2015 - 2026 Freelvoro® Global LLC
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

<div
    class="profile-edit-overlay"
    id="profileEditOverlay"
    aria-hidden="true"
>

    <div
        class="profile-edit-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="profileEditTitle"
    >

        <button
            type="button"
            class="profile-edit-close"
            id="profileEditClose"
            aria-label="Close"
        >
            ×
        </button>

        <h2 id="profileEditTitle">
            Edit profile
        </h2>

        <form
            id="profileEditForm"
            action="update_profile.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <input
    type="hidden"
    name="email"
    value="<?= htmlspecialchars($freelancer['email'] ?? '') ?>"
>

            <div class="profile-photo-edit">

                <div class="profile-photo-preview">

                    <?php if (!empty($freelancer["profile_pic"])): ?>

                        <img
                            id="profilePhotoPreview"
                            src="<?= htmlspecialchars(
                                $freelancer["profile_pic"]
                            ) ?>"
                            alt="Profile picture"
                        >

                    <?php else: ?>

                        <span id="profilePhotoInitials">
                            <?= htmlspecialchars(
                                $freelancer["initials"]
                            ) ?>
                        </span>

                    <?php endif; ?>

                </div>

                <label
                    for="profilePhoto"
                    class="change-photo-button"
                >
                    Change photo
                </label>

                <input
                    type="file"
                    id="profilePhoto"
                    name="profile_pic"
                    accept="image/jpeg,image/png,image/webp"
                    hidden
                >

            </div>

            <div class="profile-form-field">

                <label for="firstName">
                    First name
                </label>

                <input
                    type="text"
                    id="firstName"
                    name="first_name"
                    value="<?= htmlspecialchars(
                        $firstName
                    ) ?>"
                    required
                >

            </div>

            <div class="profile-form-field">

                <label for="lastName">
                    Last name
                </label>

                <input
                    type="text"
                    id="lastName"
                    name="last_name"
                    value="<?= htmlspecialchars(
                        $lastName
                    ) ?>"

                >

            </div>

            <div class="profile-form-field">

                <label for="country">
                    Country
                </label>

                <input
                    type="text"
                    id="country"
                    name="country"
                    value="<?= htmlspecialchars(
                        $user["country"] ?? ""
                    ) ?>"
                >

            </div>

            <div class="profile-edit-actions">

                <button
                    type="button"
                    class="profile-cancel-button"
                    id="profileEditCancel"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="profile-save-button"
                >
                    Save
                </button>

            </div>

        </form>

    </div>

</div>

<script src="fprofile.js?v=102"></script>

</body>

</html>
