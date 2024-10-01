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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Profile</title>
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
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
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
    <h1>Add New Profile</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error-message"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name">

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name">

        <label for="email">Email:</label>
        <input type="text" name="email" id="email">

        <label for="headline">Headline:</label>
        <input type="text" name="headline" id="headline">

        <label for="summary">Summary:</label>
        <textarea name="summary" id="summary"></textarea>

        <input type="submit" value="Add">
    </form>
</div>

</body>
</html>
