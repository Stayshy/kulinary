<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = (int)$_POST['recipe_id'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if (!empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, recipe_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $recipe_id, $comment]);
    }

    header("Location: recipe.php?id=$recipe_id");
    exit;
}
?>