-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2022 at 05:32 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `estock`
--

-- --------------------------------------------------------

--
-- Table structure for table `catalogs`
--

CREATE TABLE `catalogs` (
  `cat_id` int(13) NOT NULL,
  `cat_name` varchar(250) NOT NULL,
  `cat_detail` text NOT NULL,
  `cat_sort` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `catalogs`
--

INSERT INTO `catalogs` (`cat_id`, `cat_name`, `cat_detail`, `cat_sort`) VALUES
(100, 'สำนักงาน', '', 1),
(200, 'คอมพิวเตอร์', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pro_id` int(13) NOT NULL,
  `pro_name` varchar(250) NOT NULL,
  `pro_detail` text DEFAULT NULL,
  `cat_id` int(13) DEFAULT NULL,
  `unit_id` int(13) DEFAULT NULL,
  `instrok` int(10) DEFAULT NULL,
  `locat` varchar(250) DEFAULT NULL,
  `lower` int(10) DEFAULT NULL,
  `min` int(10) DEFAULT NULL,
  `st` int(10) DEFAULT NULL,
  `img` varchar(250) NOT NULL,
  `own` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pro_id`, `pro_name`, `pro_detail`, `cat_id`, `unit_id`, `instrok`, `locat`, `lower`, `min`, `st`, `img`, `own`, `created_at`, `update_at`) VALUES
(1, 'sss', '', 100, 2, NULL, 'sss1', 1, 1, 0, '1651418476.jpg', 'phayao sonplai', '2022-05-01 14:01:35', '2022-05-01 22:29:19'),
(2, 'd2222', '', 100, 2, NULL, '2222', 1, 1, 1, '1651418972.png', 'phayao sonplai', '2022-05-01 17:58:58', '2022-05-01 22:29:32'),
(3, 'dd3333', '', 100, 1, NULL, '', 1, 1, 1, '3.jpg', 'phayao sonplai', '2022-05-01 18:00:53', '2022-05-01 21:42:14'),
(4, 's44444', '', 100, 1, NULL, '', 1, 1, 1, '4.png', 'phayao sonplai', '2022-05-01 18:02:29', '2022-05-01 21:37:05'),
(5, 's444445', '', 200, 1, NULL, '', 1, 1, 1, '1651418460.png', 'phayao sonplai', '2022-05-01 18:02:48', '2022-05-01 22:21:00'),
(6, 'd5555', '', 100, 1, NULL, '', 1, 1, 1, '1651418502.jpg', 'phayao sonplai', '2022-05-01 18:03:51', '2022-05-01 22:21:42'),
(7, 'www', '', 100, 1, NULL, 'ww', 1, 1, 1, '', 'phayao sonplai', '2022-05-01 22:29:46', '2022-05-01 22:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(13) NOT NULL,
  `unit_name` varchar(250) NOT NULL,
  `unit_detail` varchar(250) DEFAULT NULL,
  `to_unit_id` int(13) DEFAULT NULL,
  `to_unit_ratio` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `unit_detail`, `to_unit_id`, `to_unit_ratio`) VALUES
(1, 'ม้วน', NULL, NULL, NULL),
(2, 'รีม', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(13) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(100) NOT NULL,
  `fullname` varchar(250) NOT NULL,
  `dep` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `fullname`, `dep`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'e29ckg', '$2y$10$37GMNcQApVM7OUuLxkbFB.LKPLpIxf6etEtYe6v2eWb.CT69LMG8e', 'e29ckg@gmail.com', '', 'phayao sonplai', '', '', '0000-00-00 00:00:00', '2022-04-30 03:41:48'),
(2, 'user', '$2y$10$9G0.cEbyLt89jAoE7v8CTeMMe0L6ruj8PlG/.NuG7jmQLLwXtKpHu', '1234@gmail.com', '', 'phayao sonplai222', '', '', '2022-04-30 00:00:00', '2022-04-30 06:09:05'),
(3, '', '$2y$10$bVaB2iqfMDuyHBGAEanMiuP/xQms3EsrPksfbNJbWF5PUyHKMAQHe', 'rrr@gmail.com', '', 'rrr', '', '', '0000-00-00 00:00:00', '2022-04-30 15:35:39'),
(4, '', '$2y$10$3TFhL9DyO/OxFDYjJuvhnuyhcC1UqmDoCOlGtyqas7RLt.akLkBzy', 'ddd@ss.c', '', 'dd', '', '', '0000-00-00 00:00:00', '2022-04-30 15:36:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catalogs`
--
ALTER TABLE `catalogs`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pro_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catalogs`
--
ALTER TABLE `catalogs`
  MODIFY `cat_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pro_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
