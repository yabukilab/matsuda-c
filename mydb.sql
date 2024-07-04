-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-07-04 08:34:22
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
-- データベース: `music_archive`
--
CREATE DATABASE IF NOT EXISTS `music_archive` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `music_archive`;

-- --------------------------------------------------------

--
-- テーブルの構造 `music`
--

CREATE TABLE `music` (
  `music_id` int(11) NOT NULL,
  `music_user_id` varchar(30) NOT NULL,
  `music_name` varchar(255) NOT NULL,
  `music_artist` varchar(255) NOT NULL,
  `music_category` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `music`
--

INSERT INTO `music` (`music_id`, `music_user_id`, `music_name`, `music_artist`, `music_category`) VALUES
(27, 'kazuya', '北酒場', '演歌', 'ドライブ'),
(29, 'kazuya', 'どこにも負けない', '角上魚類', '仕事'),
(30, 'wata', '習志野市歌', '習志野市', '落ち込んだ時'),
(31, 'wata', '軍歌', '大日本帝国', '落ち込んだ時');

-- --------------------------------------------------------

--
-- テーブルの構造 `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `test`
--

INSERT INTO `test` (`id`, `text`) VALUES
(1, ''),
(2, 'テスト実行'),
(3, '２回目だよ（笑）');

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `user_id` varchar(30) NOT NULL,
  `user_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ユーザ管理テーブル';

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`user_id`, `user_pass`) VALUES
('a', 'a'),
('aa', 'ss'),
('aaaa', 'se'),
('ass', '$2y$10$StT59KEJyV4x0Hh7r2rWyOa1pLc34x5sg/9QVfnJYq0BMzKhr/fHe'),
('kazuya', 'aaaa'),
('poke', '$2y$10$FM1cNn0axkxw9qVD7dfw.uNeUjqwGnqH5kcrrfPxyM8SacpxU8qZm'),
('qq', '$2y$10$J9K7RqJ0W/DYiR8zDsPZPe5FOtNv9d79mTL69eCmK9prpAnwgz/ny'),
('sa', 'ss'),
('wata', 'aa');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`music_id`),
  ADD KEY `music_user_id` (`music_user_id`);

--
-- テーブルのインデックス `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `music`
--
ALTER TABLE `music`
  MODIFY `music_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- テーブルの AUTO_INCREMENT `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `music_ibfk_1` FOREIGN KEY (`music_user_id`) REFERENCES `user` (`user_id`);
--
-- データベース: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

--
-- テーブルのデータのダンプ `pma__designer_settings`
--

INSERT INTO `pma__designer_settings` (`username`, `settings_data`) VALUES
('root', '{\"angular_direct\":\"direct\",\"snap_to_grid\":\"off\",\"relation_lines\":\"true\"}');

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- テーブルのデータのダンプ `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"pm_train\",\"table\":\"seat\"},{\"db\":\"sobu_line\",\"table\":\"train_set\"},{\"db\":\"sobu_line\",\"table\":\"trains\"},{\"db\":\"music_archive\",\"table\":\"user\"},{\"db\":\"music_archive\",\"table\":\"music\"},{\"db\":\"music_archive\",\"table\":\"test\"},{\"db\":\"sobu_line\",\"table\":\"stations\"},{\"db\":\"sobu_line\",\"table\":\"user\"}]');

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- テーブルのデータのダンプ `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2024-06-20 07:33:05', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"ja\"}');

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- テーブルの構造 `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- テーブルのインデックス `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- テーブルのインデックス `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- テーブルのインデックス `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- テーブルのインデックス `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- テーブルのインデックス `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- テーブルのインデックス `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- テーブルのインデックス `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- テーブルのインデックス `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- テーブルのインデックス `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- テーブルのインデックス `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- テーブルのインデックス `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- テーブルのインデックス `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- テーブルのインデックス `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- テーブルのインデックス `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- テーブルのインデックス `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- テーブルのインデックス `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- テーブルのインデックス `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- データベース: `pm_train`
--
CREATE DATABASE IF NOT EXISTS `pm_train` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pm_train`;

-- --------------------------------------------------------

--
-- テーブルの構造 `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `car_number` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `reservation_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `schedules`
--

