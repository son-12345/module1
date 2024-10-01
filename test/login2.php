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
    <title>Please Login</title>
    <script>
        function doValidate() {
            var email = document.getElementById('email').value;
            var pass = document.getElementById('pass').value;

            if (email === "" || pass === "") {
                alert("Both fields must be filled out!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<h1>Login</h1>

<?php if (!empty($error_message)): ?>
    <p style="color: red;"><?= $error_message ?></p>
<?php endif; ?>

<form method="post" onsubmit="return doValidate();">
    <input type="text" name="email" id="email" placeholder="Email" required><br>
    <input type="password" name="pass" id="pass" placeholder="Password" required><br>
    <input type="submit" value="Login">
</form>

</body>
</html>
