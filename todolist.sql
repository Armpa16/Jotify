-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 12:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todolist`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `calendar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `display_mode` varchar(20) NOT NULL,
  `view_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color_code` varchar(7) NOT NULL,
  `icon_url` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `noti_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `noti_type` varchar(50) NOT NULL,
  `send_time` datetime NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `timeout_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL,
  `status_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `user_id`, `title`, `note`, `color`, `timeout_date`, `created_at`, `updated_at`, `status`, `status_at`) VALUES
(8, 1, 'งานจุล', 'พรีเซ้่นรอบ3', '#238300', '2025-02-22', '2025-02-10 02:59:53', '2025-02-10 02:59:53', 'done', '2025-02-22 16:20:16'),
(9, 1, 'การบ้าน', 'อ.คิม', '#CC0000', '0000-00-00', '2025-02-10 03:18:20', '2025-02-10 03:18:20', 'done', '2025-02-19 14:09:59'),
(11, 1, 'ส่งบันทึกสมุดจุล', 'ก่อนรอบ 4 ครั้ง 1', '#D1A209', '2025-03-03', '2025-02-19 08:38:54', '2025-02-19 08:38:54', 'done', '2025-03-09 14:23:42'),
(12, 1, 'Object Detection', 'งาน อ.ใหม่', '#D1A209', '2025-03-12', '2025-02-19 12:05:22', '2025-02-19 12:05:22', NULL, NULL),
(13, 1, 'การบ้าน', 'lab อ.คิม', '#238300', '2025-03-05', '2025-02-19 13:10:33', '2025-02-19 13:10:33', NULL, NULL),
(22, 1, 'ส่งงาน', 'วิศวกรรมซอฟเเวร์', '#D1A209', '2025-03-06', '2025-02-19 17:13:46', '2025-02-19 17:13:46', 'done', '2025-03-11 19:19:02'),
(36, 1, 'อบรม ai', 'line api', '#238300', '2025-03-10', '2025-02-22 18:15:21', '2025-02-22 18:15:21', NULL, NULL),
(37, 1, 'สอบปลายภาค', 'อ.ใหม่', '#CC0000', '2025-03-17', '2025-02-22 18:50:54', '2025-02-22 18:50:54', NULL, NULL),
(38, 11, 'aj.mai', 'ai', '#CC0000', '2025-03-06', '2025-02-23 06:53:42', '2025-02-23 06:53:42', NULL, NULL),
(39, 1, 'mobileapp', 'aj.kim progress', '#D1A209', '2025-03-10', '2025-02-23 16:10:10', '2025-02-23 16:10:10', 'done', '2025-03-11 19:29:52'),
(48, 1, 'จุลนิพนธ์', 'นำเสนอ ครั้งที่ 4', '#CC0000', '2025-04-05', '2025-03-11 18:29:05', '2025-03-11 18:29:05', 'done', '2025-03-11 19:29:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `surname`, `email`, `password_hash`) VALUES
(1, 'ภาณุเดช', 'สุสัณกุลธร', 'panudech1419@gmail.com', '$2y$10$qTE4ybnoy51k.63eHgdAbex9XkZ39zrg/KyZUYFT90KNzvvRT1bue'),
(2, 'ddd', 'asasd', 'armarm2629@gmail.com', '$2y$10$rpyQxCLUBUi687.Rfe1.f.irmPYbxHLGreWC5YxEggCa/G/UjH9qq'),
(3, 'sss', 'sss', 'arm@gmail.com', '$2y$10$WGiBdZpaD82qkLIU1XftH.qpPfRhHmhKBs05hHH5wdtBA0LdY6psi'),
(4, 'knn', 'll', 'kinaeiou@gmail.com', '$2y$10$nuntoxZgqVQ5R/t5DO24/eRDDrCDjgypjH.yThZMD374GUNcO0X8C'),
(5, 'rrr', 'rrrr', 'rrr@gmail.com', '$2y$10$0D1M1A02T30JpzbbvluccuH99GkmyhSS8LxwrfZaqBXi96RWlRxuG'),
(6, 'uuuu', 'uuuu', 'uuuuu@gmail.com', '$2y$10$1X04RVoy4qNF/lgu5qfdtelVqBeVMRGKLO.KvwtY5qPgprtUv8Vl.'),
(11, 'ภาณุเดช', 'สุสัณกุลธร', 'susankunthorn_p@silpakorn.edu', '$2y$10$2V8ZhCS3eUe1WDgnMHLUBOldvQK1C4UcIiT/l8EcKqcagoIDaVSVS'),
(12, 'lla', 'oo', 'llak@gmail.com', '$2y$10$X.inxgdFSdNWTQVATxZJzeqWcOSynXMJAzJ2RvDCSEc6gampTvxqS'),
(13, 'lla', 'hh', 'lla@gmail.com', '$2y$10$hL97Ht1CwvO7VUHyXcX.juhr0rrZiHGC79PtRZddPbPOFta2Bv3Te'),
(14, 'ffa', 'hh', 'ffa@gmail.com', '$2y$10$eLVPyy6VpG05C/6h7bo.kO3H5xWdyNO7PoNxZ1r.rIy11Cka0yXQK'),
(16, 'ppa', 'ss', 'ppa@gmail.com', '$2y$10$qYF8suiZGWzgZMnoY/8qJOhgML0jS6bBonnCBvu0Id5ncTXHlufu.'),
(17, 'llr', 'p', 'llr@gmail.com', '$2y$10$k5zuweTz71UXtT.G8uxW9uecsS8qMooXc1t8yebRiT6JDUHFB5LMu'),
(18, 'test2', 'a', 'test2@gmail.com', '$2y$10$KJZRG5OjDgoP1EluXl99lOQ0wd332m.OdRAyZFB62z8jUCdXLF0pS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`calendar_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`noti_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `userid_FK` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `calendar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `noti_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `userid_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
