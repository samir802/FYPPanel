<?php
// Check if the user is already logged in
if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4b6cb7, #182848);
            animation: gradientAnimation 15s ease infinite;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .login-card-body {
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            background-color: #fff;
        }

        .login-box-msg {
            font-size: 18px;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            border-radius: 5px;
            height: 50px;
        }

        .btn-primary {
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 18px;
            width: 100%;
            /* Set width to 100% */
        }

        /* Popup */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            color: #fff;
            z-index: 9999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-card-body">
                    <h1 class="login-box-msg">Login</h1>
                    <form id="loginForm" method="POST" action="./functions/login.php">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="popup" id="errorPopup">
        <p id="popupMessage"></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            <?php if (isset($_SESSION['login_error'])): ?>
                showPopup("<?php echo $_SESSION['login_error']; ?>");
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>
        });

        function showPopup(message) {
            document.getElementById("popupMessage").innerText = message;
            document.getElementById("errorPopup").style.display = "block";
        }
    </script>
</body>

</html>