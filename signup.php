<?php
session_start();
$role = $_SESSION["role"] ?? $_GET["role"] ?? "";

if ($role !== "client" && $role !== "freelancer") {
    header("Location: selectrole.php");
    exit;
}

include "config.php";

$role = $_SESSION["role"] ?? $_GET["role"] ?? "";

if ($role !== "client" && $role !== "freelancer") {
    header("Location: selectrole.php");
    exit;
}

$message = "";
$messageType = "";

$firstName = "";
$lastName = "";
$email = "";
$country = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $userPassword = $_POST["password"] ?? "";
    $country = trim($_POST["country"] ?? "");

    $terms = isset($_POST["terms"]) ? 1 : 0;

    if (
        $firstName === "" ||
        $lastName === "" ||
        $email === "" ||
        $userPassword === "" ||
        $country === ""
    ) {

        $message = "Please complete all required fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif (strlen($userPassword) < 8) {

        $message = "Password must contain at least 8 characters.";
        $messageType = "error";

    } elseif (!$terms) {

        $message = "You must agree to the Terms of Service.";
        $messageType = "error";

    } else {

        $check = $conn->prepare(
            "SELECT email FROM users WHERE email = ? LIMIT 1"
        );

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "An account with this email already exists.";
            $messageType = "error";

            $check->close();

        } else {

            $check->close();

            $hashedPassword = password_hash(
                $userPassword,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (
                    email,
                    first_name,
                    last_name,
                    password,
                    country,
                    user_type
                )
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssss",
                $email,
                $firstName,
                $lastName,
                $hashedPassword,
                $country,
                $role
            );

            if ($stmt->execute()) {

                $_SESSION["user_email"] = $email;
                $_SESSION["user_type"] = $role;
                $_SESSION["user_name"] = $firstName . " " . $lastName;

                if ($role === "freelancer") {

                    header("Location: fdashboard.php");
                    exit;

                } elseif ($role === "client") {

                    header("Location: cdashboard.php");
                    exit;
                }

            } else {

                $message = "Something went wrong while creating your account.";
                $messageType = "error";
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

    <title>Create Account | Freelvoro</title>

    <link
        rel="stylesheet"
        href="signup.css?v=10"
    >

</head>

<body>

<header class="signup-header">

    <a href="index.php" class="logo">
        Freelvoro
    </a>

    <div class="header-right">

        <span>Looking for work?</span>

        <a href="#">
            Apply as talent
        </a>

    </div>

</header>

<main class="signup-page">

    <section class="signup-container">

        <h1>
            Complete your account
        </h1>

        <p class="signup-subtitle">
            Create your Freelvoro account
        </p>

        <div class="social-buttons">

            <button
                type="button"
                class="social-btn apple-btn"
            >
                
                <span>Continue with Apple</span>
            </button>

            <button
                type="button"
                class="social-btn google-btn"
            >
                <span class="google-icon">G</span>
                <span>Continue with Google</span>
            </button>

        </div>

        <div class="or-divider">

            <span>or</span>

        </div>

        <?php if ($message !== ""): ?>

            <div class="form-message <?php echo $messageType; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>

        <form
            method="POST"
            action="signup.php"
            id="signupForm"
            novalidate
        >

            <div class="name-row">

                <div class="form-group">

                    <label for="firstName">
                        First name
                    </label>

                    <input
                        type="text"
                        id="firstName"
                        name="first_name"
                        value="<?php echo htmlspecialchars($firstName ?? ''); ?>"
                        autocomplete="given-name"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="lastName">
                        Last name
                    </label>

                    <input
                        type="text"
                        id="lastName"
                        name="last_name"
                        value="<?php echo htmlspecialchars($lastName ?? ''); ?>"
                        autocomplete="family-name"
                        required
                    >

                </div>

            </div>

            <div class="form-group">

                <label for="email">
                    Work email address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    autocomplete="email"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password (8 or more characters)"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="passwordToggle"
                        aria-label="Show password"
                    >
                        👁
                    </button>

                </div>

                <div
                    class="password-strength"
                    id="passwordStrength"
                ></div>

            </div>

            <div class="form-group">

                <label for="country">
                    Country
                </label>

                <select
                    id="country"
                    name="country"
                    required
                >

                    <option
                        value="Bangladesh"
                        <?php echo (($country ?? '') === "Bangladesh") ? "selected" : ""; ?>
                    >
                        Bangladesh
                    </option>

                    <option value="India">
                        India
                    </option>

                    <option value="Pakistan">
                        Pakistan
                    </option>

                    <option value="United States">
                        United States
                    </option>

                    <option value="United Kingdom">
                        United Kingdom
                    </option>

                    <option value="Canada">
                        Canada
                    </option>

                    <option value="Australia">
                        Australia
                    </option>

                </select>

            </div>

            <label class="checkbox-row">

                <input
                    type="checkbox"
                    name="tips"
                    value="1"
                    checked
                >

                <span>
                    Send me emails with tips on how to find
                    talent that fits my needs.
                </span>

            </label>

            <label class="checkbox-row terms-row">

                <input
                    type="checkbox"
                    name="terms"
                    id="terms"
                    value="1"
                    required
                >

                <span>

                    Yes, I understand and agree to the

                    <a href="#">
                        Terms of Service
                    </a>

                    including the

                    <a href="#">
                        User Agreement
                    </a>

                    and

                    <a href="#">
                        Privacy Policy
                    </a>.

                </span>

            </label>

            <button
                type="submit"
                class="create-btn"
            >
                Create my account
            </button>

        </form>

        <div class="login-text">

            Already have an account?

            <a href="login.php">
                Log in
            </a>

        </div>

    </section>

</main>

<script src="signup.js"></script>

</body>
</html>
