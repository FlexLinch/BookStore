<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=book_store', 'root', '');

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'Администратор') {
    header("Location: index.php");
    exit();
}

$message = '';

// Обработка добавления книги
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];
    $quantity = $_POST['quantity'];
    $purchase_price = $_POST['purchase_price'];
    $rent_price_2_weeks = $_POST['rent_price_2_weeks'];
    $rent_price_1_month = $_POST['rent_price_1_month'];
    $rent_price_3_months = $_POST['rent_price_3_months'];

    $stmt = $pdo->prepare("INSERT INTO books (title, author, genre, year, quantity, purchase_price, rent_price_2_weeks, rent_price_1_month, rent_price_3_months) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $author, $genre, $year, $quantity, $purchase_price, $rent_price_2_weeks, $rent_price_1_month, $rent_price_3_months])) {
        $message = "Книга добавлена!";
    } else {
        $message = "Ошибка при добавлении книги.";
    }
}

// Получение информации о купленных книгах
$purchases = $pdo->query("SELECT u.username, b.title, p.quantity FROM purchases p JOIN books b ON p.book_id = b.id JOIN users u ON p.user_id = u.id")->fetchAll(PDO::FETCH_ASSOC);

// Получение информации об арендованных книгах
$rentals = $pdo->query("SELECT u.username, b.title, r.rental_period, DATEDIFF(NOW(), r.rental_date) AS days_rented FROM rentals r JOIN books b ON r.book_id = b.id JOIN users u ON r.user_id = u.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администрирование</title>
</head>
<body>
    <h1>Администрирование</h1>
    <p><?= $message ?></p>

    <h2>Добавить книгу</h2>
    <form method="post">
        <input type="text" name="title" placeholder="Название" required>
        <input type="text" name="author" placeholder="Автор" required>
        <input type="text" name="genre" placeholder="Жанр" required>
        <input type="number" name="year" placeholder="Год издания" required>
        <input type="number" name="quantity" placeholder="Количество" required>
        <input type="number" step="0.01" name="purchase_price" placeholder="Цена покупки" required>
        <input type="number" step="0.01" name="rent_price_2_weeks" placeholder="Цена аренды на 2 недели" required>
        <input type="number" step="0.01" name="rent_price_1_month" placeholder="Цена аренды на 1 месяц" required>
        <input type="number" step="0.01" name="rent_price_3_months" placeholder="Цена аренды на 3 месяца" required>
        <button type="submit" name="add_book">Добавить книгу</button>
    </form>

    <h2>Кто купил книги:</h2>
    <ul>
        <?php foreach ($purchases as $purchase): ?>
            <li><?= htmlspecialchars($purchase['username']) ?> купил <?= htmlspecialchars($purchase['title']) ?> - <?= $purchase['quantity'] ?> шт.</li>
        <?php endforeach; ?>
    </ul>

    <h2>У кого сейчас книги:</h2>
    <ul>
        <?php foreach ($rentals as $rental): ?>
            <li><?= htmlspecialchars($rental['username']) ?> арендовал <?= htmlspecialchars($rental['title']) ?> на <?= $rental['rental_period'] ?> дней (<?= $rental['days_rented'] ?> дней прошло)</li>
        <?php endforeach; ?>
    </ul>

    <a href="index.php">Вернуться на главную</a>
</body>
</html>