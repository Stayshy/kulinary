<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="container mx-auto p-4">
    <!-- Menu -->
    <nav class="bg-orange-500 text-white p-4 rounded-lg mb-8">
        <h2 class="text-xl font-bold">Меню</h2>
        <ul class="space-y-2 mt-4">
            <li><a href="feed.html" class="hover:underline">Лента рецептов</a></li>
            <li><a href="profile.html" class="hover:underline">Профиль</a></li>
            <li><a href="favorites.html" class="hover:underline">Избранное</a></li>
            <li><a href="achievements.html" class="hover:underline">Достижения</a></li>
            <li><a href="index.html" class="hover:underline">Выйти</a></li>
        </ul>
    </nav>

    <h1 class="text-3xl font-bold text-orange-600 mb-8">Профиль пользователя</h1>

    <!-- Profile Info -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <p><strong>Имя:</strong> Загрузка...</p>
        <p><strong>Email:</strong> Загрузка...</p>
        <p><strong>Телефон:</strong> Загрузка...</p>
        <p><strong>Аватар:</strong> <img src="" alt="Аватар" class="w-24 h-24 rounded-full"></p>
        <p><strong>Рецептов:</strong> Загрузка...</p>
        <button class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 mt-4">Редактировать профиль</button>
    </div>

    <!-- User Recipes -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-orange-500 mb-4">Ваши рецепты</h2>
        <p>Загрузка...</p>
    </div>

    <!-- Edit Profile Modal -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-orange-500 mb-4">Редактировать профиль</h2>
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Имя</label>
                <input type="text" class="w-full p-2 border rounded-md" placeholder="Введите имя">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" class="w-full p-2 border rounded-md" placeholder="Введите email">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Телефон</label>
                <input type="text" class="w-full p-2 border rounded-md" placeholder="Введите телефон">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">URL аватара</label>
                <input type="text" class="w-full p-2 border rounded-md" placeholder="Введите URL аватара">
            </div>
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">Сохранить</button>
        </form>
    </div>
</div>
</body>
</html>