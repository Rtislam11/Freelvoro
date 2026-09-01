<?php
session_start();

include "config.php";

$errors = [];
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $loginPassword = $_POST["password"] ?? "";

    if ($email === "") {

        $errors["email"] = "This field is required";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors["email"] = "Enter a valid email address";

    }

    if ($loginPassword === "") {

        $errors["password"] = "This field is required";

    }

    if (empty($errors)) {

        $stmt = $conn->prepare(
            "SELECT email, password
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if (!$stmt) {

            $errors["email"] =
                "Unable to connect to the login system.";

        } else {

            $stmt->bind_param("s", $email);

            $stmt->execute();

            $result = $stmt->get_result();

            $user = $result->fetch_assoc();

            if (!$user) {

                $errors["email"] =
                    "We couldn't find an account with this email.";

            }

            elseif (
                !password_verify(
                    $loginPassword,
                    $user["password"]
                )
            ) {

                $errors["password"] =
                    "The password you entered is incorrect.";

            }

            else {

                session_regenerate_id(true);

                $_SESSION["logged_in"] = true;

                $_SESSION["user_email"] =
                    $user["email"];

                header("Location: fdashboard.php");

                exit;
            }

            $stmt->close();
        }
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Log in | Freelvoro</title>

    <link
        rel="stylesheet"
        href="login.css"
    >

</head>

<body>

<header class="login-header">

    <a
        href="index.php"
        class="logo"
    >
        Freelvoro
    </a>

</header>

<main class="login-page">

    <section class="login-card">

        <?php if (!empty($errors)): ?>

            <div
                class="top-error"
                id="topError"
            >

                <span class="error-icon">
                    !
                </span>

                <span>
                    Please fix the errors below.
                </span>

                <button
                    type="button"
                    id="closeError"
                    aria-label="Close error"
                >
                    ×
                </button>

            </div>

        <?php endif; ?>

        <h1>
            Log in to Freelvoro
        </h1>

        <form
            action="login.php"
            method="POST"
            id="loginForm"
            novalidate
        >

            <div
                class="form-group
                <?php
                echo isset($errors["email"])
                    ? "has-error"
                    : "";
                ?>"
            >

                <div class="input-wrapper">

                    <span class="input-icon">
                        ♙
                    </span>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Username or Email"
                        value="<?php
                        echo htmlspecialchars(
                            $email,
                            ENT_QUOTES,
                            "UTF-8"
                        );
                        ?>"
                        autocomplete="email"
                    >

                </div>

                <?php if (isset($errors["email"])): ?>

                    <div class="field-error">

                        <span>
                            ⓘ
                        </span>

                        <?php
                        echo htmlspecialchars(
                            $errors["email"],
                            ENT_QUOTES,
                            "UTF-8"
                        );
                        ?>

                    </div>

                <?php endif; ?>

            </div>

            <div
                class="form-group
                <?php
                echo isset($errors["password"])
                    ? "has-error"
                    : "";
                ?>"
            >

                <div class="input-wrapper">

                    <span class="input-icon">
                        🔒
                    </span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        autocomplete="current-password"
                    >

                    <button
                        type="button"
                        id="passwordToggle"
                        class="password-toggle"
                        aria-label="Show password"
                        aria-pressed="false"
                    >
                        👁
                    </button>

                </div>

                <?php if (isset($errors["password"])): ?>

                    <div class="field-error">

                        <span>
                            ⓘ
                        </span>

                        <?php
                        echo htmlspecialchars(
                            $errors["password"],
                            ENT_QUOTES,
                            "UTF-8"
                        );
                        ?>

                    </div>

                <?php endif; ?>

            </div>

            <button
                type="submit"
                class="continue-btn"
                id="continueButton"
            >
                Continue
            </button>

        </form>

        <div class="or-divider">

            <span>
                or
            </span>

        </div>

        <button
            type="button"
            class="google-btn"
            id="googleBtn"
        >

            <span class="google-icon">
                G
            </span>

            Continue with Google

        </button>

        <button
            type="button"
            class="apple-btn"
            id="appleBtn"
        >

            <span>
                
            </span>

            Continue with Apple

        </button>

        <div class="signup-text">

            Don't have a Freelvoro account?

            <a href="signup.php">
                Sign Up
            </a>

        </div>

    </section>

</main>

<footer class="login-footer">

    <div class="footer-content">

        <span>
            © 2015 - 2026 Freelvoro Global LLC.
        </span>

        <a href="#">
            Privacy Policy
        </a>

        <span>
            •
        </span>

        <a href="#">
            Your Privacy Choices
        </a>

        <button
            type="button"
            class="privacy-choice"
            title="Privacy Choices"
        >
            ✓
        </button>

    </div>

</footer>

<script src="login.js"></script>

</body>

</html>
