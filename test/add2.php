<?php
global $pdo;
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email address must contain @";
        header("Location: add.php");
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']
    ));
    $_SESSION['success'] = "Profile added";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Profile</title>
</head>
<body>
<h1>Add New Profile</h1>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color:red;"><?= $_SESSION['error'] ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="post">
    <p>First Name: <input type="text" name="first_name"></p>
    <p>Last Name: <input type="text" name="last_name"></p>
    <p>Email: <input type="text" name="email"></p>
    <p>Headline: <input type="text" name="headline"></p>
    <p>Summary: <textarea name="summary"></textarea></p>
    <p><input type="submit" value="Add"></p>
</form>

</body>
</html>
