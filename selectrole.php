<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $role = $_POST["role"] ?? "";

    if ($role === "client" || $role === "freelancer") {

        $_SESSION["role"] = $role;

        if ($role === "client") {
            header("Location: signup.php?role=client");
            exit;
        }

        if ($role === "freelancer") {
            header("Location: signup.php?role=freelancer");
            exit;
        }

    } else {
        $message = "Please select Client or Freelancer.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Welcome to Freelvoro</title>

    <link rel="stylesheet" href="selectrole.css">

</head>

<body>

<header class="top-header">

    <a href="index.php" class="logo">
        Freelvoro
    </a>

</header>


<main class="signup-page">

    <section class="signup-box">

        <h1>Welcome to Freelvoro</h1>

        <p class="subtitle">
            Which describes you best?
        </p>


        <?php if ($message !== ""): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <form
            method="POST"
            action="selectrole.php"
            id="roleForm"
        >

            <input
                type="hidden"
                name="role"
                id="roleInput"
                value=""
            >


            <div class="role-container">


                <!-- CLIENT -->

                <button
                    type="button"
                    class="role-card"
                    data-role="client"
                >

                    <div class="role-icon">

                        <svg
                            viewBox="0 0 64 64"
                            aria-hidden="true"
                        >

                            <circle
                                cx="26"
                                cy="17"
                                r="8"
                            />

                            <path
                                d="M12 43c0-9 6-15 14-15s14 6 14 15"
                            />

                            <rect
                                x="36"
                                y="31"
                                width="18"
                                height="16"
                                rx="2"
                            />

                            <path
                                d="M40 31v-4h10v4"
                            />

                            <path
                                d="M36 37h18"
                            />

                        </svg>

                    </div>


                    <div class="role-name">
                        Client <span>→</span>
                    </div>

                    <div class="role-description">
                        Post jobs and hire
                    </div>

                </button>



                <!-- FREELANCER -->

                <button
                    type="button"
                    class="role-card"
                    data-role="freelancer"
                >

                    <div class="role-icon">

                        <svg
                            viewBox="0 0 64 64"
                            aria-hidden="true"
                        >

                            <circle
                                cx="26"
                                cy="17"
                                r="8"
                            />

                            <path
                                d="M12 43c0-9 6-15 14-15s14 6 14 15"
                            />

                            <rect
                                x="35"
                                y="29"
                                width="23"
                                height="16"
                                rx="2"
                            />

                            <path
                                d="M40 45h13"
                            />

                            <circle
                                cx="46"
                                cy="37"
                                r="1.5"
                            />

                        </svg>

                    </div>


                    <div class="role-name">
                        Freelancer <span>→</span>
                    </div>

                    <div class="role-description">
                        Work and get paid
                    </div>

                </button>

            </div>


            <button
                type="submit"
                class="continue-btn"
                id="continueBtn"
            >
                Continue
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


<footer class="signup-footer">

    <div>

        © 2026 Freelvoro Global LLC.

        <a href="#">
            Privacy Policy
        </a>

        <span>•</span>

        <a href="#">
            Your Privacy Choices
        </a>

    </div>

</footer>


<script src="selectrole.js"></script>

</body>
</html>