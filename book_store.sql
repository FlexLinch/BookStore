-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 08 2024 г., 23:25
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `book_store`
--

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `year` int NOT NULL,
  `quantity` int NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `rent_price_2_weeks` decimal(10,2) NOT NULL,
  `rent_price_1_month` decimal(10,2) NOT NULL,
  `rent_price_3_months` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `year`, `quantity`, `purchase_price`, `rent_price_2_weeks`, `rent_price_1_month`, `rent_price_3_months`) VALUES
(1, 'Властелин колец', 'Толкин Джон Рональд Руэл', 'Фэнтези', 2024, 7, '2581.00', '750.00', '1000.00', '2000.00'),
(2, 'Метро 2033', 'Глуховский Дмитрий', 'Фантастический боевик', 2024, 26, '1200.00', '375.00', '500.00', '1000.00'),
(3, 'Гарри Поттер и философский камень', 'Роулинг Джоан Кэтлин', 'Фэнтези', 2023, 9, '1026.00', '300.00', '400.00', '800.00'),
(4, 'Мастер и Маргарита', 'Булгаков Михаил', 'Классическая проза', 2023, 10, '700.00', '225.00', '300.00', '600.00'),
(5, 'Гарри Поттер и Тайная комната', 'Роулинг Джоан Кэтлин', 'Фэнтези', 2023, 9, '1026.00', '300.00', '400.00', '800.00'),
(6, 'Метро 2034', 'Глуховский Дмитрий', 'Фантастический боевик', 2023, 9, '1170.00', '375.00', '500.00', '1000.00'),
(7, 'Метро 2035', 'Глуховский Дмитрий', 'Фантастический боевик', 2023, 8, '1000.00', '375.00', '500.00', '1000.00'),
(9, 'Преступление и наказание', 'Достоевский Федор', 'Классическая проза', 2024, 10, '278.00', '75.00', '100.00', '200.00'),
(10, 'Отцы и дети', 'Тургенев Иван', 'Классическая проза', 2024, 7, '248.00', '75.00', '100.00', '200.00'),
(11, 'Война и мир', 'Толстой Лев', 'Классическая проза', 2022, 7, '1248.00', '375.00', '500.00', '1000.00'),
(12, 'Математика. Вероятность и статистика.', 'Высоцкий Иван', 'Учебная литература', 2024, 3, '764.00', '225.00', '300.00', '600.00'),
(13, 'Физика. 7-9 классы. Справочник', 'Громцева Ольга', 'Учебная литература', 2023, 4, '220.00', '75.00', '100.00', '200.00');

-- --------------------------------------------------------

--
-- Структура таблицы `purchases`
--

CREATE TABLE `purchases` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `quantity` int NOT NULL,
  `purchase_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `book_id`, `quantity`, `purchase_date`) VALUES
(1, 1, 1, 1, '2024-08-08 17:41:00'),
(4, 3, 7, 1, '2024-08-08 18:46:48'),
(5, 3, 6, 1, '2024-08-08 18:47:03'),
(6, 2, 5, 1, '2024-08-08 18:48:03'),
(7, 4, 13, 1, '2024-08-08 18:49:02'),
(8, 4, 13, 1, '2024-08-08 18:49:02'),
(9, 4, 13, 1, '2024-08-08 18:49:03'),
(10, 4, 13, 1, '2024-08-08 18:49:04'),
(11, 4, 13, 1, '2024-08-08 18:49:05'),
(12, 4, 13, 1, '2024-08-08 18:49:12'),
(13, 4, 12, 1, '2024-08-08 18:49:22'),
(14, 4, 12, 1, '2024-08-08 18:49:24'),
(15, 4, 12, 1, '2024-08-08 18:49:25'),
(16, 4, 12, 1, '2024-08-08 18:49:26'),
(17, 4, 12, 1, '2024-08-08 18:49:26'),
(18, 4, 12, 1, '2024-08-08 18:49:28'),
(19, 4, 12, 1, '2024-08-08 18:49:35');

-- --------------------------------------------------------

--
-- Структура таблицы `rentals`
--

CREATE TABLE `rentals` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `rental_period` int NOT NULL,
  `rental_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `rentals`
--

INSERT INTO `rentals` (`id`, `user_id`, `book_id`, `rental_period`, `rental_date`) VALUES
(29, 3, 2, 14, '2024-08-08 18:47:05'),
(30, 2, 3, 30, '2024-08-08 18:47:55'),
(31, 4, 11, 90, '2024-08-08 18:50:01'),
(32, 4, 11, 14, '2024-08-08 18:50:03'),
(33, 4, 11, 14, '2024-08-08 18:50:04'),
(34, 4, 10, 90, '2024-08-08 18:50:11'),
(35, 4, 10, 14, '2024-08-08 18:50:12'),
(36, 4, 10, 14, '2024-08-08 18:50:13');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Администратор', '$2y$10$f7EJRDwHFYQ.HooNWxDaP.Vynh0kkALCqZg2v6ue//NCT93rAdAO6', '2024-08-08 17:21:48'),
(2, 'User1', '$2y$10$1nKjrvAJTJVs.kaZFT.On.x.cssYbCAjfE9TCUtPQ.pZnoy7DemAK', '2024-08-08 17:24:51'),
(3, 'User2', '$2y$10$c0LoFMkdog2txUSa45R5ZuX7NnSkemRGNx5HuUYb4iOMBoE2M6xXO', '2024-08-08 18:43:10'),
(4, 'User3', '$2y$10$gpRibDd6yO3FF2MO3uPWP.rd33L6WzXRbc6UZ3bRvAEqjs3yuGhSG', '2024-08-08 18:43:55');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Индексы таблицы `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Ограничения внешнего ключа таблицы `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
