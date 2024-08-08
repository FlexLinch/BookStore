<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=book_store', 'root', '');
$message = '';
$books = [];
$genres = [];
$authors = [];
$years = [];
$selected_genre = '';
$selected_author = '';
$selected_year = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($stmt->execute([$username, $password])) {
            $message = "Вы успешно зарегистрировались $username!";
        } else {
            $message = "Пользователь уже существует.";
        }
    }

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $message = "Добро пожаловать $username!";
        } else {
            $message = "Неверный логин или пароль.";
        }
    }

    if (isset($_POST['filter'])) {
        $selected_genre = $_POST['genre'];
        $selected_author = $_POST['author'];
        $selected_year = $_POST['year'];
    }

    if (isset($_POST['reset'])) {
        $selected_genre = '';
        $selected_author = '';
        $selected_year = '';
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}

// Получение списка книг с учетом фильтров
$query = "SELECT * FROM books WHERE 1=1";
$params = [];

if ($selected_genre) {
    $query .= " AND genre = ?";
    $params[] = $selected_genre;
}

if ($selected_author) {
    $query .= " AND author = ?";
    $params[] = $selected_author;
}

if ($selected_year) {
    $query .= " AND year = ?";
    $params[] = $selected_year;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение уникальных жанров, авторов и годов
$stmt = $pdo->query("SELECT DISTINCT genre FROM books");
$genres = $stmt->fetchAll(PDO::FETCH_COLUMN);

$stmt = $pdo->query("SELECT DISTINCT author FROM books");
$authors = $stmt->fetchAll(PDO::FETCH_COLUMN);

$stmt = $pdo->query("SELECT DISTINCT year FROM books ORDER BY year");
$years = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Книжный магазин</title>
</head>
<body>
    <h1>Добро пожаловать в библиотеку!</h1>
    <p>Для тестирование создано 4 пользователя:<br>
    Администратор (пароль pass)<br>
    User1 (пароль pass1)<br>
    User2 (пароль pass2)<br>
    User3 (пароль pass3)<br></p>
    <div>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Добро пожаловать, <?= $_SESSION['username'] ?>!</p>
            <form method="post">
                <button type="submit" name="logout">Выйти</button>
                <a href="my_purchases.php">Мои покупки</a>
                <?php if ($_SESSION['username'] === 'Администратор'): ?>
                    <a href="admin.php">Администрирование</a>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <form method="post">
                <h2>Регистрация</h2>
                <input type="text" name="username" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit" name="register">Зарегистрироваться</button>
            </form>
            <form method="post">
                <h2>Вход</h2>
                <input type="text" name="username" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit" name="login">Войти</button>
            </form>
        <?php endif; ?>
    </div>

    <p><?= $message ?></p>

    <h2>Фильтры</h2>
    <form method="post">
        <label for="genre">Жанр:</label>
        <select name="genre" id="genre">
            <option value="">Все жанры</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?= $genre ?>" <?= ($selected_genre === $genre) ? 'selected' : '' ?>><?= $genre ?></option>
            <?php endforeach; ?>
        </select>

        <label for="author">Автор:</label>
        <select name="author" id="author">
            <option value="">Все авторы</option>
            <?php foreach ($authors as $author): ?>
                <option value="<?= $author ?>" <?= ($selected_author === $author) ? 'selected' : '' ?>><?= $author ?></option>
            <?php endforeach; ?>
        </select>

        <label for="year">Год издания:</label>
        <select name="year" id="year">
            <option value="">Все годы</option>
            <?php foreach ($years as $year): ?>
                <option value="<?= $year ?>" <?= ($selected_year == $year) ? 'selected' : '' ?>><?= $year ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="filter">Применить фильтры</button>
        <button type="submit" name="reset">Сбросить фильтры</button>
    </form>

    <h2>Список книг</h2>
    <table>
        <tr>
            <th>Цена покупки</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Жанр</th>
            <th>Год издания</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= $book['purchase_price'] ?></td>
                <td><a href="book.php?id=<?= $book['id'] ?>"><?= $book['title'] ?></a></td>
                <td><?= $book['author'] ?></td>
                <td><?= $book['genre'] ?></td>
                <td><?= $book['year'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>