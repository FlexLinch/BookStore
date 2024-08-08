<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=book_store', 'root', '');

if (!isset($_GET['id'])) {
    die("Книга не найдена.");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Книга не найдена.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buy'])) {
        if ($book['quantity'] > 0) {
            $stmt = $pdo->prepare("INSERT INTO purchases (user_id, book_id, quantity) VALUES (?, ?, 1)");
            $stmt->execute([$_SESSION['user_id'], $book['id']]);
            $pdo->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = ?")->execute([$book['id']]);
            $message = "Вы купили книгу!";
        } else {
            $message = "Извините, этой книги нет в наличии в данный момент.";
        }
    }

    if (isset($_POST['rent'])) {
        $rental_period = $_POST['rental_period'];
        if ($book['quantity'] > 0) {
            $stmt = $pdo->prepare("INSERT INTO rentals (user_id, book_id, rental_period) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $book['id'], $rental_period]);
            $pdo->prepare("UPDATE books SET quantity = quantity - 1 WHERE id = ?")->execute([$book['id']]);
            $message = "Вы арендовали книгу на $rental_period дней!";
        } else {
            $message = "Извините, этой книги нет в наличии в данный момент.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($book['title']) ?></h1>
    <p>Автор: <?= htmlspecialchars($book['author']) ?></p>
    <p>Жанр: <?= htmlspecialchars($book['genre']) ?></p>
    <p>Год издания: <?= htmlspecialchars($book['year']) ?></p>
    <p>Количество книг: <?= htmlspecialchars($book['quantity']) ?></p>
    <p>Цена покупки: <?= htmlspecialchars($book['purchase_price']) ?></p>
    <p>Цена аренды на 2 недели: <?= htmlspecialchars($book['rent_price_2_weeks']) ?></p>
    <p>Цена аренды на 1 месяц: <?= htmlspecialchars($book['rent_price_1_month']) ?></p>
    <p>Цена аренды на 3 месяца: <?= htmlspecialchars($book['rent_price_3_months']) ?></p>

    <form method="post">
        <button type="submit" name="buy">Купить</button>
        <label for="rental_period">Арендовать на:</label>
        <select name="rental_period" id="rental_period">
            <option value="14">2 недели</option>
            <option value="30">1 месяц</option>
            <option value="90">3 месяца</option>
        </select>
        <button type="submit" name="rent">Арендовать</button>
    </form>
    <p><?= $message ?></p>

    <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'Администратор'): ?>
        <a href="edit_book.php?id=<?= $book['id'] ?>">Редактировать</a><br>
    <?php endif; ?>

    <a href="index.php">Вернуться на главную</a>
</body>
</html>