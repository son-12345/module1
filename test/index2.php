<?php
global $pdo;
session_start();

require_once '../includes/database.php';

$stmt = $pdo->query("SELECT * FROM Profile");
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile List</title>
</head>
<body>
<h1>Profiles</h1>
<?php if (isset($_SESSION['name'])): ?>
    <p>Logged in as <?= htmlspecialchars($_SESSION['name']) ?></p>
    <p><a href="add.php">Add New Profile</a></p>
    <p><a href="logout.php">Logout</a></p>
<?php else: ?>
    <p><a href="login.php">Please log in</a></p>
<?php endif; ?>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Headline</th>
        <th>Action</th>
    </tr>
    <?php foreach ($profiles as $profile): ?>
        <tr>
            <td><a href="view.php?profile_id=<?= $profile['profile_id'] ?>">
                    <?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?>
                </a></td>
            <td><?= htmlspecialchars($profile['headline']) ?></td>
            <td>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile['user_id']): ?>
                    <a href="edit.php?profile_id=<?= $profile['profile_id'] ?>">Edit</a> /
                    <a href="delete.php?profile_id=<?= $profile['profile_id'] ?>">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
