-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 18 2025 г., 12:59
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `grand_cinema`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cinemas`
--

CREATE TABLE `cinemas` (
  `cinema_id` int NOT NULL,
  `cinema_name` varchar(512) NOT NULL,
  `cinema_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `cinemas`
--

INSERT INTO `cinemas` (`cinema_id`, `cinema_name`, `cinema_address`) VALUES
(3, 'Arman 3D', '6J7Q+29Q, Abay Ave, Kostanay 110000'),
(4, 'ZODIAC CINEMA', 'Нұрсұлтан Назарбаев даңғылы 193, Kostanay 110000'),
(5, 'Qazaqstan', '6J6H+PQ3, Gogol Street, Kostanay 110000');

-- --------------------------------------------------------

--
-- Структура таблицы `halls`
--

CREATE TABLE `halls` (
  `hall_id` int NOT NULL,
  `cinema_id` int NOT NULL,
  `hall_number` int NOT NULL,
  `capacity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `halls`
--

INSERT INTO `halls` (`hall_id`, `cinema_id`, `hall_number`, `capacity`) VALUES
(3, 3, 1, 125),
(4, 3, 2, 130),
(5, 3, 3, 120),
(7, 4, 1, 100),
(8, 4, 2, 105),
(9, 5, 1, 111),
(10, 5, 2, 121),
(11, 5, 3, 123);

-- --------------------------------------------------------

--
-- Структура таблицы `movies`
--

CREATE TABLE `movies` (
  `movie_id` int NOT NULL,
  `cinema_id` int NOT NULL,
  `title` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `duration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `movies`
--

INSERT INTO `movies` (`movie_id`, `cinema_id`, `title`, `description`, `duration`) VALUES
(3, 3, 'Побег из Шоушенка', 'Банкира Энди Дюфрейна обвиняют в убийстве жены и её любовника и отправляют в тюрьму под названием Шоушенк. Там он попадает в сети жестокости и беззакония, которые царят с двух сторон от решётки. Но благодаря своей находчивости и доброму сердцу, он находит способ выжить в этом рабстве. А вместе с тем начинает разрабатывать план побега.', 108),
(4, 3, 'Криминальное чтиво', 'Знаковый фильм Квентина Тарантино в новом ремастированном качестве\r\n\r\nЧерная комедия Квентина Тарантино рассказывает о криминальных буднях двух бандитов - Винсента Вега и Джулса Винфилда. Они проводят время в философских беседах в перерыве между разборками и \"решением проблем\" с должниками своего криминального босса Марселласа Уоллеса. Параллельно разворачивается три истории.\r\n\r\nВ одной из них Винсент не очень удачно присматривает за женой босса, другая рассказывает о боксере, нанятом Уоллесом, чтобы сдать бой, но обманувшим его. Третья история объединяет первые две - в кафе парочка молодых неудачливых грабителей - Пампкин и Хани Бани делают попытку ограбления, но Джулс останавливает их.\r\n', 120),
(5, 4, '\r\nМолчание ягнят\r\nТрейлер\r\n2 отзыва\r\nНашли ошибку?\r\nМолчание ягнят', 'Психопат похищает и убивает молодых женщин по всему Среднему Западу. ФБР, уверенное, что все преступления совершены одним и тем же человеком, поручает агенту Клариссе Старлинг встретиться с заключенным-маньяком Ганнибалом Лектером, который мог бы помочь составить психологический портрет убийцы. Сам Лектер отбывает наказание за убийства и каннибализм. Он согласен помочь Клариссе лишь в том случае, если она попотчует его больное воображение подробностями своей личной жизни', 67),
(6, 4, '\r\nУнесенные призраками', 'Маленькая Тихиро вместе с мамой и папой переезжают в новый дом. Заблудившись по дороге, они оказываются в странном пустынном городе, где их ждет великолепный пир. Родители с жадностью набрасываются на еду и к ужасу девочки превращаются в свиней. Тихиро и ее родители проникли в мир, населенный древними богами и волшебными существами, которыми правит злая колдунья Юбаба. Она объясняет Тихиро, что всех новоприбывших обращают в животных, а потом забивают и съедают. Тех, кто избежал этой трагической участи, уничтожают, как только они оказываются бесполезными. К счастью, Тихиро находит союзника в лице загадочного Хаку. Чтобы оттянуть роковой день уничтожения, чтобы выжить в этом странном и опасном новом мире, населенном магическими существами, и полном загадочных видений, она должна стать полезной, должна работать. Тихиро прощается со своим разумом, воспоминаниями, даже со своим именем…', 125),
(7, 5, 'Назад в будущее', 'Марти МакФлай, типичный американский тинейджер восьмидесятых, попадает в 1955 год на машине времени, изобретенной его другом, чокнутым гением Эмметом Брауном. Теперь Марти должен одеваться в стиле 50-х и слушать хиты того времени. А чтобы вернуться назад в будущее, Марти нужно удостовериться, что его родители, пока еще тинейджеры, познакомятся и полюбят друг друга.', 108),
(8, 5, 'Леон', 'Профессиональный убийца Леон, не знающий пощады и жалости, знакомится со своей очаровательной соседкой Матильдой, семью которой расстреливают полицейские, замешанные в торговле наркотиками. Благодаря этому знакомству он впервые испытывает чувство любви, но…', 110);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `role_id` int NOT NULL,
  `role` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `screenings`
--

CREATE TABLE `screenings` (
  `screening_id` int NOT NULL,
  `movie_id` int NOT NULL,
  `hall_id` int NOT NULL,
  `start_time` datetime NOT NULL,
  `price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `screenings`
--

INSERT INTO `screenings` (`screening_id`, `movie_id`, `hall_id`, `start_time`, `price`) VALUES
(5, 3, 3, '2025-03-04 23:07:44', '750'),
(6, 4, 4, '2025-03-04 23:08:28', '800'),
(7, 5, 5, '2025-03-04 23:08:43', '850'),
(8, 6, 7, '2025-03-04 23:08:55', '670'),
(9, 7, 8, '2025-03-04 23:09:07', '770'),
(10, 8, 10, '2025-03-04 23:09:16', '885');

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int NOT NULL,
  `screening_id` int NOT NULL,
  `user_id` int NOT NULL,
  `seat_number` text NOT NULL,
  `purchase_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(42, 'Name', 'email@email.com', '$2y$10$Zd1tyv2t6NHlbOLS90Vmzu6t2GmeLJo9DZYMX4AWNvVJaqHGQZRt2');

-- --------------------------------------------------------

--
-- Структура таблицы `users_phone`
--

CREATE TABLE `users_phone` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `phones` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users_phone`
--

INSERT INTO `users_phone` (`id`, `user_id`, `phones`) VALUES
(2, 42, '1111111111'),
(3, 42, '232323'),
(4, 42, '121212121212');

-- --------------------------------------------------------

--
-- Структура таблицы `users_role`
--

CREATE TABLE `users_role` (
  `role_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` varchar(128) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users_role`
--

INSERT INTO `users_role` (`role_id`, `user_id`, `role`) VALUES
(3, 42, 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cinemas`
--
ALTER TABLE `cinemas`
  ADD PRIMARY KEY (`cinema_id`),
  ADD UNIQUE KEY `cinema_name` (`cinema_name`);

--
-- Индексы таблицы `halls`
--
ALTER TABLE `halls`
  ADD PRIMARY KEY (`hall_id`),
  ADD KEY `fk_cinema_id` (`cinema_id`);

--
-- Индексы таблицы `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `fk_movies_cinema_id` (`cinema_id`);

--
-- Индексы таблицы `screenings`
--
ALTER TABLE `screenings`
  ADD PRIMARY KEY (`screening_id`),
  ADD KEY `fk_movie_id` (`movie_id`),
  ADD KEY `fk_hall_id` (`hall_id`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `fk_screening_id` (`screening_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `users_phone`
--
ALTER TABLE `users_phone`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id_phone` (`user_id`);

--
-- Индексы таблицы `users_role`
--
ALTER TABLE `users_role`
  ADD PRIMARY KEY (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `cinema_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `halls`
--
ALTER TABLE `halls`
  MODIFY `hall_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `screenings`
--
ALTER TABLE `screenings`
  MODIFY `screening_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `users_phone`
--
ALTER TABLE `users_phone`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users_role`
--
ALTER TABLE `users_role`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `halls`
--
ALTER TABLE `halls`
  ADD CONSTRAINT `fk_cinema_id` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`cinema_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `fk_movies_cinema_id` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`cinema_id`);

--
-- Ограничения внешнего ключа таблицы `screenings`
--
ALTER TABLE `screenings`
  ADD CONSTRAINT `fk_hall_id` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`hall_id`),
  ADD CONSTRAINT `fk_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_screening_id` FOREIGN KEY (`screening_id`) REFERENCES `screenings` (`screening_id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users_phone`
--
ALTER TABLE `users_phone`
  ADD CONSTRAINT `fk_user_id_phone` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
