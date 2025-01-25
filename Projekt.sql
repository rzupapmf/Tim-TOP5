-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 19, 2025 at 02:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Projekt`
--

-- --------------------------------------------------------

--
-- Table structure for table `Kategorije`
--

CREATE TABLE `Kategorije` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `budget` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_generic` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Kategorije`
--

INSERT INTO `Kategorije` (`id`, `user_id`, `name`, `budget`, `is_generic`) VALUES
(4, 7, 'Hrana', 500.00, 1),
(5, 7, 'Stanarina', 300.00, 1),
(6, 7, 'Režije', 200.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Korisnici`
--

CREATE TABLE `Korisnici` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Korisnici`
--

INSERT INTO `Korisnici` (`id`, `username`, `password`) VALUES
(3, 'Test1', '$2y$10$5bVaBpzxSMKzICtDpeoou.Qx8MhxBV4yQhgzIZjR7B44Gc6SUAqCG'),
(4, 'Test12', '$2y$10$o.fLoT3IKCfrHo2KRYi1fe.SoaCfQH.babJko.zrFxqaKqTvHpzNW'),
(5, 'Test123', '$2y$10$OeD.Jznv6eA5G3Z2PVqub.sHrJsIvp9urpV8PuhfFetVF9ki/LA6W'),
(6, '4Test123', '$2y$10$k.p31PmKJqzgZrVR60B8SeMuhaMNDgUIPKPE5KVxV7fcjqGHQ.mWC'),
(7, '4Test1232', '$2y$10$VIPQYLhysuUGwPz3emZN4uHvSaTG5ApW2uglc9l1hmSzdGmJek0ze');

-- --------------------------------------------------------

--
-- Table structure for table `Troškovi`
--

CREATE TABLE `Troškovi` (
  `id` int(11) NOT NULL,
  `kategorije_id` int(11) DEFAULT NULL,
  `količina` decimal(10,2) DEFAULT NULL,
  `opis` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Kategorije`
--
ALTER TABLE `Kategorije`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Korisnici`
--
ALTER TABLE `Korisnici`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Troškovi`
--
ALTER TABLE `Troškovi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategorije_id` (`kategorije_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Kategorije`
--
ALTER TABLE `Kategorije`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Korisnici`
--
ALTER TABLE `Korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Troškovi`
--
ALTER TABLE `Troškovi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Kategorije`
--
ALTER TABLE `Kategorije`
  ADD CONSTRAINT `kategorije_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `korisnici` (`id`);

--
-- Constraints for table `Troškovi`
--
ALTER TABLE `Troškovi`
  ADD CONSTRAINT `troškovi_ibfk_1` FOREIGN KEY (`kategorije_id`) REFERENCES `Kategorije` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
