-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2022 at 04:32 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pro_id` int(13) NOT NULL,
  `pro_name` varchar(250) NOT NULL,
  `pro_detail` text DEFAULT NULL,
  `cat_name` varchar(250) DEFAULT NULL,
  `unit_name` varchar(250) DEFAULT NULL,
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
(7, 'user', '$2y$10$K8ZiyMr9.lBeCnwhVwAoo.Nt9Kyr/UlXZ4AWiZqLUDqAnMZajZbHu', 'd', '', 'test', '', '', '0000-00-00 00:00:00', '2022-05-04 21:26:42');

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
  MODIFY `cat_id` int(13) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pro_id` int(13) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(13) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
