-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 17, 2022 at 08:11 AM
-- Server version: 8.0.24
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `findstudents`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_add_education`
--

CREATE TABLE `application_add_education` (
  `id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `title` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `short_title` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'Checking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int NOT NULL,
  `rus_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `eng_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `country_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `cities`
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
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `rus_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `eng_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `rus_title`, `eng_title`) VALUES
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
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `short_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `united_id` int DEFAULT NULL,
  `display_index` int NOT NULL DEFAULT '1',
  `city_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `title`, `short_title`, `united_id`, `display_index`, `city_id`) VALUES
(1, 'Пермский Государственный Национально-исследовательский Университет', 'ПГНИУ', 1, 2, 2),
(12, 'Западно-Уральский институт экономики и права', 'ЗУИЭП', 0, 1, 2),
(13, 'Пермский национальный исследовательский политехнический университет', 'ПНИПУ', 0, 1, 2),
(14, 'Институт деловой карьеры', 'ИДК', 0, 1, 2),
(15, 'Прикамский социальный институт', 'ПСИ', 0, 1, 2),
(16, 'Пермский государственный гуманитарно-педагогический университет', 'ПГГПУ', 0, 1, 2),
(17, 'Пермский институт ФСИН РФ', 'ПИ ФСИН', 0, 1, 2),
(18, 'Уральский государственный университет путей сообщения', 'УрГУПС', 0, 1, 2),
(19, 'Пермская государственная фармацевтическая академия', ' ФГБОУ ВО ПГФА', 0, 1, 2),
(20, 'Пермский военный институт войск национальной гвардии РФ', 'ПВИ ВНГ РФ', 0, 1, 2),
(21, 'Пермский государственный медицинский университет им. Е.А. Вагнера', 'ПГМУ им. ак. Е.А. Вагнера', 0, 1, 2),
(22, 'Волжский государственный университет водного транспорта', 'ВГУВТ', 0, 1, 2),
(23, 'Российский экономический университет им. Г.В. Плеханова', 'РЭУ им. Г.В. Плеханова', 0, 1, 2),
(24, 'Российская академия народного хозяйства и государственной службы при Президенте РФ', 'РАНХиГС', 0, 1, 2),
(25, 'Пермский государственный аграрно-технологический университет им. Д.Н. Прянишникова', 'ПГАТУ', 0, 1, 2),
(26, 'Пермский государственный институт культуры', 'ПГИК', 0, 1, 2),
(27, 'Российская академия живописи, ваяния и зодчества И. Глазунова', 'РАЖВиЗ', 0, 1, 2),
(33, 'Пермский радиотехнический колледж им. А.С. Попова', 'ПРК им. А.С. Попова', 0, 1, 2),
(35, 'Пермский авиационный техникум им. А.Д. Швецова', 'ПАТ им. А.Д. Швецова', 0, 1, 2),
(37, 'Пермское государственное хореографическое училище', 'ПГХУ', 0, 1, 2),
(40, 'Пермский политехнический колледж им. Н.Г. Славянова', 'ППК им. Н.Г. Славянова', 0, 1, 2),
(43, 'Пермский химико-технологический техникум', 'ПХТТ', 0, 1, 2),
(46, 'Пермский техникум профессиональных технологий и дизайна', 'ПТПТД', 0, 1, 2),
(50, 'Колледж Уральский государственный университет путей сообщения', 'ПИЖТ УрГУПС', 0, 1, 2),
(52, 'Пермский колледж транспорта и сервиса', 'ПКТС', 0, 1, 2),
(58, 'Пермский государственный профессионально-педагогический колледж', 'ПГППК', 0, 1, 2),
(59, 'Пермский краевой колледж Оникс', 'ПКК \"ОНИКС\"', 0, 1, 2),
(79, 'Финансово-экономический колледж', 'ФЭК', 0, 1, 2),
(90, 'Колледж Российский экономический университет им. Г.В. Плеханова', 'Колледж РЭУ им. Г.В. Плеханова', 0, 1, 2),
(97, 'Колледж Финансовый университет при Правительстве РФ (г. Пермь)', 'Колледж ФУ при Правительстве РФ', 0, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` int NOT NULL,
  `title` text COLLATE utf8mb4_bin NOT NULL,
  `short_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `education_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `title`, `short_title`, `education_id`) VALUES
