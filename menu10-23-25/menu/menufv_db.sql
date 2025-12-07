-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 08:56 AM
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
-- Database: `menufv_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `FName` varchar(50) NOT NULL,
  `MName` varchar(50) NOT NULL,
  `LName` varchar(50) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Priority` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `FName`, `MName`, `LName`, `UserID`, `Priority`) VALUES
(1, 'asdf', 'asdf', 'asdf', 1, 0),
(2, 'Gabriel Angelo', 'Aguirre', 'Maala', 4, 0),
(3, 'priority', 'priority', 'plus', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Status` varchar(15) NOT NULL,
  `OrderPrice` int(11) NOT NULL,
  `TimeOrdered` datetime NOT NULL,
  `OrderCompleted` datetime NOT NULL,
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(50) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Priority` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `Quantity`, `Status`, `OrderPrice`, `TimeOrdered`, `OrderCompleted`, `ProductID`, `ProductName`, `UserID`, `Priority`) VALUES
(29, 1, 'Completed', 80, '2025-09-23 04:10:46', '2025-09-23 04:10:55', 3, 'Shawarma Rice', 1, 0),
(30, 2, 'Completed', 60, '2025-09-23 04:31:10', '2025-09-23 05:42:00', 1, 'Pancit Canton', 1, 0),
(31, 1, 'Completed', 80, '2025-09-23 04:31:30', '2025-09-23 05:42:08', 3, 'Shawarma Rice', 1, 0),
(32, 3, 'Completed', 195, '2025-09-23 04:31:30', '2025-09-23 05:42:10', 13, 'Ham And Cheese Sandwhich', 1, 0),
(33, 2, 'Completed', 140, '2025-09-23 04:31:59', '2025-09-23 05:42:12', 4, 'Siomai Rice', 1, 0),
(34, 5, 'Completed', 225, '2025-09-23 04:32:13', '2025-09-23 05:42:17', 2, 'Pancit Canton w Egg', 1, 0),
(35, 12, 'Completed', 240, '2025-09-23 04:32:28', '2025-09-23 05:42:19', 9, 'Buttered Cheese Corn', 1, 0),
(36, 1, 'Completed', 30, '2025-09-23 04:33:09', '2025-09-23 05:42:21', 1, 'Pancit Canton', 1, 0),
(37, 1, 'Completed', 45, '2025-09-23 04:33:09', '2025-09-23 05:42:23', 2, 'Pancit Canton w Egg', 1, 0),
(38, 1, 'Completed', 80, '2025-09-23 04:33:09', '2025-09-23 05:42:24', 3, 'Shawarma Rice', 1, 0),
(39, 1, 'Completed', 70, '2025-09-23 04:33:09', '2025-09-23 05:44:01', 4, 'Siomai Rice', 1, 0),
(40, 1, 'Completed', 20, '2025-09-23 04:33:09', '2025-09-23 05:46:30', 9, 'Buttered Cheese Corn', 1, 0),
(41, 1, 'Completed', 65, '2025-09-23 04:33:09', '2025-09-25 07:35:14', 13, 'Ham And Cheese Sandwhich', 1, 0),
(42, 3, 'Completed', 90, '2025-09-23 04:35:09', '2025-09-25 07:52:14', 1, 'Pancit Canton', 1, 0),
(43, 5, 'Cancelled', 100, '2025-09-23 04:35:09', '2025-09-28 02:48:08', 9, 'Buttered Cheese Corn', 1, 0),
(44, 15, 'Cancelled', 300, '2025-09-23 04:35:29', '2025-09-28 02:59:49', 9, 'Buttered Cheese Corn', 1, 0),
(45, 3, 'Cancelled', 240, '2025-09-23 04:35:29', '2025-09-28 02:48:38', 3, 'Shawarma Rice', 1, 0),
(46, 2, 'Completed', 90, '2025-09-23 04:42:07', '2025-09-28 03:27:31', 2, 'Pancit Canton w Egg', 1, 0),
(47, 3, 'Completed', 90, '2025-09-23 05:43:28', '2025-09-28 03:27:32', 1, 'Pancit Canton', 1, 0),
(48, 2, 'Completed', 130, '2025-09-23 05:43:28', '2025-09-28 03:27:34', 13, 'Ham And Cheese Sandwhich', 1, 0),
(49, 1, 'Completed', 20, '2025-09-23 05:43:28', '2025-09-28 03:27:36', 9, 'Buttered Cheese Corn', 1, 0),
(50, 2, 'Completed', 160, '2025-09-23 05:43:28', '2025-09-28 03:27:38', 3, 'Shawarma Rice', 1, 0),
(51, 2, 'Completed', 140, '2025-09-23 05:43:28', '2025-09-28 03:27:39', 4, 'Siomai Rice', 1, 0),
(52, 1, 'Completed', 30, '2025-09-23 05:43:42', '2025-09-28 03:27:40', 1, 'Pancit Canton', 1, 0),
(53, 1, 'Completed', 80, '2025-09-23 05:43:42', '2025-09-28 03:27:42', 3, 'Shawarma Rice', 1, 0),
(54, 1, 'Completed', 70, '2025-09-23 08:50:13', '2025-09-25 07:35:16', 4, 'Siomai Rice', 7, 1),
(55, 1, 'Completed', 30, '2025-09-25 07:34:42', '2025-09-28 03:27:43', 1, 'Pancit Canton', 1, 0),
(56, 1, 'Completed', 80, '2025-09-25 07:51:32', '2025-09-28 03:27:44', 3, 'Shawarma Rice', 1, 0),
(57, 1, 'Cancelled', 65, '2025-09-25 07:53:14', '2025-09-28 02:47:58', 13, 'Ham And Cheese Sandwhich', 7, 1),
(58, 1, 'Completed', 30, '2025-09-25 07:55:37', '2025-09-28 03:27:45', 1, 'Pancit Canton', 7, 0),
(60, 3, 'Completed', 90, '2025-09-28 01:00:08', '2025-09-28 03:27:46', 1, 'Pancit Canton', 1, 0),
(61, 1, 'Pending', 45, '2025-09-28 04:07:16', '0000-00-00 00:00:00', 2, 'Pancit Canton w Egg', 1, 0),
(62, 3, 'Pending', 240, '2025-09-29 09:40:50', '0000-00-00 00:00:00', 3, 'Shawarma Rice', 1, 0),
(63, 2, 'Pending', 90, '2025-09-29 09:41:22', '0000-00-00 00:00:00', 2, 'Pancit Canton w Egg', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(50) NOT NULL,
  `ProductDesc` varchar(100) NOT NULL,
  `Status` varchar(10) NOT NULL,
  `Price` double NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0,
  `SellerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `ProductDesc`, `Status`, `Price`, `Quantity`, `SellerID`) VALUES
(1, 'Pancit Canton', 'Lucky Me! Pancit Canton in a bowl!', 'ACTIVE', 30, 71, 1),
(2, 'Pancit Canton w Egg', 'Lucky Me! Pancit Canton with an egg in a bowl!', 'ACTIVE', 45, 64, 1),
(3, 'Shawarma Rice', 'Shawarma with Rice in a bowl!', 'ACTIVE', 80, 47, 1),
(4, 'Siomai Rice', 'Siomai Rice in a bowl! Test', 'ACTIVE', 70, 0, 1),
(9, 'Buttered Cheese Corn', 'Buttered Cheese Corn in a cup', 'ACTIVE', 20, 30, 1),
(13, 'Ham And Cheese Sandwhich', 'sammich', 'ACTIVE', 65, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `SellerID` int(11) NOT NULL,
  `StoreName` varchar(50) NOT NULL,
  `Tin` varchar(50) NOT NULL,
  `Permit` varchar(50) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`SellerID`, `StoreName`, `Tin`, `Permit`, `UserID`) VALUES
(1, 'hjkl', 'hjkl', 'hjkl', 2),
(2, 'happy', 'happy', 'happy', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Type` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `Type`) VALUES
(1, 'asdf', 'asdf@asdf', 'asdf', 'Customer'),
(2, 'hjkl', 'hjkl@hjkl', 'hjkl', 'Seller'),
(3, 'happy', 'happy@admin', 'pass', 'Seller'),
(4, 'Gabriel Angelo', 'gabrielangelomaala@gmail.com', 'Tekashinosatoru15', 'Customer'),
(5, 'admin', 'admin@menu', 'admin123', 'Admin'),
(6, 'owneraccount', 'owner@menu', 'owner123', 'Owner'),
(7, 'priority', 'prio@rity', 'special', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `SellerID` (`SellerID`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`SellerID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `SellerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`SellerID`) REFERENCES `sellers` (`SellerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sellers`
--
ALTER TABLE `sellers`
  ADD CONSTRAINT `sellers_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
