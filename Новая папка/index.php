<?php
require 'includes/db.php';
session_start();

// Фильтрация
$where = [];
$params = [];
if (!empty($_GET['ingredient_id'])) {
    $where[] = "ri.ingredient_id = ?";
    $params[] = (int)$_GET['ingredient_id'];
}
if (isset($_GET['gluten_free'])) {
    $where[] = "i.is_gluten = 0";
}
if (isset($_GET['vegan'])) {
    $where[] = "i.is_vegan = 1";
}
if (!empty($_GET['search'])) {
    $where[] = "(r.title LIKE ? OR r.description LIKE ?)";
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
}

$sql = "SELECT DISTINCT r.*, c.name AS category_name FROM recipes r 
        JOIN categories c ON r.category_id = c.id 
        LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id 
        LEFT JOIN ingredients i ON ri.ingredient_id = i.id";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ingredients = $pdo->query("SELECT * FROM ingredients")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Кулинарный портал</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<main>
    <h1>Лента рецептов</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="add_recipe.php" class="btn">Добавить рецепт</a>
    <?php endif; ?>

    <form method="GET" action="">
        <label>Поиск: <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"></label>
        <button type="submit" class="btn">Найти</button>
    </form>

    <!-- Фильтры -->
    <form method="GET" action="">
        <label>Фильтр по ингредиентам:</label>
        <select name="ingredient_id">
            <option value="">Все</option>
            <?php foreach ($ingredients as $ingredient): ?>
                <option value="<?php echo $ingredient['id']; ?>" <?php echo (isset($_GET['ingredient_id']) && $_GET['ingredient_id'] == $ingredient['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($ingredient['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label><input type="checkbox" name="gluten_free" <?php echo isset($_GET['gluten_free']) ? 'checked' : ''; ?>> Без глютена</label>
        <label><input type="checkbox" name="vegan" <?php echo isset($_GET['vegan']) ? 'checked' : ''; ?>> Веган</label>
        <button type="submit" class="btn">Фильтровать</button>
    </form>

    <!-- Лента рецептов -->
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