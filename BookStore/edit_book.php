<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=book_store', 'root', '');

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'Администратор') {
    header("Location: index.php");
    exit();
}

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
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];
    $quantity = $_POST['quantity'];
    $purchase_price = $_POST['purchase_price'];
    $rent_price_2_weeks = $_POST['rent_price_2_weeks'];
    $rent_price_1_month = $_POST['rent_price_1_month'];
    $rent_price_3_months = $_POST['rent_price_3_months'];

    $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, genre = ?, year = ?, quantity = ?, purchase_price = ?, rent_price_2_weeks = ?, rent_price_1_month = ?, rent_price_3_months = ? WHERE id = ?");
    if ($stmt->execute([$title, $author, $genre, $year, $quantity, $purchase_price, $rent_price_2_weeks, $rent_price_1_month, $rent_price_3_months, $id])) {
        $message = "Книга успешно обновлена!";
        // Перенаправление на страницу книги после редактирования
        header("Location: book.php?id=$id");
        exit();
    } else {
        $message = "Ошибка при обновлении книги.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование книги</title>
</head>
<body>
    <h1>Редактирование книги: <?= htmlspecialchars($book['title']) ?></h1>
    <p><?= $message ?></p>
    <form method="post">
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required placeholder="Название">
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required placeholder="Автор">
        <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']) ?>" required placeholder="Жанр">
        <input type="number" name="year" value="<?= htmlspecialchars($book['year']) ?>" required placeholder="Год издания">
        <input type="number" name="quantity" value="<?= htmlspecialchars($book['quantity']) ?>" required placeholder="Количество">
        <input type="number" step="0.01" name="purchase_price" value="<?= htmlspecialchars($book['purchase_price']) ?>" required placeholder="Цена покупки">
        <input type="number" step="0.01" name="rent_price_2_weeks" value="<?= htmlspecialchars($book['rent_price_2_weeks']) ?>" required placeholder="Цена аренды на 2 недели">
        <input type="number" step="0.01" name="rent_price_1_month" value="<?= htmlspecialchars($book['rent_price_1_month']) ?>" required placeholder="Цена аренды на 1 месяц">
        <input type="number" step="0.01" name="rent_price_3_months" value="<?= htmlspecialchars($book['rent_price_3_months']) ?>" required placeholder="Цена аренды на 3 месяца">
        <button type="submit">Сохранить изменения</button>
    </form>
    <a href="book.php?id=<?= $book['id'] ?>">Вернуться к книге</a>
</body>
</html>