(1, 'Колледж Профессионального Образования', NULL, 1),
(2, 'Биологический', NULL, 1),
(3, 'Геологический', NULL, 1),
(4, 'Историко-политологический', NULL, 1),
(5, 'Механико-математический', NULL, 1),
(6, 'Филосовско-социологический', NULL, 1),
(7, 'Факультет севременных иностранных языков и литератур', NULL, 1),
(8, 'Физический', NULL, 1),
(9, 'Филологический', NULL, 1),
(10, 'Химический', NULL, 1),
(11, 'Экономический', NULL, 1),
(12, 'Юридический', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `title` text COLLATE utf8mb4_bin NOT NULL,
  `short_title` text COLLATE utf8mb4_bin,
  `students` text COLLATE utf8mb4_bin,
  `robots` text COLLATE utf8mb4_bin,
  `specialization_id` int NOT NULL,
  `head_student` int DEFAULT NULL,
  `deputy_head_student` int DEFAULT NULL,
  `year_of_admission` int NOT NULL,
  `admission_class` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `title`, `short_title`, `students`, `robots`, `specialization_id`, `head_student`, `deputy_head_student`, `year_of_admission`, `admission_class`) VALUES
(1, 'ИСП-1,2 2020 БО', 'ИСП-1,2', '[95,92]', '[]', 1, 95, NULL, 2020, 'БО'),
(6, 'ИСП-3,4 2020 БО', 'ИСП-3,4', NULL, NULL, 1, NULL, NULL, 2020, 'БО'),
(7, 'ПСО-1,2 2020 БО', 'ПСО-1,2', NULL, NULL, 1, NULL, NULL, 2020, 'БО'),
(8, 'ПСО-3,4 2020 БО', 'ПСО-3,4', NULL, NULL, 1, NULL, NULL, 2020, 'БО'),
(9, 'ПСО-5,6 2020 БО', 'ПСО-5,6', NULL, NULL, 1, NULL, NULL, 2020, 'БО'),
(10, 'БНД-1,2 2020 БО', 'БНД-1,2', NULL, NULL, 1, NULL, NULL, 2020, 'БО');

-- --------------------------------------------------------

--
-- Table structure for table `group_membership_requests`
--

CREATE TABLE `group_membership_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `group_id` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE `invites` (
  `id` int NOT NULL,
  `text_id` text COLLATE utf8mb4_bin,
  `inviting_user_id` int NOT NULL,
  `group_id` int NOT NULL,
  `date` text COLLATE utf8mb4_bin NOT NULL,
  `expires` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `invites`
--

INSERT INTO `invites` (`id`, `text_id`, `inviting_user_id`, `group_id`, `date`, `expires`) VALUES
(16, 'doqF4UNz', 95, 1, '09.04.2022 18:25:25', '0');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
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
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int NOT NULL,
  `technical_break` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `technical_break`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `id` int NOT NULL,
  `title` text COLLATE utf8mb4_bin NOT NULL,
  `short_title` text COLLATE utf8mb4_bin,
  `faculty_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`id`, `title`, `short_title`, `faculty_id`) VALUES
(1, 'Информационные системы и программирование', 'ИСП', 1),
(2, 'Банковское дело', 'БНД', 1),
(3, 'Право и организация социального обеспечения', 'ПСО', 1),
(4, 'Экономика и бухгалтерский учёт', 'ЭБУ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int NOT NULL,
  `email` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `theme` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `message` varchar(4096) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT 'Checking',
  `user_viewed` int NOT NULL DEFAULT '0',
  `answer` varchar(8192) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `admin_id` int DEFAULT '0',
  `appealer_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `united_education`
--

CREATE TABLE `united_education` (
  `id` int NOT NULL,
  `title` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `united_education`
--

INSERT INTO `united_education` (`id`, `title`) VALUES
(1, 'ПГНИУ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `reputation` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `friends` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `blacklist` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `first_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `last_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `patronymic` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
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
  `ban_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `delete_account_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `delete_account_date` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `privacy_messages` int NOT NULL DEFAULT '2' COMMENT '0 - никто не может присылать сообщения. 1 - только друзья. 2 - все.',
  `hi_icue` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `group_id` int DEFAULT NULL,
  `specialization_id` int DEFAULT NULL,
  `faculty_id` int DEFAULT NULL,
  `password_change_history` text COLLATE utf8mb4_bin,
  `login_history` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `google_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `token`, `reputation`, `friends`, `blacklist`, `first_name`, `last_name`, `patronymic`, `sex`, `email`, `password`, `education_id`, `city_id`, `photo`, `photo_style`, `bg_image`, `bg_image_style`, `registration_date`, `status`, `closed_profile`, `last_online`, `gif_user_photo`, `ban_reason`, `delete_account_reason`, `delete_account_date`, `privacy_messages`, `hi_icue`, `group_id`, `specialization_id`, `faculty_id`, `password_change_history`, `login_history`, `google_id`) VALUES
