<?php
require 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $errors = [];

    // Валидация
    if (empty($username) || strlen($username) < 3) {
        $errors[] = 'Имя пользователя должно быть не короче 3 символов';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Пароль должен быть не короче 6 символов';
    }

    // Проверка уникальности
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'Email уже зарегистрирован';
    }
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'Имя пользователя уже занято';
    }

    // Регистрация
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hashed_password]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Ошибка регистрации: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<main>
    <h1>Регистрация</h1>
    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST">
        <label>Имя пользователя: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <button type="submit" class="btn">Зарегистрироваться</button>
    </form>
    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</main>
</body>
</html>