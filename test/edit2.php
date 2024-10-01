<?php
global $pdo;
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if (!isset($_GET['profile_id'])) {
    die("Missing profile_id");
}

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid");
$stmt->execute(array(":pid" => $_GET['profile_id'], ":uid" => $_SESSION['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if ($profile === false) {
    die("Profile not found or unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email address must contain @";
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    $stmt = $pdo->prepare('UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':pid' => $_POST['profile_id'],
        ':uid' => $_SESSION['user_id']
    ));
    $_SESSION['success'] = "Profile updated";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
</head>
<body>
<h1>Edit Profile</h1>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color:red;"><?= $_SESSION['error'] ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="profile_id" value="<?= $profile['profile_id'] ?>">
    <p>First Name: <input type="text" name="first_name" value="<?= htmlspecialchars($profile['first_name']) ?>"></p>
    <p>Last Name: <input type="text" name="last_name" value="<?= htmlspecialchars($profile['last_name']) ?>"></p>
    <p>Email: <input type="text" name="email" value="<?= htmlspecialchars($profile['email']) ?>"></p>
    <p>Headline: <input type="text" name="headline" value="<?= htmlspecialchars($profile['headline']) ?>"></p>
    <p>Summary: <textarea name="summary"><?= htmlspecialchars($profile['summary']) ?></textarea></p>
    <p><input type="submit" value="Save"></p>
</form>

</body>
</html>