(92, '84c21cde78e18478d9478527a9424218', NULL, NULL, NULL, 'Александр', 'Соромотин', '1', '', 'sannekys@gmail.com', '001bb629acf4ad641ca834797285b0a3', 1, 0, 'http://frmjdg.com/find-students/uploads/user_avatars/679ff315fb163970fa04d05179ecb1dc.gif?v=b66dc16006694db57344fcc70ac0fea4', 'a:2:{s:5:\"ox_oy\";s:0:\"\";s:5:\"scale\";s:4:\"1.79\";}', 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2022-03-21 16:14:26', 'Admin', 0, '2022-03-21 16:14:26', 0, NULL, NULL, NULL, 2, NULL, 1, NULL, NULL, '0', '[{\"date\":\"16.04.2022 16:27\",\"details\":null},{\"date\":\"16.04.2022 13:33\",\"details\":null},{\"date\":\"16.04.2022 12:04\",\"details\":null},{\"date\":\"16.04.2022 11:03\",\"details\":null},{\"date\":\"16.04.2022 10:25\",\"details\":null},{\"date\":\"16.04.2022 09:19\",\"details\":null},{\"date\":\"15.04.2022 20:01\",\"details\":null},{\"date\":\"15.04.2022 19:24\",\"details\":null},{\"date\":\"15.04.2022 12:01\",\"details\":null},{\"date\":\"15.04.2022 11:01\",\"details\":null},{\"date\":\"15.04.2022 10:06\",\"details\":null},{\"date\":\"15.04.2022 09:01\",\"details\":null},{\"date\":\"15.04.2022 08:00\",\"details\":null},{\"date\":\"15.04.2022 07:22\",\"details\":null},{\"date\":\"14.04.2022 18:00\",\"details\":null},{\"date\":\"14.04.2022 17:53\",\"details\":null},{\"date\":\"13.04.2022 19:01\",\"details\":null},{\"date\":\"13.04.2022 18:46\",\"details\":null},{\"date\":\"13.04.2022 17:15\",\"details\":null},{\"date\":\"10.04.2022 12:01\",\"details\":null},{\"date\":\"10.04.2022 11:04\",\"details\":null},{\"date\":\"10.04.2022 10:02\",\"details\":null},{\"date\":\"10.04.2022 09:10\",\"details\":null},{\"date\":\"10.04.2022 08:01\",\"details\":null},{\"date\":\"10.04.2022 07:27\",\"details\":null},{\"date\":\"09.04.2022 21:20\",\"details\":null},{\"date\":\"09.04.2022 20:00\",\"details\":null},{\"date\":\"09.04.2022 19:21\",\"details\":null},{\"date\":\"07.04.2022 21:05\",\"details\":null},{\"date\":\"07.04.2022 20:40\",\"details\":null},{\"date\":\"07.04.2022 18:56\",\"details\":null},{\"date\":\"07.04.2022 17:18\",\"details\":null},{\"date\":\"07.04.2022 16:00\",\"details\":null},{\"date\":\"07.04.2022 15:21\",\"details\":null},{\"date\":\"06.04.2022 19:26\",\"details\":null},{\"date\":\"06.04.2022 13:00\",\"details\":null},{\"date\":\"06.04.2022 12:01\",\"details\":null},{\"date\":\"06.04.2022 10:10\",\"details\":null},{\"date\":\"06.04.2022 09:18\",\"details\":null},{\"date\":\"06.04.2022 07:31\",\"details\":null},{\"date\":\"06.04.2022 06:17\",\"details\":null},{\"date\":\"05.04.2022 20:39\",\"details\":null},{\"date\":\"05.04.2022 19:26\",\"details\":null},{\"date\":\"05.04.2022 17:00\",\"details\":null},{\"date\":\"05.04.2022 16:09\",\"details\":null},{\"date\":\"05.04.2022 15:16\",\"details\":null},{\"date\":\"05.04.2022 13:00\",\"details\":null},{\"date\":\"05.04.2022 12:56\",\"details\":null},{\"date\":\"04.04.2022 20:00\",\"details\":null},{\"date\":\"04.04.2022 19:00\",\"details\":null},{\"date\":\"04.04.2022 18:06\",\"details\":null},{\"date\":\"04.04.2022 17:08\",\"details\":null},{\"date\":\"03.04.2022 22:02\",\"details\":null},{\"date\":\"03.04.2022 21:00\",\"details\":null},{\"date\":\"03.04.2022 20:25\",\"details\":null},{\"date\":\"03.04.2022 19:03\",\"details\":null},{\"date\":\"03.04.2022 18:08\",\"details\":null},{\"date\":\"03.04.2022 16:07\",\"details\":null},{\"date\":\"03.04.2022 15:00\",\"details\":null},{\"date\":\"03.04.2022 14:37\",\"details\":null},{\"date\":\"03.04.2022 13:04\",\"details\":null},{\"date\":\"03.04.2022 12:01\",\"details\":null},{\"date\":\"03.04.2022 11:01\",\"details\":null},{\"date\":\"03.04.2022 10:00\",\"details\":null},{\"date\":\"03.04.2022 09:09\",\"details\":null},{\"date\":\"03.04.2022 05:35\",\"details\":null},{\"date\":\"02.04.2022 20:02\",\"details\":null},{\"date\":\"02.04.2022 19:05\",\"details\":null},{\"date\":\"02.04.2022 18:04\",\"details\":null},{\"date\":\"02.04.2022 17:43\",\"details\":null},{\"date\":\"01.04.2022 08:01\",\"details\":null},{\"date\":\"01.04.2022 07:53\",\"details\":null},{\"date\":\"01.04.2022 07:53\",\"details\":{\"browser\":\"Chrome\",\"ip\":\"93.186.225.208\",\"isp\":\"Vkontakte Ltd\",\"country\":null,\"region\":null,\"city\":null,\"lat\":null,\"lon\":null}},{\"date\":\"01.04.2022 07:52\",\"details\":{\"browser\":\"Chrome\",\"ip\":\"93.80.42.110\",\"isp\":\"PJSC Vimpelcom\",\"country\":null,\"region\":null,\"city\":null,\"lat\":null,\"lon\":null}},{\"date\":\"01.04.2022 07:50\",\"details\":{\"browser\":\"Chrome\",\"ip\":\"176.59.204.177\",\"isp\":\"T2 Mobile LLC\",\"country\":null,\"region\":null,\"city\":null,\"lat\":null,\"lon\":null}},{\"date\":\"01.04.2022 07:48\",\"details\":{\"browser\":\"Chrome\",\"ip\":\"93.80.42.110\",\"isp\":\"PJSC Vimpelcom\",\"country\":null,\"region\":null,\"city\":null,\"lat\":null,\"lon\":null}},{\"date\":\"01.04.2022 06:40\",\"details\":{\"browser\":\"Chrome\",\"ip\":\"93.80.42.110\",\"isp\":\"PJSC Vimpelcom\",\"country\":null,\"region\":null,\"city\":null,\"lat\":null,\"lon\":null}},{\"date\":\"01.04.2022 06:36\",\"details\":null},{\"date\":\"31.03.2022 22:38\",\"details\":null},{\"date\":\"30.03.2022 22:26\",\"details\":null}]', NULL),
(93, '88a866a06895e0cdbc75d6624009ba4e', NULL, NULL, NULL, 'Барыгин', 'Александр', NULL, '', 'add@mail.com', '001bb629acf4ad641ca834797285b0a3', 0, 0, 'http://frmjdg.com/assets/img/unknown-user.png', NULL, 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2022-03-24 21:27:18', 'User', 0, '2022-03-24 21:27:18', 0, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, '0', NULL, NULL),
(94, '2cada45ebf1e65b4aded18523bbb0f23', NULL, NULL, NULL, 'Нюх', 'Александр', NULL, '', '123@gmail.com', '001bb629acf4ad641ca834797285b0a3', 0, 0, 'http://frmjdg.com/assets/img/unknown-user.png', NULL, 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2022-03-25 15:08:58', 'User', 0, '2022-03-25 15:08:58', 0, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, '0', NULL, NULL),
(95, '99292239136bc827156b4698d971b911', NULL, NULL, NULL, 'Александр', 'sannekysovich', NULL, '', '1234@gmail.com', '001bb629acf4ad641ca834797285b0a3', 0, 0, 'http://frmjdg.com/find-students/uploads/user_avatars/d236a6accee1a652efaba555e300ad80.jpg', 'a:2:{s:5:\"ox_oy\";s:0:\"\";s:5:\"scale\";s:4:\"1.44\";}', 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2022-03-26 12:23:40', 'User', 0, '2022-03-26 12:23:40', 0, NULL, NULL, NULL, 2, NULL, 1, NULL, NULL, '0', '[{\"date\":\"09.04.2022 19:00\",\"details\":null},{\"date\":\"09.04.2022 18:02\",\"details\":null},{\"date\":\"09.04.2022 17:31\",\"details\":null},{\"date\":\"09.04.2022 16:01\",\"details\":null},{\"date\":\"09.04.2022 15:02\",\"details\":null},{\"date\":\"09.04.2022 14:04\",\"details\":null},{\"date\":\"09.04.2022 13:36\",\"details\":null},{\"date\":\"08.04.2022 08:00\",\"details\":null},{\"date\":\"08.04.2022 07:10\",\"details\":null},{\"date\":\"07.04.2022 22:01\",\"details\":null},{\"date\":\"07.04.2022 21:30\",\"details\":null},{\"date\":\"07.04.2022 17:36\",\"details\":null},{\"date\":\"03.04.2022 13:04\",\"details\":null},{\"date\":\"03.04.2022 12:43\",\"details\":null},{\"date\":\"02.04.2022 20:13\",\"details\":null},{\"date\":\"02.04.2022 19:24\",\"details\":null}]', NULL),
(96, 'c348232039931447bbea96db1a9b38c3', NULL, NULL, NULL, 'Aldad', 'awdawdad', NULL, '', '1@gmail.com', '001bb629acf4ad641ca834797285b0a3', 0, 0, 'http://frmjdg.com/assets/img/unknown-user.png', NULL, 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2022-03-28 15:58:36', 'User', 0, '2022-03-28 15:58:36', 0, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, '0', NULL, NULL),
(97, 'b73479dd3348f95bfac5f00cc2226596', NULL, NULL, NULL, '123', '123', NULL, '', 'legs.doawdawdo_0j@icloud.com', '001bb629acf4ad641ca834797285b0a3', 0, 0, 'http://frmjdg.com/assets/img/unknown-user.png', NULL, 'http://frmjdg.com/assets/img/bg_image.jpg', NULL, '2022-04-06 04:32:26', 'User', 0, '2022-04-06 04:32:26', 0, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, '[{\"date\":\"06.04.2022 07:32\",\"details\":null}]', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `visits_archive`
--

CREATE TABLE `visits_archive` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `students` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `date` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `visits_archive`
--

INSERT INTO `visits_archive` (`id`, `group_id`, `students`, `date`) VALUES
(22, 1, NULL, '04.04.2022'),
(23, 1, '{\"95\":{\"active\":false,\"history\":[{\"time\":\"2022.04.05 13:39:06\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.05 13:39:37\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.05 15:19:01\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.05 15:19:03\",\"activity\":\"forcibly-leave\"}]},\"92\":{\"active\":false,\"history\":[{\"time\":\"2022.04.05 13:40:04\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.05 17:42:30\",\"activity\":\"leave\"}]}}', '05.04.2022'),
(24, 1, '{\"92\":{\"active\":true,\"history\":[{\"time\":\"2022.04.06 12:52:40\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.06 12:52:47\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.06 12:52:49\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.06 12:52:51\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.06 12:53:41\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.06 12:53:43\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.06 12:53:44\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.06 12:53:45\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.06 12:53:48\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.06 12:53:49\",\"activity\":\"forcibly-join\"}]}}', '06.04.2022'),
(25, 1, '{\"92\":{\"active\":true,\"history\":[{\"time\":\"2022.04.07 15:41:57\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:47:02\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 15:47:04\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:47:11\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:47:13\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:48:40\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 15:48:41\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:48:42\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 15:48:43\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:56:19\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 15:56:22\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:56:27\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 15:56:29\",\"activity\":\"join\"},{\"time\":\"2022.04.07 15:59:02\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 15:59:05\",\"activity\":\"join\"},{\"time\":\"2022.04.07 16:00:00\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 16:00:01\",\"activity\":\"join\"},{\"time\":\"2022.04.07 16:00:04\",\"activity\":\"join\"},{\"time\":\"2022.04.07 16:00:37\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 16:00:39\",\"activity\":\"join\"},{\"time\":\"2022.04.07 16:04:59\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 16:08:23\",\"activity\":\"join\"},{\"time\":\"2022.04.07 16:08:24\",\"activity\":\"leave\"},{\"time\":\"2022.04.07 16:08:27\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.07 16:08:29\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.07 21:33:47\",\"activity\":\"forcibly-join\"}]},\"95\":{\"active\":true,\"history\":[{\"time\":\"2022.04.07 21:33:51\",\"activity\":\"forcibly-join\"}]}}', '07.04.2022'),
(26, 1, '{\"95\":{\"active\":true,\"history\":[{\"time\":\"2022.04.09 14:04:12\",\"activity\":\"join\"},{\"time\":\"2022.04.09 15:14:08\",\"activity\":\"leave\"},{\"time\":\"2022.04.09 15:14:11\",\"activity\":\"join\"},{\"time\":\"2022.04.09 15:26:35\",\"activity\":\"leave\"},{\"time\":\"2022.04.09 15:28:35\",\"activity\":\"join\"},{\"time\":\"2022.04.09 15:28:36\",\"activity\":\"leave\"},{\"time\":\"2022.04.09 15:39:57\",\"activity\":\"join\"},{\"time\":\"2022.04.09 15:39:57\",\"activity\":\"join\"},{\"time\":\"2022.04.09 15:39:57\",\"activity\":\"leave\"},{\"time\":\"2022.04.09 15:41:42\",\"activity\":\"join\"},{\"time\":\"2022.04.09 19:28:54\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.09 19:28:56\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.09 19:29:00\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.09 19:29:03\",\"activity\":\"forcibly-join\"}]},\"92\":{\"active\":true,\"history\":[{\"time\":\"2022.04.09 15:16:19\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.09 15:16:21\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.09 15:16:26\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.09 19:28:57\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.09 19:28:58\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.09 19:29:01\",\"activity\":\"forcibly-leave\"},{\"time\":\"2022.04.09 19:29:02\",\"activity\":\"forcibly-join\"}]}}', '09.04.2022'),
(27, 1, '{\"95\":{\"active\":false,\"history\":[{\"time\":\"2022.04.10 08:50:07\",\"activity\":\"forcibly-join\"},{\"time\":\"2022.04.10 08:50:09\",\"activity\":\"forcibly-leave\"}]}}', '10.04.2022'),
(28, 1, NULL, '13.04.2022'),
(30, 1, '{\"robot_625925e49dd17\":{\"active\":true,\"student_name\":\"adwda\",\"student_surname\":\"wadwad\",\"history\":[{\"time\":\"2022.04.15 11:55:58\",\"activity\":\"forcibly-join\"}]},\"92\":{\"active\":true,\"student_name\":null,\"student_surname\":null,\"history\":[{\"time\":\"2022.04.15 11:56:06\",\"activity\":\"forcibly-join\"}]},\"robot_62599e85d1e92\":{\"active\":true,\"student_name\":\"test\",\"student_surname\":\"test\",\"history\":[{\"time\":\"2022.04.15 20:04:20\",\"activity\":\"forcibly-join\"}]}}', '15.04.2022'),
(31, 1, '{\"robot_625925e49dd17\":{\"active\":true,\"student_name\":\"adwda\",\"student_surname\":\"wadwad\",\"history\":[{\"time\":\"2022.04.16 09:25:32\",\"activity\":\"forcibly-join\"}]}}', '16.04.2022');

-- --------------------------------------------------------

--
-- Table structure for table `visits_reports`
--

CREATE TABLE `visits_reports` (
  `id` int NOT NULL,
  `text_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `group_id` int NOT NULL,
  `archive` text COLLATE utf8mb4_bin NOT NULL,
  `date` text COLLATE utf8mb4_bin NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `visits_reports`
--

INSERT INTO `visits_reports` (`id`, `text_id`, `group_id`, `archive`, `date`, `user_id`) VALUES
(33, '384a9e389698dbbb31e604e0284a10d5', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '03.04.2022 21:50:09', 92),
(34, '6fa1f1ad4d078628d82e2cba3ce517d2', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 19:41:40', 92),
(35, '65d650c299ff5a42141c2d8c6affe974', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:13:40', 92),
(36, '64215a7a5c581116b1e23b52de4d1f77', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:14:28', 92),
(37, '853296330fc68c283d279baa105782c1', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:14:56', 92),
(38, 'b5efebb2d5c0cde63c506de93cb8a20a', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:19:01', 92),
(39, '9691d59633be281b88853e4715a42159', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:23:24', 92),
(40, 'ac0349f94833c3868532b1f3f8be5b36', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:24:00', 92),
(41, 'd50a12243bbc39e83261d30ce811242a', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:25:36', 92),
(42, 'dc2227affdb28cd612b47fe6fbe2b77c', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:26:27', 92),
(43, 'f2f8223562c559ed1c9f44cd29e4727a', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:26:39', 92),
(44, '35c2d8a7b63876e23e515aefe6f4b910', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '04.04.2022 20:27:32', 92),
(45, '7665091834bd9f70372525c41dc6982f', 1, '{\"present_students\":[],\"missing_students\":[\"95\",\"92\"]}', '05.04.2022 13:00:20', 92),
(46, '7b857bf54d3a0fc00f6a8eb4a21210fc', 1, '{\"present_students\":[],\"missing_students\":[\"92\"]}', '06.04.2022 09:51:16', 92),
(47, '2fb575e28420a27ed962e4855c8d937f', 1, '{\"present_students\":[92],\"missing_students\":[]}', '06.04.2022 12:54:04', 92),
(48, '28616cac395d90b21ea44fc9149d1bcc', 1, '{\"present_students\":[92],\"missing_students\":[]}', '06.04.2022 12:58:47', 92),
(49, '34a21e78f9fc87f4af43a30476bfc7ee', 1, '{\"present_students\":[95,92],\"missing_students\":[]}', '09.04.2022 19:26:55', 92),
(50, '0d55a58cfef9d01ff2e63ef0299e6952', 1, '{\"present_students\":[],\"missing_students\":[95,92]}', '14.04.2022 18:03:49', 92),
(51, 'b81cae2f929e5ba690e178e59ea69750', 1, '{\"present_students\":[],\"missing_students\":[95,92]}', '14.04.2022 18:40:36', 92),
(52, '8fc1273672de4b2bf18f9410f8efcc63', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 09:01:33', 92),
(53, '43edc30cab244b577c95067f0ec76ca9', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 09:28:04', 92),
(54, '45729c145898d47a14ca82ed6551c08f', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[]}', '15.04.2022 09:29:45', 92),
(55, 'b3dfa4efcf3641cc23709f054a3ac505', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[]}', '15.04.2022 09:30:33', 92),
(56, '74418c893eb2be98a27c5a7c256702f0', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:06:32', 92),
(57, '31f7c70bf44f1c1fc54adf85d04396fb', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:16:18', 92),
(58, 'f798200c05128b4d4589ea2564177ec8', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"[\",\"student_surname\":\"[\"}],\"missing_students\":[{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:38:08', 92),
(59, '92fb1b3c64c59d0b1895413b1bf4b3d5', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"[\",\"student_surname\":\"[\"}],\"missing_students\":[{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:43:19', 92),
(60, '12c59da5437c73c45a8fb7eb147be0d5', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"alexander\",\"student_surname\":\"sorom\"}],\"missing_students\":[{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:43:27', 92),
(61, '71826e30ab712747cd9830c78cad0bf0', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"[\",\"student_surname\":\"[\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:43:37', 92),
(62, 'd20134e83c4733e5120cdbd49b4f5b85', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"[\",\"student_surname\":\"[\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:44:02', 92),
(63, '5ac26b6568e31f00b80a25b2b9a34c93', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"alexander\",\"student_surname\":\"sorom\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 10:44:07', 92),
(64, 'a8927233de6dc89e4dd16c2805bfb5a2', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"alexander\",\"student_surname\":\"sorom\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 11:07:44', 92),
(65, '0b1b8f5c1667f2d9f1e08635a91d7441', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"}],\"missing_students\":[{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"alexander\",\"student_surname\":\"sorom\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 11:09:57', 92),
(66, '35b6e137ceaf7ba2cf9d141d3e2e0d8a', 1, '{\"present_students\":[{\"student_id\":92,\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"robot_62591c3caf9aa\",\"student_name\":\"alexander\",\"student_surname\":\"sorom\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 11:10:55', 92),
(67, '4169e19e83bd9f6c99d0cde423955c20', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"},92],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 11:56:21', 92),
(68, '78d114918962a1503a9ab4ef1a7a4776', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"},92],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 11:57:01', 92),
(69, 'db3e2f02bdf6eb7b310074a3d9bb8b38', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 19:43:40', 92),
(70, '64d519519c49678241833a94f282930b', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 19:47:17', 92),
(71, 'b901eaf3c351abf768313e4b49492864', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 19:47:44', 92),
(72, 'fd55eef675322ed9231dbe39b1ce62f6', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 19:48:35', 92),
(73, 'e11b1287eeddff64de920bf35fed94f7', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"robot_id\":\"robot_62599e85d1e92\",\"robot_name\":\"test\",\"robot_surname\":\"test\"},{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 19:58:37', 92),
(74, '931ef2a9eeebcd8250d2aadca3e9db33', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"robot_id\":\"robot_62599e85d1e92\",\"robot_name\":\"test\",\"robot_surname\":\"test\"},{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 19:59:37', 92),
(75, 'f7bfaa2e60ac729c928175f19a8900ea', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"robot_62599e85d1e92\",\"student_name\":\"test\",\"student_surname\":\"test\"},{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '15.04.2022 20:01:51', 92),
(76, '46b39f26c690baed86941d5e58a9f344', 1, '{\"present_students\":[],\"missing_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"},{\"student_id\":\"robot_62599e85d1e92\",\"student_name\":\"test\",\"student_surname\":\"test\"},{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '16.04.2022 09:25:23', 92),
(77, 'd96c7f56cf25b50a2c699e0f219f76d0', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"robot_62599e85d1e92\",\"student_name\":\"test\",\"student_surname\":\"test\"},{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '16.04.2022 09:25:36', 92),
(78, '7ff1c492101ca1c777599080136d43df', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '16.04.2022 09:25:48', 92),
(79, '3736c9cb6d54e2981bf53e42bac3d5b5', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '16.04.2022 09:25:57', 92),
(80, '0340419651819b40026cdddb21662bee', 1, '{\"present_students\":[{\"student_id\":\"robot_625925e49dd17\",\"student_name\":\"adwda\",\"student_surname\":\"wadwad\"}],\"missing_students\":[{\"student_id\":\"92\",\"student_name\":\"Александр\",\"student_surname\":\"Соромотин\"},{\"student_id\":\"95\",\"student_name\":\"Александр\",\"student_surname\":\"sannekysovich\"}]}', '16.04.2022 09:26:11', 92);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_add_education`
--
ALTER TABLE `application_add_education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_membership_requests`
--
ALTER TABLE `group_membership_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invites`
--
ALTER TABLE `invites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `united_education`
--
ALTER TABLE `united_education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visits_archive`
--
ALTER TABLE `visits_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visits_reports`
--
ALTER TABLE `visits_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_add_education`
--
ALTER TABLE `application_add_education`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `group_membership_requests`
--
ALTER TABLE `group_membership_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `invites`
--
ALTER TABLE `invites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `united_education`
--
ALTER TABLE `united_education`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `visits_archive`
--
ALTER TABLE `visits_archive`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `visits_reports`
--
ALTER TABLE `visits_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
