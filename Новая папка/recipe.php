<?php
require 'includes/db.php';
session_start();

$recipe_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT r.*, c.name AS category_name FROM recipes r JOIN categories c ON r.category_id = c.id WHERE r.id = ?");
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    die("Рецепт не найден");
}

$stmt = $pdo->prepare("SELECT i.name, ri.quantity, i.is_gluten, i.is_vegan FROM recipe_ingredients ri JOIN ingredients i ON ri.ingredient_id = i.id WHERE ri.recipe_id = ?");
$stmt->execute([$recipe_id]);
$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$has_gluten = false;
$is_vegan = true;
foreach ($ingredients as $ind) {
    if ($ind['is_gluten']) $has_gluten = true;
    if (!$ind['is_vegan']) $is_vegan = false;
}

// Проверка лайка
$liked = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$_SESSION['user_id'], $recipe_id]);
    $liked = $stmt->fetchColumn() > 0;
}

// Получение комментариев
$stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.recipe_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$recipe_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($recipe['title']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>
<body>
<?php include 'includes/header.php'; ?>
<main>
    <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
    <img src="images/<?php echo htmlspecialchars($recipe['image'] ?: 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
    <div class="indicators">
        <?php if ($has_gluten): ?>
            <span class="indicator gluten" title="Содержит глютен"></span>
        <?php endif; ?>
        <?php if ($is_vegan): ?>
            <span class="indicator vegan" title="Веганское блюдо"></span>
        <?php endif; ?>
    </div>
    <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

    <!-- Калькулятор порций -->
    <label>Количество порций: <input type="number" id="servings" value="<?php echo htmlspecialchars($recipe['servings']); ?>" min="1"></label>
    <button onclick="calculateServings(<?php echo htmlspecialchars($recipe['servings']); ?>)">Рассчитать</button>

    <h2>Ингредиенты</h2>
    <ul id="ingredient-list">
        <?php foreach ($ingredients as $ingredient): ?>
            <li data-quantity="<?php echo htmlspecialchars($ingredient['quantity']); ?>">
                <?php echo htmlspecialchars($ingredient['name']) . ': ' . htmlspecialchars($ingredient['quantity']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Инструкции</h2>
    <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>

    <!-- Лайки -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" action="toggle_like.php">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <button type="submit" class="btn"><?php echo $liked ? 'Убрать лайк' : 'Лайк'; ?></button>
        </form>
    <?php endif; ?>

    <!-- Комментарии -->
    <h2>Комментарии</h2>
    <?php if ($comments): ?>
        <ul class="comment-list">
            <?php foreach ($comments as $comment): ?>
                <li>
                    <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                    <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                    <small><?php echo $comment['created_at']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Комментариев пока нет.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" action="add_comment.php">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <textarea name="comment" required placeholder="Ваш комментарий"></textarea>
            <button type="submit" class="btn">Отправить</button>
        </form>
    <?php endif; ?>

    <a href="generate_ttk.php?id=<?php echo $recipe_id; ?>" class="btn">Скачать ТТК</a>
</main>
</body>
</html>