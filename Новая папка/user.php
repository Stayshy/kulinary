<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT r.*, c.name AS category_name FROM recipes r JOIN categories c ON r.category_id = c.id WHERE r.user_id = ?");
$stmt->execute([$user_id]);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои рецепты</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<main>
    <h1>Мои рецепты</h1>
    <div class="recipe-grid">
        <?php foreach ($recipes as $recipe): ?>
            <?php
            $stmt = $pdo->prepare("SELECT i.is_gluten, i.is_vegan FROM recipe_ingredients ri JOIN ingredients i ON ri.ingredient_id = i.id WHERE ri.recipe_id = ?");
            $stmt->execute([$recipe['id']]);
            $indicators = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $has_gluten = false;
            $is_vegan = true;
            foreach ($indicators as $ind) {
                if ($ind['is_gluten']) $has_gluten = true;
                if (!$ind['is_vegan']) $is_vegan = false;
            }
            ?>
            <div class="recipe-card">
                <a href="recipe.php?id=<?php echo $recipe['id']; ?>">
                    <img src="images/<?php echo htmlspecialchars($recipe['image'] ?: 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                </a>
                <div class="indicators">
                    <?php if ($has_gluten): ?>
                        <span class="indicator gluten" title="Содержит глютен"></span>
                    <?php endif; ?>
                    <?php if ($is_vegan): ?>
                        <span class="indicator vegan" title="Веганское блюдо"></span>
                    <?php endif; ?>
                </div>
                <p>Время: <?php echo htmlspecialchars($recipe['prep_time']); ?> мин</p>
                <p>Сложность: <?php echo htmlspecialchars($recipe['difficulty']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>