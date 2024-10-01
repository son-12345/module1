<?php
global $pdo;
session_start();
require_once '../includes/database.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $salt = 'XyZzy12*_';
    $check = hash('md5', $salt . $_POST['pass']);

    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
    } else {
        $error_message = "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please Log In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
        }

        h1 {
            font-size: 1.5em;
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }

        .hint {
            font-size: 0.9em;
            text-align: center;
            margin-top: 10px;
        }
    </style>

    <script>
        function doValidate() {
            var email = document.getElementById('email').value;
            var pass = document.getElementById('pass').value;

            if (email === "" || pass === "") {
                alert("Both fields must be filled out!");
                return false;
            }

            var re = /^([a-zA-Z0-9_\.\-])+\@([a-zA-Z0-9_\.\-])+\.([a-zA-Z]{2,5})$/;
            if (!re.test(email)) {
                alert("Invalid email address");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<div class="login-container">
    <h1>Please Log In</h1>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?= $error_message ?></p>
    <?php endif; ?>

    <form method="post" onsubmit="return doValidate();">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" placeholder="Email" required><br>

        <label for="pass">Password</label>
        <input type="password" name="pass" id="pass" placeholder="Password" required><br>

        <input type="submit" value="Login">
    </form>

    <p class="hint">For a password hint, view source and find an account.</p>
</div>

</body>
</html>