CREATE TABLE `schedules` (
  `station_id` int(11) DEFAULT NULL,
  `departure_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `schedules`
--

INSERT INTO `schedules` (`station_id`, `departure_time`) VALUES
(1, '08:00:00'),
(1, '09:00:00'),
(2, '08:05:00'),
(2, '09:05:00'),
(3, '08:10:00'),
(3, '09:10:00'),
(4, '08:15:00'),
(4, '09:15:00'),
(5, '08:20:00'),
(5, '09:20:00'),
(6, '08:25:00'),
(6, '09:25:00'),
(7, '08:30:00'),
(7, '09:30:00'),
(8, '08:35:00'),
(8, '09:35:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `seat`
--

CREATE TABLE `seat` (
  `seat_number` varchar(10) NOT NULL,
  `car_number` int(11) NOT NULL,
  `is_reserved` tinyint(1) DEFAULT 0,
  `seat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `seat`
--

INSERT INTO `seat` (`seat_number`, `car_number`, `is_reserved`, `seat_id`) VALUES
('1A', 4, 1, 1),
('1B', 4, 0, 2),
('1C', 4, 0, 3),
('1D', 4, 0, 4),
('2A', 4, 0, 5),
('2B', 4, 0, 6),
('2C', 4, 0, 7),
('2D', 4, 0, 8),
('3A', 4, 0, 9),
('3B', 4, 0, 10),
('3C', 4, 0, 11),
('3D', 4, 0, 12),
('4A', 4, 0, 13),
('4B', 4, 0, 14),
('4C', 4, 0, 15),
('4D', 4, 0, 16),
('5A', 4, 0, 17),
('5B', 4, 0, 18),
('5C', 4, 0, 19),
('5D', 4, 0, 20),
('6A', 4, 0, 21),
('6B', 4, 0, 22),
('6C', 4, 0, 23),
('6D', 4, 0, 24),
('7A', 4, 0, 25),
('7B', 4, 0, 26),
('7C', 4, 0, 27),
('7D', 4, 0, 28),
('8A', 4, 0, 29),
('8B', 4, 0, 30),
('8C', 4, 0, 31),
('8D', 4, 0, 32),
('9A', 4, 0, 33),
('9B', 4, 0, 34),
('9C', 4, 0, 35),
('9D', 4, 0, 36),
('10A', 4, 0, 37),
('10B', 4, 0, 38),
('10C', 4, 0, 39),
('10D', 4, 0, 40),
('11A', 4, 0, 41),
('11B', 4, 0, 42),
('11C', 4, 0, 43),
('11D', 4, 0, 44),
('12A', 4, 0, 45),
('12B', 4, 0, 46),
('12C', 4, 0, 47),
('12D', 4, 0, 48),
('13A', 4, 0, 49),
('13B', 4, 0, 50),
('13C', 4, 0, 51),
('13D', 4, 0, 52),
('14A', 4, 0, 53),
('14B', 4, 0, 54),
('14C', 4, 0, 55),
('14D', 4, 0, 56),
('15A', 4, 0, 57),
('15B', 4, 0, 58),
('15C', 4, 0, 59),
('15D', 4, 0, 60),
('16A', 4, 0, 61),
('16B', 4, 0, 62),
('16C', 4, 0, 63),
('16D', 4, 0, 64),
('17A', 4, 0, 65),
('17B', 4, 0, 66),
('17C', 4, 0, 67),
('17D', 4, 0, 68),
('18A', 4, 0, 69),
('18B', 4, 0, 70),
('18C', 4, 0, 71),
('18D', 4, 0, 72),
('19A', 4, 0, 73),
('19B', 4, 0, 74),
('19C', 4, 0, 75),
('19D', 4, 0, 76),
('20A', 4, 0, 77),
('20B', 4, 0, 78),
('20C', 4, 0, 79),
('20D', 4, 0, 80),
('21A', 4, 0, 81),
('21B', 4, 0, 82),
('21C', 4, 0, 83),
('21D', 4, 0, 84),
('22A', 4, 0, 85),
('22B', 4, 0, 86),
('22C', 4, 0, 87),
('22D', 4, 0, 88),
('23A', 4, 0, 89),
('23B', 4, 0, 90),
('23C', 4, 0, 91),
('23D', 4, 0, 92),
('1A', 5, 0, 93),
('1B', 5, 0, 94),
('1C', 5, 0, 95),
('1D', 5, 0, 96),
('2A', 5, 0, 97),
('2B', 5, 0, 98),
('2C', 5, 0, 99),
('2D', 5, 0, 100),
('3A', 5, 0, 101),
('3B', 5, 0, 102),
('3C', 5, 0, 103),
('3D', 5, 0, 104),
('4A', 5, 0, 105),
('4B', 5, 0, 106),
('4C', 5, 0, 107),
('4D', 5, 0, 108),
('5A', 5, 0, 109),
('5B', 5, 0, 110),
('5C', 5, 0, 111),
('5D', 5, 0, 112),
('6A', 5, 0, 113),
('6B', 5, 0, 114),
('6C', 5, 0, 115),
('6D', 5, 0, 116),
('7A', 5, 0, 117),
('7B', 5, 0, 118),
('7C', 5, 0, 119),
('7D', 5, 0, 120),
('8A', 5, 0, 121),
('8B', 5, 0, 122),
('8C', 5, 0, 123),
('8D', 5, 0, 124),
('9A', 5, 0, 125),
('9B', 5, 0, 126),
('9C', 5, 0, 127),
('9D', 5, 0, 128),
('10A', 5, 0, 129),
('10B', 5, 0, 130),
('10C', 5, 0, 131),
('10D', 5, 0, 132),
('11A', 5, 0, 133),
('11B', 5, 0, 134),
('11C', 5, 0, 135),
('11D', 5, 0, 136),
('12A', 5, 0, 137),
('12B', 5, 0, 138),
('12C', 5, 0, 139),
('12D', 5, 0, 140),
('13A', 5, 0, 141),
('13B', 5, 0, 142),
('13C', 5, 0, 143),
('13D', 5, 0, 144),
('14A', 5, 0, 145),
('14B', 5, 0, 146),
('14C', 5, 0, 147),
('14D', 5, 0, 148),
('15A', 5, 0, 149),
('15B', 5, 0, 150),
('15C', 5, 0, 151),
('15D', 5, 0, 152),
('16A', 5, 0, 153),
('16B', 5, 0, 154),
('16C', 5, 0, 155),
('16D', 5, 0, 156),
('17A', 5, 0, 157),
('17B', 5, 0, 158),
('17C', 5, 0, 159),
('17D', 5, 0, 160),
('18A', 5, 0, 161),
('18B', 5, 0, 162),
('18C', 5, 0, 163),
('18D', 5, 0, 164),
('19A', 5, 0, 165),
('19B', 5, 0, 166),
('19C', 5, 0, 167),
('19D', 5, 0, 168),
('20A', 5, 0, 169),
('20B', 5, 0, 170),
('20C', 5, 0, 171),
('20D', 5, 0, 172),
('21A', 5, 0, 173),
('21B', 5, 0, 174),
('21C', 5, 0, 175),
('21D', 5, 0, 176),
('22A', 5, 0, 177),
('22B', 5, 0, 178),
('22C', 5, 0, 179),
('22D', 5, 0, 180),
('23A', 5, 0, 181),
('23B', 5, 0, 182),
('23C', 5, 0, 183),
('23D', 5, 0, 184);

-- --------------------------------------------------------

--
-- テーブルの構造 `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `is_reserved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(5, '市川'),
(6, '船橋'),
(7, '津田沼'),
(8, '千葉');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `user_id` varchar(255) NOT NULL,
  `suica_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`user_id`, `suica_number`) VALUES
('パンプキン', 'JE801 B201 2040 4734'),
('watanabe', 'JE801 B201 2040 4734'),
('taisei', 'JE801120070807952'),
('watanabe', 'JE801 B201 2040 4760'),
('as', 'aa'),
('a', 'a'),
('a', 'a'),
('s', 's');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `schedules`
--
ALTER TABLE `schedules`
  ADD KEY `station_id` (`station_id`);

--
-- テーブルのインデックス `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`seat_id`);

--
-- テーブルのインデックス `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD UNIQUE KEY `seat_number` (`seat_number`);

--
-- テーブルのインデックス `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`station_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- テーブルの AUTO_INCREMENT `seat`
--
ALTER TABLE `seat`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- テーブルの AUTO_INCREMENT `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- テーブルの AUTO_INCREMENT `stations`
--
ALTER TABLE `stations`
  MODIFY `station_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`station_id`) REFERENCES `stations` (`station_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
