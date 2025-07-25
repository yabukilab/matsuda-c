-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2025 年 7 月 15 日 19:43
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `piano_lesson`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `Login_number` int(7) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `Lesson_date` date NOT NULL,
  `Lesson_time` time(5) NOT NULL,
  `lesson_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`id`, `Login_number`, `teacher_id`, `Lesson_date`, `Lesson_time`, `lesson_type_id`) VALUES
(16, 1234567, 2, '2025-07-18', '11:00:00.00000', 2),
(18, 1234567, 1, '2025-07-23', '13:00:00.00000', 2),
(19, 1234567, 2, '2025-07-28', '13:00:00.00000', 2),
(20, 1234567, 3, '2025-07-29', '13:00:00.00000', 3);

-- --------------------------------------------------------

--
-- テーブルの構造 `mst_lesson_types`
--

CREATE TABLE `mst_lesson_types` (
  `lesson_type_id` int(11) NOT NULL,
  `lesson_type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `mst_lesson_types`
--

INSERT INTO `mst_lesson_types` (`lesson_type_id`, `lesson_type_name`) VALUES
(1, '体験レッスン'),
(2, '通常レッスン'),
(3, '体験レッスン(OL)'),
(4, '通常レッスン(OL)');

-- --------------------------------------------------------

--
-- テーブルの構造 `mst_students`
--

CREATE TABLE `mst_students` (
  `Login_number` int(7) NOT NULL,
  `password` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `mst_students`
--

INSERT INTO `mst_students` (`Login_number`, `password`) VALUES
(1234567, 'password123'),
(1234568, 'password123');

-- --------------------------------------------------------

--
-- テーブルの構造 `mst_teachers`
--

CREATE TABLE `mst_teachers` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `mst_teachers`
--

INSERT INTO `mst_teachers` (`teacher_id`, `teacher_name`) VALUES
(1, '田中先生'),
(2, '鈴木先生'),
(3, '佐藤先生');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_comments_login` (`Login_number`),
  ADD KEY `fk_comments_teachers` (`teacher_id`),
  ADD KEY `care_gray` (`lesson_type_id`);

--
-- テーブルのインデックス `mst_lesson_types`
--
ALTER TABLE `mst_lesson_types`
  ADD PRIMARY KEY (`lesson_type_id`);

--
-- テーブルのインデックス `mst_students`
--
ALTER TABLE `mst_students`
  ADD PRIMARY KEY (`Login_number`);

--
-- テーブルのインデックス `mst_teachers`
--
ALTER TABLE `mst_teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- テーブルの AUTO_INCREMENT `mst_lesson_types`
--
ALTER TABLE `mst_lesson_types`
  MODIFY `lesson_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- テーブルの AUTO_INCREMENT `mst_teachers`
--
ALTER TABLE `mst_teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `Log_num` FOREIGN KEY (`Login_number`) REFERENCES `mst_students` (`Login_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `care_gray` FOREIGN KEY (`lesson_type_id`) REFERENCES `mst_lesson_types` (`lesson_type_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_lesson_type` FOREIGN KEY (`lesson_type_id`) REFERENCES `mst_lesson_types` (`lesson_type_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_students` FOREIGN KEY (`Login_number`) REFERENCES `mst_students` (`Login_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_teachers` FOREIGN KEY (`teacher_id`) REFERENCES `mst_teachers` (`teacher_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
