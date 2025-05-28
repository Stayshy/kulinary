<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header>
    <nav>
        <a href="index.php" <?php echo $current_page == 'index.php' ? 'class="active"' : ''; ?>>Главная</a>
        <a href="cookbook.php" <?php echo $current_page == 'cookbook.php' ? 'class="active"' : ''; ?>>Кулинарная книга</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="user.php" <?php echo $current_page == 'user.php' ? 'class="active"' : ''; ?>>Мои рецепты</a>
            <a href="logout.php">Выйти</a>
        <?php else: ?>
            <a href="login.php" <?php echo $current_page == 'login.php' ? 'class="active"' : ''; ?>>Войти</a>
            <a href="register.php" <?php echo $current_page == 'register.php' ? 'class="active"' : ''; ?>>Регистрация</a>
        <?php endif; ?>
    </nav>
</header>