<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$ingredients = $pdo->query("SELECT * FROM ingredients")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructions = $_POST['instructions'];
    $prep_time = (int)$_POST['prep_time'];
    $difficulty = $_POST['difficulty'];
    $servings = (int)$_POST['servings'];
    $category_id = (int)$_POST['category_id'];
    $ingredient_ids = $_POST['ingredient_id'];
    $quantities = $_POST['quantity'];

    // Загрузка изображения
    $image = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uniqid() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image);
    }

    // Сохранение рецепта
    $stmt = $pdo->prepare("INSERT INTO recipes (user_id, title, description, instructions, prep_time, difficulty, servings, image, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $description, $instructions, $prep_time, $difficulty, $servings, $image, $category_id]);
    $recipe_id = $pdo->lastInsertId();

    // Сохранение ингредиентов
    $stmt = $pdo->prepare("INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity) VALUES (?, ?, ?)");
    for ($i = 0; $i < count($ingredient_ids); $i++) {
        $stmt->execute([$recipe_id, $ingredient_ids[$i], $quantities[$i]]);
    }

    header("Location: recipe.php?id=$recipe_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить рецепт</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<main>
    <h1>Добавить рецепт</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Название: <input type="text" name="title" required></label><br>
        <label>Описание: <textarea name="description"></textarea></label><br>
        <label>Инструкции: <textarea name="instructions" required></textarea></label><br>
        <label>Время приготовления (мин): <input type="number" name="prep_time" required></label><br>
        <label>Сложность:
            <select name="difficulty" required>
                <option value="Легко">Легко</option>
                <option value="Средне">Средне</option>
                <option value="Сложно">Сложно</option>
            </select>
        </label><br>
        <label>Порции: <input type="number" name="servings" value="1" min="1" required></label><br>
        <label>Категория:
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Изображение: <input type="file" name="image"></label><br>

        <h3>Ингредиенты</h3>
        <div id="ingredients">
            <div class="ingredient-row">
                <select name="ingredient_id[]" required>
                    <option value="">Выберите ингредиент</option>
                    <?php foreach ($ingredients as $ingredient): ?>
                        <option value="<?php echo $ingredient['id']; ?>">
                            <?php echo htmlspecialchars($ingredient['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="quantity[]" placeholder="Количество" required>
            </div>
        </div>
        <button type="button" onclick="addIngredientRow()">Добавить ингредиент</button><br>
        <button type="submit">Сохранить рецепт</button>
    </form>
</main>
<script>
    function addIngredientRow() {
        const div = document.createElement('div');
        div.className = 'ingredient-row';
        div.innerHTML = document.querySelector('.ingredient-row').innerHTML;
        document.getElementById('ingredients').appendChild(div);
    }
</script>
</body>
</html>