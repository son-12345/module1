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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Profile</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error-message"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="profile_id" value="<?= $profile['profile_id'] ?>">

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($profile['first_name']) ?>">

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($profile['last_name']) ?>">

        <label for="email">Email:</label>
        <input type="text" name="email" value="<?= htmlspecialchars($profile['email']) ?>">

        <label for="headline">Headline:</label>
        <input type="text" name="headline" value="<?= htmlspecialchars($profile['headline']) ?>">

        <label for="summary">Summary:</label>
        <textarea name="summary"><?= htmlspecialchars($profile['summary']) ?></textarea>

        <input type="submit" value="Save">
    </form>
</div>

</body>
</html>
