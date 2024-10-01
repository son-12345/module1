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

$stmt = $pdo->prepare("DELETE FROM Profile WHERE profile_id = :pid AND user_id = :uid");
$stmt->execute(array(":pid" => $_GET['profile_id'], ":uid" => $_SESSION['user_id']));

$_SESSION['success'] = "Profile deleted";
header("Location: index.php");
?>
