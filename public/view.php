<?php
global $pdo;
session_start();
require_once '../includes/database.php';

if (empty($_GET['profile_id'])) {
    die("Missing profile_id");
}

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(":pid" => $_GET['profile_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if ($profile === false) {
    die("Profile not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Profile</title>
</head>
<body>
<h1><?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></h1>
<p>Email: <?= htmlspecialchars($profile['email']) ?></p>
<p>Headline: <?= htmlspecialchars($profile['headline']) ?></p>
<p>Summary: <?= htmlspecialchars($profile['summary']) ?></p>
<a href="index.php">Back</a>
</body>
</html>
