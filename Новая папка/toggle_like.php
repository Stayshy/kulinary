<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = (int)$_POST['recipe_id'];
    $user_id = $_SESSION['user_id'];

    // Проверка, есть ли лайк
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$user_id, $recipe_id]);
    $exists = $stmt->fetchColumn() > 0;

    if ($exists) {
        // Удалить лайк
        $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user_id, $recipe_id]);
    } else {
        // Добавить лайк
        $stmt = $pdo->prepare("INSERT INTO likes (user_id, recipe_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $recipe_id]);
    }

    header("Location: recipe.php?id=$recipe_id");
    exit;
}
?>