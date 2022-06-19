-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2022 at 06:02 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `coffe_machines`
--

CREATE TABLE `coffe_machines` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `turned` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coffe_machines`
--

INSERT INTO `coffe_machines` (`id`, `name`, `turned`) VALUES
(1, 'Super Hiper Coffee Machine', 1);

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `coffee_status` varchar(3) NOT NULL,
  `milk_status` varchar(3) NOT NULL,
  `water_status` varchar(3) NOT NULL,
  `coffee_power` int(2) NOT NULL,
  `coffee_machine_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `coffee_status`, `milk_status`, `water_status`, `coffee_power`, `coffee_machine_id`) VALUES
(1, '100', '100', '100', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `specification`
--

CREATE TABLE `specification` (
  `ac_power` varchar(255) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `water_boiler` varchar(255) NOT NULL,
  `steam_boiler` varchar(255) NOT NULL,
  `max_consumption` varchar(255) NOT NULL,
  `coffee_machine_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `specification`
--

INSERT INTO `specification` (`ac_power`, `manufacturer`, `water_boiler`, `steam_boiler`, `max_consumption`, `coffee_machine_id`) VALUES
('AC 220~240V', 'TEST TEST 😎', '1100 Watts', '700 Watts', '1100 Watts', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coffe_machines`
--
ALTER TABLE `coffe_machines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coffee_machine_id` (`coffee_machine_id`);

--
-- Indexes for table `specification`
--
ALTER TABLE `specification`
  ADD PRIMARY KEY (`ac_power`),
  ADD UNIQUE KEY `coffe_machine_id` (`coffee_machine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coffe_machines`
--
ALTER TABLE `coffe_machines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
