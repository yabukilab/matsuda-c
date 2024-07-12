-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-07-12 06:44:43
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `pm_train`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `car_number` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `reservation_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `seat_id`, `car_number`, `schedule_id`, `reservation_time`) VALUES
(4, 2, 11, 4, 42, '2024-07-12 04:26:51'),
(6, 2, 6, 0, 38, '2024-07-12 04:41:29'),
(7, 2, 7, 0, 38, '2024-07-12 04:41:39'),
(8, 2, 8, 0, 38, '2024-07-12 04:41:54'),
(9, 2, 9, 0, 38, '2024-07-12 04:53:09'),
(10, 2, 10, 0, 38, '2024-07-12 05:00:01'),
(11, 2, 12, 0, 43, '2024-07-12 05:00:13'),
(12, 2, 13, 0, 38, '2024-07-12 05:00:14'),
(13, 2, 14, 0, 38, '2024-07-12 05:01:17'),
(14, 2, 15, 0, 38, '2024-07-12 05:01:19'),
(15, 2, 16, 0, 38, '2024-07-12 05:01:23'),
(16, 2, 25, 4, 38, '2024-07-12 05:05:27');

-- --------------------------------------------------------

--
-- テーブルの構造 `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `departure_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `arrival_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `station_id`, `departure_time`, `arrival_time`) VALUES
(38, 1, '2024-07-11 23:00:00', NULL),
(39, 1, '2024-07-12 00:00:00', NULL),
(40, 2, '2024-07-11 23:05:00', NULL),
(41, 2, '2024-07-12 00:05:00', NULL),
(42, 3, '2024-07-11 23:10:00', NULL),
(43, 3, '2024-07-12 00:10:00', NULL),
(44, 4, '2024-07-11 23:15:00', NULL),
(45, 4, '2024-07-12 00:15:00', NULL),
(46, 5, '2024-07-11 23:20:00', NULL),
(47, 5, '2024-07-12 00:20:00', NULL),
(48, 6, '2024-07-11 23:25:00', NULL),
(49, 6, '2024-07-12 00:25:00', NULL),
(50, 7, '2024-07-11 23:30:00', NULL),
(51, 7, '2024-07-12 00:30:00', NULL),
(52, 8, '2024-07-11 23:35:00', NULL),
(53, 8, '2024-07-12 00:35:00', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `seat`
--

CREATE TABLE `seat` (
  `seat_id` int(11) NOT NULL,
  `car_number` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `is_reserved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `seat`
--

INSERT INTO `seat` (`seat_id`, `car_number`, `seat_number`, `is_reserved`) VALUES
(1, 4, '1A', 1),
(2, 4, '1B', 1),
(3, 4, '1C', 1),
(4, 4, '1D', 1),
(5, 4, '2A', 1),
(6, 4, '2B', 1),
(7, 4, '2C', 1),
(8, 4, '2D', 1),
(9, 4, '3A', 1),
(10, 4, '3B', 1),
(11, 4, '3C', 1),
(12, 4, '3D', 1),
(13, 4, '4A', 1),
(14, 4, '4B', 1),
(15, 4, '4C', 1),
(16, 4, '4D', 1),
(17, 4, '5A', 0),
(18, 4, '5B', 0),
(19, 4, '5C', 0),
(20, 4, '5D', 0),
(21, 4, '6A', 0),
(22, 4, '6B', 0),
(23, 4, '6C', 0),
(24, 4, '6D', 0),
(25, 4, '7A', 1),
(26, 4, '7B', 0),
(27, 4, '7C', 0),
(28, 4, '7D', 0),
(29, 4, '8A', 0),
(30, 4, '8B', 0),
(31, 4, '8C', 0),
(32, 4, '8D', 0),
(33, 4, '9A', 0),
(34, 4, '9B', 0),
(35, 4, '9C', 0),
(36, 4, '9D', 0),
(37, 4, '10A', 0),
(38, 4, '10B', 0),
(39, 4, '10C', 0),
(40, 4, '10D', 0),
(41, 4, '11A', 0),
(42, 4, '11B', 0),
(43, 4, '11C', 0),
(44, 4, '11D', 0),
(45, 4, '12A', 0),
(46, 4, '12B', 0),
(47, 4, '12C', 0),
(48, 4, '12D', 0),
(49, 4, '13A', 0),
(50, 4, '13B', 0),
(51, 4, '13C', 0),
(52, 4, '13D', 0),
(53, 4, '14A', 0),
(54, 4, '14B', 0),
(55, 4, '14C', 0),
(56, 4, '14D', 0),
(57, 4, '15A', 0),
(58, 4, '15B', 0),
(59, 4, '15C', 0),
(60, 4, '15D', 0),
(61, 4, '16A', 0),
(62, 4, '16B', 0),
(63, 4, '16C', 0),
(64, 4, '16D', 0),
(65, 4, '17A', 0),
(66, 4, '17B', 0),
(67, 4, '17C', 0),
(68, 4, '17D', 0),
(69, 4, '18A', 0),
(70, 4, '18B', 0),
(71, 4, '18C', 0),
(72, 4, '18D', 0),
(73, 4, '19A', 0),
(74, 4, '19B', 0),
(75, 4, '19C', 0),
(76, 4, '19D', 0),
(77, 4, '20A', 0),
(78, 4, '20B', 0),
(79, 4, '20C', 0),
(80, 4, '20D', 0),
(81, 4, '21A', 0),
(82, 4, '21B', 0),
(83, 4, '21C', 0),
(84, 4, '21D', 0),
(85, 4, '22A', 0),
(86, 4, '22B', 0),
(87, 4, '22C', 0),
(88, 4, '22D', 0),
(89, 4, '23A', 0),
(90, 4, '23B', 0),
(91, 4, '23C', 0),
(92, 4, '23D', 0),
(93, 5, '1A', 0),
(94, 5, '1B', 0),
(95, 5, '1C', 0),
(96, 5, '1D', 0),
(97, 5, '2A', 0),
(98, 5, '2B', 0),
(99, 5, '2C', 0),
(100, 5, '2D', 0),
(101, 5, '3A', 0),
(102, 5, '3B', 0),
(103, 5, '3C', 0),
(104, 5, '3D', 0),
(105, 5, '4A', 0),
(106, 5, '4B', 0),
(107, 5, '4C', 0),
(108, 5, '4D', 0),
(109, 5, '5A', 0),
(110, 5, '5B', 0),
(111, 5, '5C', 0),
(112, 5, '5D', 0),
(113, 5, '6A', 0),
(114, 5, '6B', 0),
(115, 5, '6C', 0),
(116, 5, '6D', 0),
(117, 5, '7A', 0),
(118, 5, '7B', 0),
(119, 5, '7C', 0),
(120, 5, '7D', 0),
(121, 5, '8A', 0),
(122, 5, '8B', 0),
(123, 5, '8C', 0),
(124, 5, '8D', 0),
(125, 5, '9A', 0),
(126, 5, '9B', 0),
(127, 5, '9C', 0),
(128, 5, '9D', 0),
(129, 5, '10A', 0),
(130, 5, '10B', 0),
(131, 5, '10C', 0),
(132, 5, '10D', 0),
(133, 5, '11A', 0),
(134, 5, '11B', 0),
(135, 5, '11C', 0),
(136, 5, '11D', 0),
(137, 5, '12A', 0),
(138, 5, '12B', 0),
(139, 5, '12C', 0),
(140, 5, '12D', 0),
(141, 5, '13A', 0),
(142, 5, '13B', 0),
(143, 5, '13C', 0),
(144, 5, '13D', 0),
(145, 5, '14A', 0),
(146, 5, '14B', 0),
(147, 5, '14C', 0),
(148, 5, '14D', 0),
(149, 5, '15A', 0),
(150, 5, '15B', 0),
(151, 5, '15C', 0),
(152, 5, '15D', 0),
(153, 5, '16A', 0),
(154, 5, '16B', 0),
(155, 5, '16C', 0),
(156, 5, '16D', 0),
(157, 5, '17A', 0),
(158, 5, '17B', 0),
(159, 5, '17C', 0),
(160, 5, '17D', 0),
(161, 5, '18A', 0),
(162, 5, '18B', 0),
(163, 5, '18C', 0),
(164, 5, '18D', 0),
(165, 5, '19A', 0),
(166, 5, '19B', 0),
(167, 5, '19C', 0),
(168, 5, '19D', 0),
(169, 5, '20A', 0),
(170, 5, '20B', 0),
(171, 5, '20C', 0),
(172, 5, '20D', 0),
(173, 5, '21A', 0),
(174, 5, '21B', 0),
(175, 5, '21C', 0),
(176, 5, '21D', 0),
(177, 5, '22A', 0),
(178, 5, '22B', 0),
(179, 5, '22C', 0),
(180, 5, '22D', 0),
(181, 5, '23A', 0),
(182, 5, '23B', 0),
(183, 5, '23C', 0),
(184, 5, '23D', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `stations`
--

CREATE TABLE `stations` (
  `station_id` int(11) NOT NULL,
  `station_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `stations`
--

INSERT INTO `stations` (`station_id`, `station_name`) VALUES
(1, '東京'),
(2, '新日本橋'),
(3, '馬喰町'),
(4, '錦糸町'),
(5, '新小岩'),
(6, '市川'),
(7, '船橋'),
(8, '津田沼'),
(9, '稲毛'),
(10, '千葉');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `suica_number` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `suica_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`user_id`, `suica_number`, `user_name`, `suica_hash`) VALUES
(1, 'sample_suica', 'testuser', 'password_hash'),
(2, 'ss', 'ss', ''),
(3, 'ww', 'wa', '');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `seat_id` (`seat_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- テーブルのインデックス `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `station_id` (`station_id`);

--
-- テーブルのインデックス `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`seat_id`);

--
-- テーブルのインデックス `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`station_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- テーブルの AUTO_INCREMENT `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- テーブルの AUTO_INCREMENT `seat`
--
ALTER TABLE `seat`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- テーブルの AUTO_INCREMENT `stations`
--
ALTER TABLE `stations`
  MODIFY `station_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seat` (`seat_id`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`);

--
-- テーブルの制約 `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`station_id`) REFERENCES `stations` (`station_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
