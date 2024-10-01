<?php
global $pdo;
session_start();

require_once '../includes/database.php';

// Fetch profiles from the database
$stmt = $pdo->query("SELECT * FROM Profile");
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions a {
            margin-right: 10px;
        }

        .actions a:last-child {
            margin-right: 0;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn-container a {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
        }

        .btn-container a:hover {
            background-color: #218838;
        }

        .logged-in {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        .logout-link {
            margin-top: 10px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Profiles</h1>

    <!-- Display login information -->
    <?php if (isset($_SESSION['name'])): ?>
        <p class="logged-in">Logged in as <strong><?= htmlspecialchars($_SESSION['name']) ?></strong></p>
        <div class="btn-container">
            <a href="add.php">Add New Profile</a>
        </div>
        <p class="logout-link"><a href="logout.php">Logout</a></p>
    <?php else: ?>
        <p class="logged-in"><a href="login.php">Please log in</a></p>
    <?php endif; ?>

    <!-- Profile Table -->
    <table>
        <tr>
            <th>Name</th>
            <th>Headline</th>
            <th>Action</th>
        </tr>

        <?php foreach ($profiles as $profile): ?>
            <tr>
                <td>
                    <a href="view.php?profile_id=<?= $profile['profile_id'] ?>">
                        <?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($profile['headline']) ?></td>
                <td class="actions">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile['user_id']): ?>
                        <a href="edit.php?profile_id=<?= $profile['profile_id'] ?>">Edit</a> /
                        <a href="delete.php?profile_id=<?= $profile['profile_id'] ?>">Delete</a>
                    <?php else: ?>
                        No action available
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
