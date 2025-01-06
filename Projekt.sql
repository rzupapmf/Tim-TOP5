-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 06, 2025 at 04:11 PM
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
-- Table structure for table `Korisnici`
--

CREATE TABLE `Korisnici` (
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Korisnici`
--

INSERT INTO `Korisnici` (`username`, `password`) VALUES
('Test', '$2y$10$PGj0EV1C44BYw8kmcTMdJ.QSAK5L2rZLOM6uImFc9ZjRfyekngbGK'),
('Test1', '$2y$10$WNXszHjLiBaVtUPvabd3B.FysHWNUv1cmKznR51ouvjh9s5KU4Vw2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Korisnici`
--
ALTER TABLE `Korisnici`
  ADD UNIQUE KEY `Username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
