-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 19 2022 г., 09:32
-- Версия сервера: 8.0.24
-- Версия PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `forum`
--

-- --------------------------------------------------------

--
-- Структура таблицы `application_add_education`
--

CREATE TABLE `application_add_education` (
  `id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `title` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `short_title` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'Checking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `chats`
--

CREATE TABLE `chats` (
  `id` int NOT NULL,
  `first_user_id` int NOT NULL,
  `second_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE `cities` (
  `id` int NOT NULL,
  `rus_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `eng_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `country_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`id`, `rus_title`, `eng_title`, `country_id`) VALUES
(2, 'Пермь', 'Perm', 1),
(3, 'Екатеринбург', 'Ekaterinburg', 1),
(4, 'Челябинск', 'Chelyabinsk', 1),
(5, 'Уфа', 'Ufa', 1),
(6, 'Ижевск', 'Izhevsk', 1),
(7, 'Оренбург', 'Orenburg', 1),
(8, 'Магнитогорск', 'Magnitogorsk', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `rus_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `end_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `rus_title`, `end_title`) VALUES
(1, 'Россия', 'Russia'),
(2, 'Армения', 'Armenia'),
(3, 'Азербайджан', 'Azerbaijan'),
(4, 'Беларусь', 'Belarus'),
(5, 'Казахстан', 'Kazakhstan'),
(6, 'Кыргызстан', 'Kyrgyzstan'),
(7, 'Узбекистан', 'Uzbekistan'),
(8, 'Таджикистан', 'Tajikistan'),
(9, 'Туркменистан', 'Turkmenistan');

-- --------------------------------------------------------

--
-- Структура таблицы `education`
--

CREATE TABLE `education` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `short_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `united_id` int DEFAULT NULL,
  `display_index` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `education`
--

INSERT INTO `education` (`id`, `title`, `short_title`, `united_id`, `display_index`) VALUES
(1, 'Пермский Государственный Национально-исследовательский Университет', 'ПГНИУ', 1, 2),
(12, 'Западно-Уральский институт экономики и права (г. Пермь)', 'ЗУИЭП', 0, 1),
(13, 'Пермский национальный исследовательский политехнический университет', 'ПНИПУ', 0, 1),
(14, 'Институт деловой карьеры (г. Пермь)', 'ИДК', 0, 1),
(15, 'Прикамский социальный институт', 'ПСИ', 0, 1),
(16, 'Пермский государственный гуманитарно-педагогический университет', 'ПГГПУ', 0, 1),
(17, 'Пермский институт ФСИН РФ', 'ПИ ФСИН', 0, 1),
(18, 'Уральский государственный университет путей сообщения (г. Пермь)', 'УрГУПС', 0, 1),
(19, 'Пермская государственная фармацевтическая академия', ' ФГБОУ ВО ПГФА', 0, 1),
(20, 'Пермский военный институт войск национальной гвардии РФ', 'ПВИ ВНГ РФ', 0, 1),
(21, 'Пермский государственный медицинский университет им. Е.А. Вагнера', 'ПГМУ им. ак. Е.А. Вагнера', 0, 1),
(22, 'Волжский государственный университет водного транспорта (г. Пермь)', 'ВГУВТ', 0, 1),
(23, 'Российский экономический университет им. Г.В. Плеханова', 'РЭУ им. Г.В. Плеханова', 0, 1),
(24, 'Российская академия народного хозяйства и государственной службы при Президенте РФ (г. Пермь)', 'РАНХиГС', 0, 1),
(25, 'Пермский государственный аграрно-технологический университет им. Д.Н. Прянишникова', 'ПГАТУ', 0, 1),
(26, 'Пермский государственный институт культуры', 'ПГИК', 0, 1),
(27, 'Российская академия живописи, ваяния и зодчества И. Глазунова (г. Пермь)', 'РАЖВиЗ', 0, 1),
(28, 'МАОУ «СОШ № 146» г.Перми', 'СОШ № 146 (г. Пермь)', 0, 1),
(29, 'МАОУ «Гимназия № 1» г.Перми', 'Гимназия № 1 (г. Пермь)', 0, 1),
(30, 'МАОУ «СОШ № 50» г.Перми', 'СОШ № 50 (г. Пермь)', 0, 1),
(31, 'МАОУ «Гимназия № 4» г.Перми', 'Гимназия № 4 (г. Пермь)', 0, 1),
(32, 'МАОУ «Гимназия № 33» г.Перми', 'Гимназия № 33 (г. Пермь)', 0, 1),
(33, 'Пермский радиотехнический колледж им. А.С. Попова', 'ПРК им. А.С. Попова', 0, 1),
(34, 'МАОУ «Гимназия № 6» г.Перми', 'Гимназия № 6 (г. Пермь)', 0, 1),
(35, 'Пермский авиационный техникум им. А.Д. Швецова', 'ПАТ им. А.Д. Швецова', 0, 1),
(36, 'МАОУ «Экономическая школа № 145» г. Перми', 'Экономическая школа № 145 (г. Пермь)', 0, 1),
(37, 'Пермское государственное хореографическое училище', 'ПГХУ', 0, 1),
(38, 'МАОУ «Лицей № 4» г.Перми', 'Лицей № 4 (г. Пермь)', 0, 1),
(39, 'МАОУ «Гимназия № 31» г.Перми', 'Гимназия № 31 (г. Пермь)', 0, 1),
(40, 'Пермский политехнический колледж им. Н.Г. Славянова', 'ППК им. Н.Г. Славянова', 0, 1),
(41, 'МАОУ «Лицей № 10» г.Перми', 'Лицей № 10 (г. Пермь)', 0, 1),
(42, 'МАОУ «СОШ № 7» г.Перми', 'СОШ №7 (г. Пермь)', 0, 1),
(43, 'Пермский химико-технологический техникум', 'ПХТТ', 0, 1),
(44, 'МАОУ «Гимназия № 5» г.Перми', 'Гимназия № 5 (г. Пермь)', 0, 1),
(45, 'МАОУ «Гимназия № 8» г.Перми', 'Гимназия № 8 (г. Пермь)', 0, 1),
(46, 'Пермский техникум профессиональных технологий и дизайна', 'ПТПТД', 0, 1),
(47, 'МБОУ «Гимназия № 11» (г. Пермь)', 'Гимназия № 11 (г. Пермь)', 0, 1),
(48, 'МАОУ «Точка» (г. Пермь)', '«Точка» (г. Пермь)', 0, 1),
(50, 'Колледж Уральский государственный университет путей сообщения (г. Пермь)', 'ПИЖТ УрГУПС', 0, 1),
(51, 'МАОУ «Гимназия № 10» (г. Пермь)', 'Гимназия № 10 (г. Пермь)', 0, 1),
(52, 'Пермский колледж транспорта и сервиса', 'ПКТС', 0, 1),
(53, 'МБОУ «Гимназия № 17» (г. Пермь)', 'Гимназия № 17 (г. Пермь)', 0, 1),
(54, 'МАОУ «СОШ № 77» (г. Пермь)', 'СОШ № 77 (г. Пермь)', 0, 1),
(55, 'МАОУ «Лицей № 2» (г. Пермь)', 'Лицей № 2 (г. Пермь)', 0, 1),
(56, 'МАОУ «СОШ № 12» (г. Пермь)', 'СОШ № 12 (г. Пермь)', 0, 1),
(57, 'МАОУ «Лицей № 9» (г. Пермь)', 'Лицей № 9 (г. Пермь)', 0, 1),
(58, 'Пермский государственный профессионально-педагогический колледж', 'ПГППК', 0, 1),
(59, 'Пермский краевой колледж Оникс', 'ПКК \"ОНИКС\"', 0, 1),
(60, 'МАОУ «СОШ № 22» (г. Пермь)', 'СОШ № 22 (г. Пермь)', 0, 1),
(61, 'МАОУ «СОШ № 91» (г. Пермь)', 'СОШ № 91 (г. Пермь)', 0, 1),
(62, 'МАОУ «Гимназия № 2» (г. Пермь)', 'Гимназия № 2 (г. Пермь)', 0, 1),
(63, 'МАОУ «СОШ № 9» (г. Пермь)', 'СОШ № 9 (г. Пермь)', 0, 1),
(64, 'МАОУ «СОШ № 2» (г. Пермь)', 'СОШ № 2 (г. Пермь)', 0, 1),
(65, 'МБОУ «Лицей № 1» (г. Пермь)', 'Лицей № 1 (г. Пермь)', 0, 1),
(66, 'МАОУ «СОШ № 127» (г. Пермь)', 'СОШ № 127 (г. Пермь)', 0, 1),
(67, 'МАОУ «СОШ № 93» (г. Пермь)', 'СОШ № 93 (г. Пермь)', 0, 1),
(68, 'МАОУ «СОШ № 109» (г. Пермь)', 'СОШ № 109 (г. Пермь)', 0, 1),
(69, 'МАОУ «СОШ № 116» (г. Пермь)', 'СОШ № 116 (г. Пермь)', 0, 1),
(70, 'МАОУ «СОШ «Петролеум +» (г. Пермь)', 'СОШ «Петролеум +» (г. Пермь)', 0, 1),
(71, 'МАОУ «СОШ № 36» (г. Пермь)', 'СОШ № 36 (г. Пермь)', 0, 2),
(72, 'МАОУ «СОШ № 63» (г. Пермь)', 'СОШ № 63 (г. Пермь)', 0, 2),
(73, 'МАОУ «СОШ № 55» (г. Пермь)', 'СОШ № 55 (г. Пермь)', 0, 1),
(74, 'МАОУ «СОШ № 101» (г. Пермь)', 'СОШ № 101 (г. Пермь)', 0, 1),
(76, 'МАОУ «СОШ № 108» (г. Пермь)', 'СОШ № 108 (г. Пермь)', 0, 1),
(77, 'МАОУ «СОШ № 32 им.Г.А.Сборщикова» (г. Пермь)', 'СОШ № 32 им.Г.А.Сборщикова (г. Пермь)', 0, 1),
(78, 'МАОУ «СОШ № 83» (г. Пермь)', 'СОШ № 83 (г. Пермь)', 0, 1),
(79, 'Финансово-экономический колледж (г. Пермь)', 'ФЭК (г. Пермь)', 0, 1),
(80, 'МАОУ «СОШ № 65» (г. Пермь)', 'СОШ № 65 (г. Пермь)', 0, 1),
(81, 'МАОУ «СОШ № 119» (г. Пермь)', 'СОШ № 119 (г. Пермь)', 0, 1),
(82, 'МАОУ «Школа Агробизнестехнологий» (г. Пермь)', 'Школа Агробизнестехнологий (г. Пермь)', 0, 1),
(83, 'МАОУ «Лицей № 3» (г. Пермь)', 'Лицей № 3 (г. Пермь)', 0, 1),
(84, 'МАОУ «СОШ № 61» (г. Пермь)', 'СОШ № 61 (г. Пермь)', 0, 1),
(85, 'МАОУ «Гимназия № 3» (г. Пермь)', 'Гимназия № 3 (г. Пермь)', 0, 1),
(86, 'МАОУ «СОШ № 25» (г. Пермь)', 'СОШ № 25 (г. Пермь)', 0, 1),
(87, 'МАОУ «Предметно-языковая школа «Дуплекс» (г. Пермь)', 'Предметно-языковая школа «Дуплекс» (г. Пермь)', 0, 1),
(88, 'МАОУ «СОШ № 14» (г. Пермь)', 'СОШ № 14 (г. Пермь)', 0, 1),
(89, 'МАОУ ПКШ №1 (г. Пермь)', 'ПКШ №1 (г. Пермь)', 0, 1),
(90, 'Колледж Российский экономический университет им. Г.В. Плеханова (г. Пермь)', 'Колледж РЭУ им. Г.В. Плеханова (г. Пермь)', 0, 1),
(91, 'МАОУ «Лицей № 5» (г. Пермь)', 'Лицей № 5 (г. Пермь)', 0, 1),
(92, 'МАОУ «СОШ № 82» (г. Пермь)', 'СОШ № 82 (г. Пермь)', 0, 1),
(93, 'МАОУ «Гимназия № 7» (г. Пермь)', 'Гимназия № 7 (г. Пермь)', 0, 1),
(94, 'МАОУ «СОШ № 118» (г. Пермь)', 'СОШ № 118 (г. Пермь)', 0, 1),
(95, 'МАОУ «СОШ № 131» (г. Пермь)', 'СОШ № 131 (г. Пермь)', 0, 1),
(96, 'МБОУ «СОШ № 21» (г. Пермь)', 'СОШ № 21 (г. Пермь)', 0, 1),
(97, 'Колледж Финансовый университет при Правительстве РФ (г. Пермь)', 'Колледж ФУ при Правительстве РФ (г. Пермь)', 0, 1),
(98, 'МАОУ «СОШ № 42» (г. Пермь)', 'СОШ № 42 (г. Пермь)', 0, 1),
(99, 'МАОУ «СОШ № 6» (г. Пермь)', 'СОШ № 6 (г. Пермь)', 0, 1),
(100, 'МАОУ «Траектория» (г. Пермь)', '«Траектория» (г. Пермь)', 0, 1),
(101, 'МАОУ «СОШ № 120» (г. Пермь)', 'СОШ № 120 (г. Пермь)', 0, 1),
(102, 'МАОУ «СОШ «Мастерград» (г. Пермь)', 'СОШ «Мастерград» (г. Пермь)', 0, 1),
(103, 'МАОУ «СОШ № 79» (г. Пермь)', 'СОШ № 79 (г. Пермь)', 0, 1),
(104, 'МАОУ «СОШ № 47» (г. Пермь)', 'СОШ № 47 (г. Пермь)', 0, 1),
(105, 'МАОУ «Энергополис» (г. Пермь)', '«Энергополис» (г. Пермь)', 0, 1),
(106, 'МАОУ «СОШ № 76» (г. Пермь)', 'СОШ № 76 (г. Пермь)', 0, 1),
(107, 'МАОУ «СОШ № 81» (г. Пермь)', 'СОШ № 81 (г. Пермь)в', 0, 1),
(143, 'Колледж Профессионального Образования ПГНИУ', 'КПО ПГНИУ', 1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` int NOT NULL,
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `replies` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `status` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'standart',
  `replies_count` int NOT NULL DEFAULT '0',
  `last_redactor` int NOT NULL DEFAULT '0',
  `loc` int NOT NULL DEFAULT '0',
  `city_id` int NOT NULL DEFAULT '0',
  `education` int NOT NULL DEFAULT '0',
  `education_id` int NOT NULL DEFAULT '0',
  `type` varchar(64) NOT NULL DEFAULT 'topic',
  `edited` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int NOT NULL,
  `outgoing_id` int NOT NULL,
  `incoming_id` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `interests_records`
--

CREATE TABLE `interests_records` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `loc` int NOT NULL,
  `city_id` int NOT NULL DEFAULT '0',
  `repost` int NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_id` int NOT NULL,
  `replies` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `replies_count` int NOT NULL DEFAULT '0',
  `status` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'standart',
  `last_redactor` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `interests_records`
--

INSERT INTO `interests_records` (`id`, `user_id`, `title`, `body`, `loc`, `city_id`, `repost`, `date`, `group_id`, `replies`, `replies_count`, `status`, `last_redactor`) VALUES
(140, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан,', 'We had one visitor from Earth who looked like trouble, a Dr. Dorian, physicist and engineer.', 1, 2, 0, '2022-01-20 03:34:57', 1, NULL, 0, 'hidden', 0),
(141, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:36:33', 2, 'a:27:{i:1;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:30\";s:7:\"message\";s:3:\"awd\";s:7:\"replies\";a:0:{}}i:2;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:31\";s:7:\"message\";s:3:\"awd\";s:7:\"replies\";a:0:{}}i:3;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:31\";s:7:\"message\";s:1:\"a\";s:7:\"replies\";a:0:{}}i:4;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:31\";s:7:\"message\";s:4:\"wdaw\";s:7:\"replies\";a:0:{}}i:5;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:31\";s:7:\"message\";s:1:\"d\";s:7:\"replies\";a:0:{}}i:6;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:32\";s:7:\"message\";s:1:\"w\";s:7:\"replies\";a:0:{}}i:7;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:32\";s:7:\"message\";s:2:\"ad\";s:7:\"replies\";a:0:{}}i:8;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:32\";s:7:\"message\";s:1:\"a\";s:7:\"replies\";a:0:{}}i:9;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:32\";s:7:\"message\";s:1:\"d\";s:7:\"replies\";a:0:{}}i:10;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:32\";s:7:\"message\";s:1:\"a\";s:7:\"replies\";a:0:{}}i:11;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:32\";s:7:\"message\";s:2:\"wd\";s:7:\"replies\";a:0:{}}i:12;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:33\";s:7:\"message\";s:3:\"awd\";s:7:\"replies\";a:0:{}}i:13;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:33\";s:7:\"message\";s:1:\"a\";s:7:\"replies\";a:0:{}}i:14;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:33\";s:7:\"message\";s:2:\"wd\";s:7:\"replies\";a:0:{}}i:15;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:33\";s:7:\"message\";s:1:\"a\";s:7:\"replies\";a:0:{}}i:16;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:33\";s:7:\"message\";s:2:\"wd\";s:7:\"replies\";a:0:{}}i:17;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:33\";s:7:\"message\";s:2:\"aw\";s:7:\"replies\";a:0:{}}i:18;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:34\";s:7:\"message\";s:1:\"a\";s:7:\"replies\";a:0:{}}i:19;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:34\";s:7:\"message\";s:2:\"wd\";s:7:\"replies\";a:0:{}}i:20;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:34\";s:7:\"message\";s:2:\"aw\";s:7:\"replies\";a:0:{}}i:21;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:34\";s:7:\"message\";s:1:\"d\";s:7:\"replies\";a:0:{}}i:22;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:34\";s:7:\"message\";s:2:\"aw\";s:7:\"replies\";a:0:{}}i:23;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:35\";s:7:\"message\";s:3:\"awd\";s:7:\"replies\";a:0:{}}i:24;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:35\";s:7:\"message\";s:1:\"a\";s:7:\"replies\";a:0:{}}i:25;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:35\";s:7:\"message\";s:2:\"wd\";s:7:\"replies\";a:0:{}}i:26;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:35\";s:7:\"message\";s:2:\"aw\";s:7:\"replies\";a:0:{}}i:27;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:01:35\";s:7:\"message\";s:1:\"d\";s:7:\"replies\";a:0:{}}}', 27, 'hidden', 0),
(142, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:36:42', 3, NULL, 0, 'hidden', 0),
(143, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:36:48', 4, NULL, 0, 'hidden', 0),
(144, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:36:56', 5, NULL, 0, 'hidden', 0),
(145, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:37:04', 6, NULL, 0, 'hidden', 0),
(146, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:37:18', 7, NULL, 0, 'hidden', 0),
(147, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:37:32', 8, NULL, 0, 'hidden', 0),
(149, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:38:02', 9, NULL, 0, 'hidden', 0),
(150, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:38:10', 10, NULL, 0, 'hidden', 0),
(151, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:38:19', 11, NULL, 0, 'hidden', 0),
(152, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:38:28', 12, NULL, 0, 'hidden', 0),
(153, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:38:36', 13, NULL, 0, 'hidden', 0),
(154, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:38:47', 14, NULL, 0, 'hidden', 0),
(155, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 1, 2, 0, '2022-01-20 03:38:55', 15, NULL, 0, 'hidden', 0),
(156, 21, 'майнкрафт', 'майнкрафт майн', 1, 2, 0, '2022-01-20 03:39:52', 1, NULL, 0, 'hidden', 21),
(157, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. csgo', 1, 2, 0, '2022-01-20 03:40:19', 1, NULL, 0, 'hidden', 0),
(158, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. gta', 1, 2, 0, '2022-01-20 03:40:32', 1, NULL, 0, 'hidden', 0),
(159, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. dota', 1, 2, 0, '2022-01-20 03:40:44', 1, NULL, 0, 'hidden', 0),
(160, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. skyrim', 1, 2, 0, '2022-01-20 03:40:57', 1, NULL, 0, 'hidden', 0),
(161, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. among us', 1, 2, 0, '2022-01-20 03:41:11', 1, NULL, 0, 'hidden', 0),
(162, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. wot blitz', 1, 2, 0, '2022-01-20 03:41:27', 1, NULL, 0, 'hidden', 0),
(163, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. apex', 1, 2, 0, '2022-01-20 03:41:37', 1, 'a:7:{i:1;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:16:56\";s:7:\"message\";s:6:\"ghbdtn\";s:7:\"replies\";a:0:{}}i:2;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:17:06\";s:7:\"message\";s:5:\"f,j,f\";s:7:\"replies\";a:0:{}}i:3;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:17:09\";s:7:\"message\";s:2:\"wa\";s:7:\"replies\";a:0:{}}i:4;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:17:15\";s:7:\"message\";s:5:\"f,j,f\";s:7:\"replies\";a:0:{}}i:5;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:22:30\";s:7:\"message\";s:12:\"привет\";s:7:\"replies\";a:0:{}}i:6;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:23:02\";s:7:\"message\";s:12:\"привет\";s:7:\"replies\";a:0:{}}i:7;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 10:00:10\";s:7:\"message\";s:6:\"ghbdnt\";s:7:\"replies\";a:0:{}}}', 7, 'hidden', 0),
(164, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. pubg', 1, 2, 0, '2022-01-20 03:41:51', 1, NULL, 0, 'hidden', 0),
(165, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. roblox', 1, 2, 0, '2022-01-20 03:42:37', 1, 'a:13:{i:1;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:24:57\";s:7:\"message\";s:12:\"привте\";s:7:\"replies\";a:0:{}}i:2;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:25:07\";s:7:\"message\";s:12:\"привте\";s:7:\"replies\";a:0:{}}i:3;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:25:14\";s:7:\"message\";s:12:\"привет\";s:7:\"replies\";a:0:{}}i:4;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:05\";s:7:\"message\";s:6:\"ghbdnt\";s:7:\"replies\";a:0:{}}i:5;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:11\";s:7:\"message\";s:6:\"ghbdtn\";s:7:\"replies\";a:0:{}}i:6;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:13\";s:7:\"message\";s:6:\"awdawd\";s:7:\"replies\";a:0:{}}i:7;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:13\";s:7:\"message\";s:6:\"awdawd\";s:7:\"replies\";a:0:{}}i:8;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:14\";s:7:\"message\";s:4:\"awda\";s:7:\"replies\";a:0:{}}i:9;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:16\";s:7:\"message\";s:8:\"dawdawda\";s:7:\"replies\";a:0:{}}i:10;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:20\";s:7:\"message\";s:4:\"awda\";s:7:\"replies\";a:0:{}}i:11;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:22\";s:7:\"message\";s:3:\"awd\";s:7:\"replies\";a:0:{}}i:12;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:23\";s:7:\"message\";s:5:\"wadaw\";s:7:\"replies\";a:0:{}}i:13;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:26:26\";s:7:\"message\";s:10:\"awdawdawda\";s:7:\"replies\";a:0:{}}}', 13, 'hidden', 0),
(166, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. mk', 1, 2, 0, '2022-01-20 03:42:47', 1, 'a:1:{i:1;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-01-21 09:23:38\";s:7:\"message\";s:12:\"привет\";s:7:\"replies\";a:0:{}}}', 1, 'hidden', 0),
(167, 21, 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер.', 'Один гость показался нам нежелательным. Некий доктор Дориан, физик и инженер. brawl stars', 1, 2, 0, '2022-01-20 03:43:04', 1, NULL, 0, 'hidden', 0),
(168, 21, 'Ищу программиста, фуллстек или нет - не важно', 'Просто есть желание сделать что-нибудь совместно с:', 0, 3, 0, '2022-02-02 06:20:25', 7, 'a:1:{i:1;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-02-02 09:20:55\";s:7:\"message\";s:24:\"Привет, давай\";s:7:\"replies\";a:1:{i:2;a:4:{s:7:\"user_id\";s:2:\"21\";s:4:\"date\";s:19:\"2022-02-02 09:21:10\";s:7:\"message\";s:73:\"Соромотин Александр, дай номер телефона\";s:7:\"replies\";a:0:{}}}}}', 2, 'standart', 21);

-- --------------------------------------------------------

--
-- Структура таблицы `interests_sections`
--

CREATE TABLE `interests_sections` (
  `id` int NOT NULL,
  `rus_title` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `eng_title` text CHARACTER SET utf8 COLLATE utf8_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `interests_sections`
--

INSERT INTO `interests_sections` (`id`, `rus_title`, `eng_title`) VALUES
(1, 'Развлечения', 'Entertainments'),
(2, 'Творчество', 'Creation'),
(3, 'Науки', 'Science');

-- --------------------------------------------------------

--
-- Структура таблицы `interests_subsections`
--

CREATE TABLE `interests_subsections` (
  `id` int NOT NULL,
  `section_id` int NOT NULL,
  `rus_title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `eng_title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tags` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `icon` text CHARACTER SET utf8 COLLATE utf8_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `interests_subsections`
--

INSERT INTO `interests_subsections` (`id`, `section_id`, `rus_title`, `eng_title`, `tags`, `icon`) VALUES
(1, 1, 'Игры', 'Games', '', '/assets/img/icons/pacman.svg'),
(2, 1, 'Фильмы и сериалы', 'Movies and TV series', '', '/assets/img/icons/movie.svg'),
(3, 1, 'Спорт', 'Sport', '', '/assets/img/icons/swimming.svg'),
(4, 1, 'Общение', 'Communication', '', '/assets/img/icons/messages.svg'),
(5, 2, 'Музыка', 'Music', '', '/assets/img/icons/music.svg'),
(6, 2, 'Искусство', 'Art', '', '/assets/img/icons/brush.svg'),
(7, 2, 'Программирование', 'Programming', '', '/assets/img/icons/braces.svg'),
(8, 2, 'Дизайн', 'Design', '', '/assets/img/icons/palette.svg'),
(9, 2, 'Кулинария', 'Cooking', '', '/assets/img/icons/tools-kitchen.svg'),
(10, 2, 'Технологии', 'Technologies', '', '/assets/img/icons/satellite.svg'),
(11, 3, 'Биология', 'Biology', '', '/assets/img/icons/virus.svg'),
(12, 3, 'Физика', 'Physics', '', '/assets/img/icons/radioactive.svg'),
(13, 3, 'Математика', 'Mathematics', '', '/assets/img/icons/square-root-2.svg'),
(14, 3, 'Химия', 'Chemistry', '', '/assets/img/icons/test-pipe.svg'),
(15, 3, 'Иностранные языки', 'Foreign languages', '', '/assets/img/icons/language.svg');

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `second_user_id` int DEFAULT NULL,
  `function` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `outgoing_id` int NOT NULL,
  `incoming_id` int NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'msg',
  `status` int NOT NULL DEFAULT '0',
  `text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `media` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `editability` int NOT NULL DEFAULT '1',
  `edited_version` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `outgoing_id`, `incoming_id`, `type`, `status`, `text`, `media`, `date`, `editability`, `edited_version`) VALUES
(885, 21, 22, '', 0, 'Привет', '\"\"', '2022-02-18 16:32:13', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `incoming_id` int NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `type` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL,
  `technical_break` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `site_settings`
--

INSERT INTO `site_settings` (`id`, `technical_break`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int NOT NULL,
  `email` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `theme` varchar(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `message` varchar(4096) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Checking',
  `user_viewed` int NOT NULL DEFAULT '0',
  `answer` varchar(8192) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `admin_id` int DEFAULT '0',
  `appealer_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `email`, `theme`, `message`, `date`, `status`, `user_viewed`, `answer`, `admin_id`, `appealer_id`) VALUES
(7, 'sannekys@gmail.com', 'Здравствуйте, у меня проблема', 'Меня оскорбляет Олег и держит в подвале, помогите, пожалуйста', '2021-11-04 05:49:02', 'Closed', 0, 'Александр, мы рассмотрели ваше обращение. Ладно', 12, 12),
(25, 'alexeygribasha1218@gmail.com', 'Здравствуйте, как работают группы интересов?', 'Мне было бы очень интересно узнать, как же на самом деле работают группы интересов.', '2021-11-17 20:06:54', 'Closed', 1, 'Алексей, мы рассмотрели ваше обращение.  Круто', 19, 19),
(32, 'alexeygribasha1218@gmail.com', 'asd', 'asdasd', '2021-11-17 20:22:16', 'Closed', 1, 'Алексей, мы рассмотрели ваше обращение. aboba', 19, 19),
(33, 'sannekys@gmail.com', '111', '11111', '2021-11-27 11:42:51', 'Closed', 1, 'Александр, мы рассмотрели ваше обращение.', 21, 21);

-- --------------------------------------------------------

--
-- Структура таблицы `united_education`
--

CREATE TABLE `united_education` (
  `id` int NOT NULL,
  `title` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `united_education`
--

INSERT INTO `united_education` (`id`, `title`) VALUES
(1, 'ПГНИУ');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `reputation` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `friends` text,
  `blacklist` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `patronymic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `sex` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Мужской',
  `email` varchar(4000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `password` varchar(4000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `education_id` int DEFAULT '0',
  `city_id` int DEFAULT '0',
  `photo` varchar(4000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'http://frmjdg.com/assets/img/unknown-user.png',
  `photo_style` varchar(4000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `bg_image` varchar(4000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'http://frmjdg.com/assets/img/bg_image.jpg',
  `bg_image_style` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'User' COMMENT 'User, Admin, Banned, deleted - удалённый аккаунт. pre-deleted - удалённый, но его ещё можно восстановить',
  `closed_profile` int NOT NULL DEFAULT '0' COMMENT '0 - профиль открыт. 1 - профиль закрыт',
  `last_online` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gif_user_photo` int NOT NULL DEFAULT '0' COMMENT '1 - пользователь может ставить гиф на фото. 0 - не может.',
  `ban_reason` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `delete_account_reason` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `delete_account_date` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `privacy_messages` int NOT NULL DEFAULT '2' COMMENT '0 - никто не может присылать сообщения. 1 - только друзья. 2 - все.',
  `hi_icue` text CHARACTER SET utf8 COLLATE utf8_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `token`, `reputation`, `friends`, `blacklist`, `first_name`, `last_name`, `patronymic`, `sex`, `email`, `password`, `education_id`, `city_id`, `photo`, `photo_style`, `bg_image`, `bg_image_style`, `registration_date`, `status`, `closed_profile`, `last_online`, `gif_user_photo`, `ban_reason`, `delete_account_reason`, `delete_account_date`, `privacy_messages`, `hi_icue`) VALUES
(12, '8943b3dc082f269c1e5d0b997121fbc7', NULL, NULL, NULL, 'Александр', 'Соромотин', 'Сергеевич', 'Мужской', 'sannekys@gmail.com', '001bb629acf4ad641ca834797285b0a3', 143, 0, 'http://frmjdg.com/assets/img/deleted-user.png', NULL, 'http://frmjdg.com/assets/img/bd_image.jpg', NULL, '2021-11-01 06:38:52', 'deleted', 0, '2021-11-12 18:15:48', 0, '', 'asef', '05-05-2021 17:56:02', 2, NULL),
(13, '439dc6554b26c2c47559819ef6c7b62c', NULL, 'a:0:{}', NULL, 'Владимир', 'Корешков', 'Иванович', 'Мужской', 'a-sorom@mail.ru', '001bb629acf4ad641ca834797285b0a3', 1, 0, 'http://frmjdg.com/uploads/user_photo/user_13.png?v=523577', 'a:2:{s:5:\"ox_oy\";s:17:\"top: 0%; left:0%;\";s:5:\"scale\";s:3:\"1.9\";}', 'http://frmjdg.com/uploads/user_bg_photo/user_bg_13.png?v=133579', 'top: -71.63567567567569%;', '2021-11-01 06:38:52', 'Admin', 0, '2022-02-07 06:34:47', 0, 'Неоднократный спам и рассылка рекламы.', '', '', 2, NULL),
(19, 'eae3a2eafeb5bd3d7712fc72d8d1e260', NULL, '', NULL, 'Алексей', 'Грибанов', 'Сергеевич', 'Мужской', 'alexeygribasha1218@gmail.com', 'a8c1b3a34f8306f341f6e2a66709367f', 48, 0, 'http://frmjdg.com/uploads/user_photo/user_19.jpg?v=632960', 'a:2:{s:5:\"ox_oy\";s:17:\"top: 0%; left:0%;\";s:5:\"scale\";s:1:\"1\";}', 'http://frmjdg.com/uploads/user_bg_photo/user_bg_19.png?v=751786', 'top: 0%;', '2021-11-01 06:38:52', 'Admin', 1, '2021-11-17 20:50:21', 0, '', 'Депресея', NULL, 2, NULL),
(20, '73ea55db15626baf7926f266a631b0fb', NULL, NULL, NULL, 'Имён', 'Фамильяз', 'Отечествич', 'Мужской', 'princesofthespace@gmail.com', '001bb629acf4ad641ca834797285b0a3', 0, 0, 'http://frmjdg.com/uploads/user_photo/user_20.png', 'a:2:{s:5:\"ox_oy\";s:22:\"top: 0%; left:1.5625%;\";s:5:\"scale\";s:3:\"1.3\";}', 'http://frmjdg.com/uploads/user_bg_photo/user_bg_20.png', 'top: -0.8023648648648648%;', '2021-11-01 09:39:06', 'pre-deleted', 0, '2021-11-01 08:01:23', 0, '', NULL, '05-11-2021 17:56:02', 2, NULL),
(21, '9b29d62f626498436298ebd22ae031cd', NULL, 'a:1:{i:0;s:2:\"22\";}', 'a:0:{}', 'Александр', 'Соромотин', 'Сергеевич', 'Мужской', 'sannekys@gmail.com', '001bb629acf4ad641ca834797285b0a3', 1, 3, 'http://frmjdg.com/uploads/user_photo/user_21.jpg?v=833438', 'a:2:{s:5:\"ox_oy\";s:51:\"top: 50.924800000000005%; left:-6.706239999999999%;\";s:5:\"scale\";s:3:\"1.2\";}', 'http://frmjdg.com/uploads/user_bg_photo/user_bg_21.png?v=196920', 'top: 16.722972972972975%;', '2021-10-07 17:32:57', 'Admin', 0, '2022-02-18 18:32:04', 0, '', '', '', 2, NULL),
(22, '04d50ecd67c72ed5b001983eeda910cc', '[\"21\"]', 'a:1:{i:0;s:2:\"21\";}', 'a:0:{}', 'Михаил', 'Аверинцев', 'Юрьевич', 'Мужской', '1234', '001bb629acf4ad641ca834797285b0a3', 1, 0, 'http://frmjdg.com/uploads/user_photo/user_22.jpg', 'a:2:{s:5:\"ox_oy\";s:40:\"top: -5.4062399999999995%; left:-0.375%;\";s:5:\"scale\";s:3:\"2.2\";}', 'http://frmjdg.com/uploads/user_bg_photo/user_bg_22.jpg', 'top: 70.98819819819819%;', '2021-11-10 20:19:11', 'User', 0, '2022-02-18 11:03:04', 0, NULL, NULL, NULL, 1, NULL),
(25, 'dafd82f44e04f6629a6a988f00101119', NULL, NULL, NULL, 'Александр', 'Локал', 'privet@gmail.com', 'Мужской', 'privet@gmail.com', '001bb629acf4ad641ca834797285b0a3', 143, 2, 'http://frmjdg.com/uploads/user_photo/user_25.jpg?v=241416', 'a:2:{s:5:\"ox_oy\";s:17:\"top: 0%; left:0%;\";s:5:\"scale\";s:1:\"1\";}', 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2021-12-22 06:32:48', 'User', 0, '2021-12-22 05:23:48', 0, NULL, '', '', 2, NULL),
(26, 'f326c4f96544d7010c07c21909a070c9', NULL, NULL, NULL, 'Абоба', 'Крутой', NULL, 'Мужской', 'abobaba@gmail.com', '001bb629acf4ad641ca834797285b0a3', 0, 0, 'http://frmjdg.com/assets/img/unknown-user.png', NULL, 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2021-12-30 20:23:20', 'Admin', 0, '2021-12-30 18:24:43', 0, '', NULL, NULL, 2, NULL),
(88, 'dfc9b6250939c3a609209bfccccaf78f', '[\"19\"]', NULL, NULL, '!!!!!', 'ty che', NULL, 'Мужской', 'umarov2901@gmail.com', 'b1be88b05045f8ce4558b9df201627b9', 0, 3, 'http://frmjdg.com/uploads/user_photo/user_88.jpg?v=248595', 'a:2:{s:5:\"ox_oy\";s:38:\"top: 27.775%; left:7.931239999999999%;\";s:5:\"scale\";s:4:\"1.25\";}', 'http://frmjdg.com/uploads/user_bg_photo/user_bg_88.png?v=660780', 'top: 0%;', '2022-01-02 11:27:34', 'User', 1, '2022-02-04 09:40:45', 1, NULL, NULL, NULL, 2, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_files`
--

CREATE TABLE `user_files` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `server_file_name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `user_files`
--

INSERT INTO `user_files` (`id`, `user_id`, `date`, `server_file_name`, `status`) VALUES
(1666, 21, '2022-02-18 17:37:11', '1666.png', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `application_add_education`
--
ALTER TABLE `application_add_education`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interests_records`
--
ALTER TABLE `interests_records`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interests_sections`
--
ALTER TABLE `interests_sections`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interests_subsections`
--
ALTER TABLE `interests_subsections`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `united_education`
--
ALTER TABLE `united_education`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_files`
--
ALTER TABLE `user_files`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `application_add_education`
--
ALTER TABLE `application_add_education`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `education`
--
ALTER TABLE `education`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT для таблицы `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT для таблицы `interests_records`
--
ALTER TABLE `interests_records`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT для таблицы `interests_sections`
--
ALTER TABLE `interests_sections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `interests_subsections`
--
ALTER TABLE `interests_subsections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=886;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT для таблицы `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `united_education`
--
ALTER TABLE `united_education`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT для таблицы `user_files`
--
ALTER TABLE `user_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1667;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
