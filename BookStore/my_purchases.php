<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=book_store', 'root', '');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$purchases = $pdo->prepare("SELECT b.id, b.title, p.quantity FROM purchases p JOIN books b ON p.book_id = b.id WHERE p.user_id = ?");
$purchases->execute([$user_id]);
$purchases = $purchases->fetchAll(PDO::FETCH_ASSOC);

$rentals = $pdo->prepare("SELECT r.id, b.id AS book_id, b.title, r.rental_period, DATEDIFF(NOW(), r.rental_date) AS days_rented FROM rentals r JOIN books b ON r.book_id = b.id WHERE r.user_id = ?");
$rentals->execute([$user_id]);
$rentals = $rentals->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['return'])) {
        $rental_id = $_POST['rental_id'];
        $book_id = $_POST['book_id'];

        // Увеличиваем количество книг в базе
        $pdo->prepare("UPDATE books SET quantity = quantity + 1 WHERE id = ?")->execute([$book_id]);

        // Удаляем только конкретную запись о аренде
        $pdo->prepare("DELETE FROM rentals WHERE id = ? AND user_id = ?")->execute([$rental_id, $user_id]);

        // Перенаправление для обновления страницы
        header("Location: my_purchases.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои покупки</title>
</head>
<body>
    <h1>Добро пожаловать в личный кабинет <?= htmlspecialchars($_SESSION['username']) ?>!</h1>

    <h2>Купленные книги</h2>
    <ul>
        <?php foreach ($purchases as $purchase): ?>
            <li><a href="book.php?id=<?= $purchase['id'] ?>"><?= htmlspecialchars($purchase['title']) ?></a> - <?= $purchase['quantity'] ?> шт.</li>
        <?php endforeach; ?>
    </ul>

    <h2>Арендованные книги</h2>
    <ul>
        <?php foreach ($rentals as $rental): ?>
            <li>
                <form method="post">
                    <input type="hidden" name="rental_id" value="<?= $rental['id'] ?>">
                    <input type="hidden" name="book_id" value="<?= $rental['book_id'] ?>">
                    <a href="book.php?id=<?= $rental['book_id'] ?>"><?= htmlspecialchars($rental['title']) ?></a> - <?= $rental['rental_period'] ?> дней
                    <button type="submit" name="return">Вернуть книгу</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="index.php">Вернуться на главную</a>
</body>
</html>