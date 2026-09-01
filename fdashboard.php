
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Find Work - Freelvoro</title>

    <link
        rel="stylesheet"
        href="fdashboard.css?v=106"
    >

    <link
        rel="stylesheet"
        href="comprofile.css?v=15"
    >

</head>

<body>

<div class="app">

    <?php include "fmenu.php"; ?>

    <main class="main">

        <div
            class="info-banner"
            id="infoBanner"
        >

            <div class="info-left">

                <span class="info-icon">
                    i
                </span>

                <span>

                    To do:

                    <strong>
                        Take the working style assessment.
                    </strong>

                    Clients trust and hire freelancers who
                    highlight their working style on their profile.

                </span>

            </div>

            <button
                type="button"
                class="close-banner"
                id="closeBanner"
                aria-label="Close"
            >
                ×
            </button>

        </div>

        <div class="content">

            <section class="direct-contracts">

                <div class="direct-text">

                    <div class="direct-small">
                        Direct Contracts
                    </div>

                    <h2>
                        Maximize your earnings with a low 5%
                        service fee when you bring new clients
                        to FreelVoro.
                    </h2>

                    <button
                        type="button"
                        class="contract-button"
                    >
                        Create contract
                    </button>

                </div>

                <div class="direct-illustration">

                    <div class="phone">

                        <div class="phone-screen">

                            <div class="phone-line"></div>
                            <div class="phone-line"></div>
                            <div class="phone-line"></div>

                            <div class="phone-dot"></div>

                        </div>

                    </div>

                    <div class="document">

                        <div></div>
                        <div></div>
                        <div></div>

                        <span></span>

                    </div>

                </div>

            </section>

            <div class="job-search">

                <span>
                    ⌕
                </span>

                <input
                    type="text"
                    id="jobSearch"
                    placeholder="Search for jobs"
                    autocomplete="off"
                >

            </div>

            <div class="jobs-heading">

                <h1>
                    Jobs you might like
                </h1>

                <button
                    type="button"
                    class="filter-button"
                    id="filterButton"
                >

                    <span>
                        ⚙
                    </span>

                    Filters

                </button>

            </div>

            <div class="tabs">

                <button
                    type="button"
                    class="tab active"
                    data-tab="best"
                >
                    Best matches
                </button>

                <button
                    type="button"
                    class="tab"
                    data-tab="recent"
                >
                    Most recent
                </button>

                <button
                    type="button"
                    class="tab"
                    data-tab="saved"
                >
                    Saved jobs
                </button>

                <button
                    type="button"
                    class="tab"
                    data-tab="invites"
                >
                    Invites
                </button>

            </div>

            <div
                class="job-list"
                id="jobList"
            >

                <?php if (empty($jobs)): ?>

                    <div
                        class="no-results"
                        id="noResults"
                        style="display:block;"
                    >
                        No jobs found.
                    </div>

                <?php else: ?>

                    <?php foreach ($jobs as $job): ?>

                        <?php

                        $skills = [];

                        if (
                            isset($job["skills"]) &&
                            trim((string)$job["skills"]) !== ""
                        ) {

                            $decoded = json_decode(
                                $job["skills"],
                                true
                            );

                            if (is_array($decoded)) {

                                $skills = $decoded;

                            } else {

                                $skills = array_filter(
                                    array_map(
                                        "trim",
                                        explode(
                                            ",",
                                            $job["skills"]
                                        )
                                    )
                                );

                            }
                        }

                        ?>

                        <article
                            class="job-card"
                            data-job-id="<?= (int)$job["id"] ?>"
                            data-category="<?= htmlspecialchars(
                                $job["category"] ?? "",
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>"
                        >

                            <div class="job-top">

                                <div>

                                    <div class="job-meta">

                                        <?php if (
                                            isset($job["posted"]) &&
                                            $job["posted"] !== ""
                                        ): ?>

                                            <?= htmlspecialchars(
                                                $job["posted"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>

                                        <?php endif; ?>

                                        <?php if (
                                            !empty($job["posted"]) &&
                                            !empty($job["proposals"])
                                        ): ?>

                                            <span>
                                                •
                                            </span>

                                        <?php endif; ?>

                                        <?php if (
                                            isset($job["proposals"]) &&
                                            $job["proposals"] !== ""
                                        ): ?>

                                            <strong>

                                                Proposals:

                                                <?= htmlspecialchars(
                                                    $job["proposals"],
                                                    ENT_QUOTES,
                                                    "UTF-8"
                                                ) ?>

                                            </strong>

                                        <?php endif; ?>

                                    </div>

                                    <h2 class="job-title">

                                        <?= htmlspecialchars(
                                            $job["title"] ?? "",
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </h2>

                                </div>

                                <div class="job-actions">

                                    <button
                                        type="button"
                                        class="action-button dislike"
                                        aria-label="Not interested"
                                    >
                                        ♡
                                    </button>

                                    <button
                                        type="button"
                                        class="action-button save-job"
                                        aria-label="Save job"
                                    >
                                        ♡
                                    </button>

                                </div>

                            </div>

                            <div class="job-type">

                                <?php if (
                                    !empty($job["type"])
                                ): ?>

                                    <span>

                                        <?= htmlspecialchars(
                                            $job["type"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    !empty($job["type"]) &&
                                    !empty($job["level"])
                                ): ?>

                                    <span>
                                        •
                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    !empty($job["level"])
                                ): ?>

                                    <span>

                                        <?= htmlspecialchars(
                                            $job["level"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    (
                                        !empty($job["type"]) ||
                                        !empty($job["level"])
                                    ) &&
                                    !empty($job["budget"])
                                ): ?>

                                    <span>
                                        •
                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    !empty($job["budget"])
                                ): ?>

                                    <span>

                                        Est. Budget:

                                        <?= htmlspecialchars(
                                            $job["budget"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                            </div>

                            <?php if (
                                !empty($job["description"])
                            ): ?>

                                <p class="job-description">

                                    <?= htmlspecialchars(
                                        $job["description"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ) ?>

                                </p>

                            <?php endif; ?>

                            <?php if (!empty($skills)): ?>

                                <div class="skills">

                                    <?php foreach (
                                        $skills as $skill
                                    ): ?>

                                        <?php

                                        if (
                                            !is_scalar($skill)
                                        ) {
                                            continue;
                                        }

                                        if (
                                            trim(
                                                (string)$skill
                                            ) === ""
                                        ) {
                                            continue;
                                        }

                                        ?>

                                        <span class="skill">

                                            <?= htmlspecialchars(
                                                (string)$skill,
                                                ENT_QUOTES,
                                                "UTF-8"
                                            ) ?>

                                        </span>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                            <div class="client-info">

                                <?php if (
                                    !empty($job["verified"])
                                ): ?>

                                    <span class="verified">

                                        <span>
                                            ✓
                                        </span>

                                        Payment verified

                                    </span>

                                <?php else: ?>

                                    <span class="unverified">

                                        <span>
                                            ✓
                                        </span>

                                        Payment unverified

                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    isset($job["rating"]) &&
                                    $job["rating"] !== ""
                                ): ?>

                                    <span class="stars">

                                        ★

                                        <?= htmlspecialchars(
                                            $job["rating"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    isset($job["spent"]) &&
                                    $job["spent"] !== ""
                                ): ?>

                                    <span>

                                        <?= htmlspecialchars(
                                            $job["spent"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                                <?php if (
                                    isset($job["country"]) &&
                                    $job["country"] !== ""
                                ): ?>

                                    <span class="location">

                                        ◇

                                        <?= htmlspecialchars(
                                            $job["country"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                            </div>

                        </article>

                    <?php endforeach; ?>

                    <div
                        class="no-results"
                        id="noResults"
                        style="display:none;"
                    >
                        No jobs found.
                    </div>

                <?php endif; ?>

            </div>

        </div>

        <aside class="right-panel">

            <div class="right-profile">

                <div class="right-profile-top">

                    <div class="large-avatar">
                        <?php if (!empty($freelancer["profile_pic"])): ?>
                            <img
                                src="<?= htmlspecialchars($freelancer["profile_pic"], ENT_QUOTES, "UTF-8") ?>"
                                alt="<?= htmlspecialchars($freelancer["name"], ENT_QUOTES, "UTF-8") ?>"
                            >
                        <?php else: ?>
                            <?= htmlspecialchars($freelancer["initials"], ENT_QUOTES, "UTF-8") ?>
                        <?php endif; ?>
                    </div>

                    <div>

                        <h3>

                            <?= htmlspecialchars(
                                $freelancer["name"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </h3>

                        <p>

                            <?= htmlspecialchars(
                                $freelancer["title"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </p>

                    </div>

                </div>

                <div class="profile-row">

                    <span>
                        Profile Visibility
                    </span>

                    <a href="#">

                        <?= htmlspecialchars(
                            $freelancer["profile_visibility"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>

                    </a>

                </div>

                <div
                    class="complete-profile"
                    id="completeProfileButton"
                    role="button"
                    tabindex="0"
                    aria-label="Complete your profile"
                >

                    <div class="complete-header">

                        <strong>
                            Complete your profile
                        </strong>

                        <span>

                            <?= (int)$freelancer[
                                "profile_complete"
                            ] ?>%

                        </span>

                    </div>

                    <div class="progress">

                        <div
                            style="
                                width:
                                <?= (int)$freelancer[
                                    "profile_complete"
                                ] ?>%;
                            "
                        ></div>

                    </div>

                </div>

                <div class="identity">

                    <div class="identity-title">

                        <span class="identity-icon">
                            ✓
                        </span>

                        <strong>
                            Identity verification
                        </strong>

                    </div>

                    <p>

                        Increase your profile visibility
                        in search results and win more work
                        with an IDV Badge.

                    </p>

                    <a href="#">
                        Get an IDV Badge
                    </a>

                </div>

                <div class="reach-clients">

                    <div class="reach-header">

                        <strong>
                            Reach more clients
                        </strong>

                        <span>
                            ⌃
                        </span>

                    </div>

                    <div class="availability">

                        <div>

                            Availability badge

                            <span class="question">
                                i
                            </span>

                        </div>

                        <button type="button">

                            <?= htmlspecialchars(
                                $freelancer["availability"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </button>

                    </div>

                </div>

            </div>

        </aside>

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

<?php include "comprofile.php"; ?>

<script src="fdashboard.js?v=105"></script>

</body>

</html>
