-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 04:33 AM
-- Server version: 8.0.43
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `get_inventory_status` (`qty` INT) RETURNS VARCHAR(20) CHARSET utf8mb4 DETERMINISTIC RETURN (
    CASE 
        WHEN qty > 10 THEN 'IN STOCK'
        WHEN qty > 0 THEN 'LOW STOCK'
        ELSE 'OUT OF STOCK'
    END
)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `AnalyticsID` int NOT NULL,
  `Forecast_ID` int NOT NULL,
  `Report_Date` date NOT NULL,
  `SalesMetrics` int NOT NULL,
  `Account_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `Customer_ID` int NOT NULL,
  `CustomerName` varchar(100) NOT NULL,
  `Location` varchar(250) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`Customer_ID`, `CustomerName`, `Location`, `Email`, `PhoneNumber`) VALUES
(3, 'Arnold', 'Bulacan', 'arnold.arnold@gmail.com', 938274753),
(4, 'Peter', 'Caloocan', 'Peter_pete@gmail.com', 903850356),
(5, 'Mike', 'Valenzuela', 'Mike.Val@gmail.com', 903850356),
(6, 'Jake', 'Navotas', 'Jake24@gmail.com', 983271642),
(7, 'Gilbert', 'Sangandaan', 'Gilbert000@gmail.com', 982713245),
(8, 'Angelo', 'Caloocan', 'angelo35@gmail.com', 938275621);

-- --------------------------------------------------------

--
-- Table structure for table `customersreturns`
--

CREATE TABLE `customersreturns` (
  `CReturn_ID` int NOT NULL,
  `ReferenceNo` varchar(100) NOT NULL,
  `Quantity` int NOT NULL,
  `ReturnedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ReasonForReturn` enum('Wrong-Item','Damaged','Not-Match','Faulty','Missing-Parts','Not-Fit','Accidental','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Customer_ID` int DEFAULT NULL,
  `Product_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customersreturns`
--

INSERT INTO `customersreturns` (`CReturn_ID`, `ReferenceNo`, `Quantity`, `ReturnedDate`, `ReasonForReturn`, `Customer_ID`, `Product_ID`) VALUES
(21, '20250900001', 1, '2025-10-17 05:16:33', 'Wrong-Item', 4, 2),
(22, '20250900001', 1, '2025-10-17 05:16:45', 'Damaged', 4, 4),
(23, '20250900001', 1, '2025-10-17 05:16:55', 'Not-Match', 4, 3),
(24, '20250900001', 1, '2025-10-17 05:17:07', 'Faulty', 4, 4),
(25, '20250900001', 1, '2025-10-17 05:17:17', 'Missing-Parts', 6, 2),
(26, '20250900002', 1, '2025-10-17 05:17:31', 'Not-Fit', 4, 7),
(27, '20250900001', 1, '2025-10-17 05:17:42', 'Accidental', 6, 4),
(28, '20250900001', 1, '2025-10-17 05:17:51', 'Other', 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `daily_sales`
--

CREATE TABLE `daily_sales` (
  `DailySales_ID` int NOT NULL,
  `PeriodStart` date DEFAULT NULL,
  `PeriodEnd` date DEFAULT NULL,
  `Product_ID` int DEFAULT NULL,
  `TotalSales` decimal(10,2) DEFAULT NULL,
  `TotalQuantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `daily_sales`
--

INSERT INTO `daily_sales` (`DailySales_ID`, `PeriodStart`, `PeriodEnd`, `Product_ID`, `TotalSales`, `TotalQuantity`) VALUES
(64, '2025-10-04', '2025-10-04', 1, 5000.00, 2),
(65, '2025-10-04', '2025-10-04', 2, 2000.00, 2),
(66, '2025-10-04', '2025-10-04', 3, 100000.00, 10),
(67, '2025-10-04', '2025-10-04', 4, 12000.00, 12),
(68, '2025-10-04', '2025-10-04', 5, 25000.00, 5),
(70, '2025-10-03', '2025-10-03', 2, 2000.00, 2),
(71, '2025-10-02', '2025-10-02', 2, 2000.00, 2),
(72, '2025-10-03', '2025-10-03', 3, 120000.00, 12),
(73, '2025-10-02', '2025-10-02', 3, 50000.00, 5),
(74, '2025-10-01', '2025-10-01', 2, 110000.00, 110),
(75, '2025-10-07', '2025-10-07', 2, 4000.00, 4),
(77, '2025-10-07', '2025-10-07', 1, 27500.00, 11),
(81, '2025-10-07', '2025-10-07', 9, 49000.00, 49),
(88, '2025-10-07', '2025-10-07', 12, 11000.00, 11),
(90, '2025-10-07', '2025-10-07', 11, 285000.00, 25),
(91, '2025-10-08', '2025-10-08', 1, 50000.00, 20),
(93, '2025-10-08', '2025-10-08', 12, 4000.00, 4),
(96, '2025-10-12', '2025-10-12', 13, 2000.00, 2),
(101, '2025-10-14', '2025-10-14', 13, 6000.00, 6),
(104, '2025-10-14', '2025-10-14', 14, 8000.00, 10),
(105, '2025-10-14', '2025-10-14', 15, 191881.00, 19),
(112, '2025-10-14', '2025-10-14', 16, 60012.00, 6),
(114, '2025-10-14', '2025-10-14', 17, 450009.00, 9),
(115, '2025-10-14', '2025-10-14', 18, 4000.00, 40),
(120, '2025-10-15', '2025-10-15', 16, 60012.00, 6),
(121, '2025-10-15', '2025-10-15', 14, 8000.00, 10),
(128, '2025-10-15', '2025-10-15', 18, 1200.00, 12),
(129, '2025-10-15', '2025-10-15', 17, 450009.00, 9),
(130, '2025-10-16', '2025-10-16', 17, 100002.00, 2),
(131, '2025-10-16', '2025-10-16', 16, 70014.00, 7),
(132, '2025-10-16', '2025-10-16', 18, 700.00, 7),
(134, '2025-10-17', '2025-10-17', 18, 600.00, 6),
(137, '2025-10-17', '2025-10-17', 16, 10002.00, 1),
(139, '2025-10-17', '2025-10-17', 20, 10000.00, 5),
(140, '2025-10-18', '2025-10-18', 12, 10000.00, 10),
(141, '2025-10-18', '2025-10-18', 15, 10099.00, 1),
(142, '2025-10-18', '2025-10-18', 20, 20000.00, 10),
(143, '2025-10-18', '2025-10-18', 9, 10000.00, 10),
(144, '2025-10-18', '2025-10-18', 11, 171000.00, 15);

-- --------------------------------------------------------

--
-- Stand-in structure for view `daily_sales_summary`
-- (See below for the actual view)
--
CREATE TABLE `daily_sales_summary` (
`Product_ID` int
,`ProductName` varchar(100)
,`SalesDate` timestamp
,`TotalQuantity` decimal(32,0)
,`TotalRevenue` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `daily_total_sales`
--

CREATE TABLE `daily_total_sales` (
  `EntireDailySales_ID` int NOT NULL,
  `PeriodStart` date NOT NULL,
  `PeriodEnd` date NOT NULL,
  `TotalSales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TotalQuantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `daily_total_sales`
--

INSERT INTO `daily_total_sales` (`EntireDailySales_ID`, `PeriodStart`, `PeriodEnd`, `TotalSales`, `TotalQuantity`) VALUES
(7, '2025-10-04', '2025-10-04', 144000.00, 31),
(13, '2025-10-03', '2025-10-03', 122000.00, 14),
(14, '2025-10-02', '2025-10-02', 52000.00, 7),
(17, '2025-10-01', '2025-10-01', 110000.00, 110),
(18, '2025-10-07', '2025-10-07', 376500.00, 100),
(34, '2025-10-08', '2025-10-08', 54000.00, 24),
(39, '2025-10-12', '2025-10-12', 2000.00, 2),
(44, '2025-10-14', '2025-10-14', 719902.00, 90),
(63, '2025-10-15', '2025-10-15', 519221.00, 37),
(73, '2025-10-16', '2025-10-16', 170716.00, 16),
(77, '2025-10-17', '2025-10-17', 20602.00, 12),
(83, '2025-10-18', '2025-10-18', 221099.00, 46);

-- --------------------------------------------------------

--
-- Table structure for table `expiration`
--

CREATE TABLE `expiration` (
  `Expiration_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `BatchNum` varchar(50) NOT NULL,
  `ExpirationDate` date NOT NULL,
  `Quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expiration`
--

INSERT INTO `expiration` (`Expiration_ID`, `Product_ID`, `BatchNum`, `ExpirationDate`, `Quantity`) VALUES
(1, 15, 'BATCH-15-001', '2027-10-14', -9),
(3, 17, 'BATCH-17-001', '2025-10-18', 2),
(4, 18, 'BATCH-18-001', '2025-10-18', 0),
(5, 18, 'BATCH-18-002', '2025-10-25', -10),
(6, 18, 'BATCH-18-003', '2025-10-28', 0),
(7, 18, 'BATCH-18-004', '2025-10-27', 1),
(8, 18, 'BATCH-18-005', '2025-11-19', 1),
(9, 12, 'BATCH-12-001', '2026-10-18', 20),
(10, 12, 'BATCH-12-002', '2026-06-18', 10);

-- --------------------------------------------------------

--
-- Table structure for table `forecast`
--

CREATE TABLE `forecast` (
  `Forecast_ID` int NOT NULL,
  `ForecastType` text NOT NULL,
  `Product_ID` int DEFAULT NULL,
  `ForecastPeriod` text NOT NULL,
  `ForecastStart` date NOT NULL,
  `ForecastEnd` date NOT NULL,
  `ProjectedSales` int NOT NULL,
  `ConfidenceLevel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `forecast`
--

INSERT INTO `forecast` (`Forecast_ID`, `ForecastType`, `Product_ID`, `ForecastPeriod`, `ForecastStart`, `ForecastEnd`, `ProjectedSales`, `ConfidenceLevel`) VALUES
(1, 'SMA (3 DAYS)', 1, 'Daily', '2025-10-15', '2025-10-16', 11, 95),
(2, 'SMA (3 DAYS)', 2, 'Daily', '2025-10-15', '2025-10-16', 3, 95),
(3, 'SMA (3 DAYS)', 3, 'Daily', '2025-10-15', '2025-10-16', 9, 95),
(4, 'SMA (3 DAYS)', 4, 'Daily', '2025-10-15', '2025-10-16', 12, 95),
(5, 'SMA (3 DAYS)', 5, 'Daily', '2025-10-15', '2025-10-16', 5, 95),
(6, 'SMA (3 DAYS)', 6, 'Daily', '2025-10-15', '2025-10-16', 0, 95),
(7, 'SMA (3 DAYS)', 7, 'Daily', '2025-10-15', '2025-10-16', 0, 95),
(8, 'SMA (3 DAYS)', 8, 'Daily', '2025-10-15', '2025-10-16', 0, 95),
(9, 'SMA (3 DAYS)', 9, 'Daily', '2025-10-15', '2025-10-16', 49, 95),
(10, 'SMA (3 DAYS)', 11, 'Daily', '2025-10-15', '2025-10-16', 25, 95),
(11, 'SMA (3 DAYS)', 12, 'Daily', '2025-10-15', '2025-10-16', 8, 95),
(12, 'SMA (3 DAYS)', 13, 'Daily', '2025-10-15', '2025-10-16', 4, 95),
(13, 'SMA (3 DAYS)', 14, 'Daily', '2025-10-15', '2025-10-16', 10, 95),
(14, 'SMA (3 DAYS)', 15, 'Daily', '2025-10-15', '2025-10-16', 19, 95),
(15, 'SMA (3 DAYS)', 16, 'Daily', '2025-10-15', '2025-10-16', 6, 95),
(16, 'SMA (3 DAYS)', 17, 'Daily', '2025-10-15', '2025-10-16', 9, 95),
(17, 'SMA (3 DAYS)', 18, 'Daily', '2025-10-15', '2025-10-16', 26, 95),
(18, 'SMA (3 DAYS)', NULL, 'Daily', '2025-10-15', '2025-10-16', 43, 95),
(19, 'SMA (3 WEEKS)', 1, 'Weekly', '2025-10-15', '2025-10-22', 17, 95),
(20, 'SMA (3 WEEKS)', 2, 'Weekly', '2025-10-15', '2025-10-22', 60, 95),
(21, 'SMA (3 WEEKS)', 3, 'Weekly', '2025-10-15', '2025-10-22', 27, 95),
(22, 'SMA (3 WEEKS)', 4, 'Weekly', '2025-10-15', '2025-10-22', 12, 95),
(23, 'SMA (3 WEEKS)', 5, 'Weekly', '2025-10-15', '2025-10-22', 5, 95),
(24, 'SMA (3 WEEKS)', 6, 'Weekly', '2025-10-15', '2025-10-22', 0, 95),
(25, 'SMA (3 WEEKS)', 7, 'Weekly', '2025-10-15', '2025-10-22', 0, 95),
(26, 'SMA (3 WEEKS)', 8, 'Weekly', '2025-10-15', '2025-10-22', 0, 95),
(27, 'SMA (3 WEEKS)', 9, 'Weekly', '2025-10-15', '2025-10-22', 49, 95),
(28, 'SMA (3 WEEKS)', 11, 'Weekly', '2025-10-15', '2025-10-22', 25, 95),
(29, 'SMA (3 WEEKS)', 12, 'Weekly', '2025-10-15', '2025-10-22', 15, 95),
(30, 'SMA (3 WEEKS)', 13, 'Weekly', '2025-10-15', '2025-10-22', 8, 95),
(31, 'SMA (3 WEEKS)', 14, 'Weekly', '2025-10-15', '2025-10-22', 20, 95),
(32, 'SMA (3 WEEKS)', 15, 'Weekly', '2025-10-15', '2025-10-22', 19, 95),
(33, 'SMA (3 WEEKS)', 16, 'Weekly', '2025-10-15', '2025-10-22', 12, 95),
(34, 'SMA (3 WEEKS)', 17, 'Weekly', '2025-10-15', '2025-10-22', 18, 95),
(35, 'SMA (3 WEEKS)', 18, 'Weekly', '2025-10-15', '2025-10-22', 52, 95),
(36, 'SMA (3 WEEKS)', NULL, 'Weekly', '2025-10-15', '2025-10-22', 138, 95),
(37, 'SMA (3MONTHS)', 1, 'Monthly', '2025-10-15', '2025-11-15', 33, 95),
(38, 'SMA (3MONTHS)', 2, 'Monthly', '2025-10-15', '2025-11-15', 120, 95),
(39, 'SMA (3MONTHS)', 3, 'Monthly', '2025-10-15', '2025-11-15', 27, 95),
(40, 'SMA (3MONTHS)', 4, 'Monthly', '2025-10-15', '2025-11-15', 12, 95),
(41, 'SMA (3MONTHS)', 5, 'Monthly', '2025-10-15', '2025-11-15', 5, 95),
(42, 'SMA (3MONTHS)', 6, 'Monthly', '2025-10-15', '2025-11-15', 0, 95),
(43, 'SMA (3MONTHS)', 7, 'Monthly', '2025-10-15', '2025-11-15', 0, 95),
(44, 'SMA (3MONTHS)', 8, 'Monthly', '2025-10-15', '2025-11-15', 0, 95),
(45, 'SMA (3MONTHS)', 9, 'Monthly', '2025-10-15', '2025-11-15', 49, 95),
(46, 'SMA (3MONTHS)', 11, 'Monthly', '2025-10-15', '2025-11-15', 25, 95),
(47, 'SMA (3MONTHS)', 12, 'Monthly', '2025-10-15', '2025-11-15', 15, 95),
(48, 'SMA (3MONTHS)', 13, 'Monthly', '2025-10-15', '2025-11-15', 8, 95),
(49, 'SMA (3MONTHS)', 14, 'Monthly', '2025-10-15', '2025-11-15', 20, 95),
(50, 'SMA (3MONTHS)', 15, 'Monthly', '2025-10-15', '2025-11-15', 19, 95),
(51, 'SMA (3MONTHS)', 16, 'Monthly', '2025-10-15', '2025-11-15', 12, 95),
(52, 'SMA (3MONTHS)', 17, 'Monthly', '2025-10-15', '2025-11-15', 18, 95),
(53, 'SMA (3MONTHS)', 18, 'Monthly', '2025-10-15', '2025-11-15', 52, 95),
(54, 'SMA (MONTHS)', NULL, 'Monthly', '2025-10-15', '2025-11-15', 415, 95);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `Product_ID` int NOT NULL,
  `LocationS` varchar(100) NOT NULL,
  `LocationR` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Inventory` int DEFAULT NULL,
  `UnitIN` datetime DEFAULT NULL,
  `UnitOut` timestamp NULL DEFAULT NULL,
  `Status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ExpirationDate` date DEFAULT NULL,
  `Barcode` varchar(100) NOT NULL,
  `Supplier_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`Product_ID`, `LocationS`, `LocationR`, `Price`, `Inventory`, `UnitIN`, `UnitOut`, `Status`, `ExpirationDate`, `Barcode`, `Supplier_ID`) VALUES
(2, 'Shelf A', 'Row A', 1000.00, 23, '2025-10-17 22:20:49', '2025-10-07 03:43:15', 'IN-STOCK', '2025-09-20', '11111', 1),
(3, 'Shelf A', 'Row A', 10000.00, 11, '2025-10-17 22:20:29', NULL, 'IN-STOCK', NULL, '11111', 2),
(9, 'Shelf A', 'Row A', 1000.00, 1, '2025-10-15 13:27:42', '2025-10-16 17:33:00', 'LOW-STOCK', NULL, '20250900006', 1),
(11, 'Shelf A', 'Row A', 11400.00, 1, '2025-10-17 16:23:41', '2025-10-16 17:40:00', 'LOW-STOCK', NULL, '20250900006', 1),
(12, 'Shelf A', 'Row A', 1000.00, 30, '2025-10-18 00:30:42', '2025-10-17 17:00:42', 'IN-STOCK', '2026-06-18', '20250900006', 1),
(13, 'Shelf A', 'Row A', 1000.00, 2, '2025-10-11 00:13:00', '2025-10-14 02:46:50', 'LOW-STOCK', NULL, '20250900006', 1),
(14, 'Shelf A', 'Row A', 800.00, 0, '2025-10-14 10:49:00', '2025-10-15 06:37:30', 'OUT-OF-STOCK', NULL, '20250900006', 1),
(15, 'Shelf C', 'Row D', 10099.00, 2, '2025-10-14 13:37:00', '2025-10-16 17:31:00', 'LOW-STOCK', '2027-10-14', '20250900006', 2),
(17, 'Shelf A', 'Row A', 50001.00, -7, '2025-10-17 22:19:56', '2025-10-16 03:56:17', 'OUT-OF-STOCK', '2025-10-18', '20250900006', 2),
(18, 'Shelf A', 'Row A', 100.00, 37, '2025-10-17 22:18:59', '2025-10-17 04:39:18', 'IN-STOCK', '2025-10-18', '20250900006', 1),
(19, 'Shelf A', 'Row A', 1200.00, NULL, '2025-10-17 16:53:49', NULL, 'OUT-OF-STOCK', NULL, '20250900006', 2),
(20, 'Shelf A', 'Row A', 2000.00, 0, '2025-10-17 16:55:02', '2025-10-16 17:33:00', 'OUT-OF-STOCK', NULL, '20250900006', 1);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_sales`
--

CREATE TABLE `monthly_sales` (
  `MonthlySales_ID` int NOT NULL,
  `PeriodStart` date DEFAULT NULL,
  `PeriodEnd` date DEFAULT NULL,
  `Product_ID` int DEFAULT NULL,
  `TotalSales` decimal(10,2) DEFAULT NULL,
  `TotalQuantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `monthly_sales`
--

INSERT INTO `monthly_sales` (`MonthlySales_ID`, `PeriodStart`, `PeriodEnd`, `Product_ID`, `TotalSales`, `TotalQuantity`) VALUES
(64, '2025-10-01', '2025-10-31', 1, 82500.00, 33),
(65, '2025-10-01', '2025-10-31', 2, 120000.00, 120),
(66, '2025-10-01', '2025-10-31', 3, 270000.00, 27),
(67, '2025-10-01', '2025-10-31', 4, 12000.00, 12),
(68, '2025-10-01', '2025-10-31', 5, 25000.00, 5),
(81, '2025-10-01', '2025-10-31', 9, 59000.00, 59),
(88, '2025-10-01', '2025-10-31', 12, 25000.00, 25),
(90, '2025-10-01', '2025-10-31', 11, 456000.00, 40),
(96, '2025-10-01', '2025-10-31', 13, 8000.00, 8),
(104, '2025-10-01', '2025-10-31', 14, 16000.00, 20),
(105, '2025-10-01', '2025-10-31', 15, 201980.00, 20),
(112, '2025-10-01', '2025-10-31', 16, 200040.00, 20),
(114, '2025-10-01', '2025-10-31', 17, 1000020.00, 20),
(115, '2025-10-01', '2025-10-31', 18, 6500.00, 65),
(139, '2025-10-01', '2025-10-31', 20, 30000.00, 15);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_total_sales`
--

CREATE TABLE `monthly_total_sales` (
  `EntireMonthlySales_ID` int NOT NULL,
  `PeriodStart` date NOT NULL,
  `PeriodEnd` date NOT NULL,
  `TotalSales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TotalQuantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `monthly_total_sales`
--

INSERT INTO `monthly_total_sales` (`EntireMonthlySales_ID`, `PeriodStart`, `PeriodEnd`, `TotalSales`, `TotalQuantity`) VALUES
(6, '2025-10-01', '2025-10-31', 2507040.00, 487);

-- --------------------------------------------------------

--
-- Table structure for table `newaddition`
--

CREATE TABLE `newaddition` (
  `Inventory_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Date_Added` timestamp NOT NULL,
  `Expiration_Date` date DEFAULT NULL,
  `Status` enum('New','Returned') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Supplier_ID` int NOT NULL,
  `LocationS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `LocationR` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `newaddition`
--

INSERT INTO `newaddition` (`Inventory_ID`, `Product_ID`, `Quantity`, `Date_Added`, `Expiration_Date`, `Status`, `Supplier_ID`, `LocationS`, `LocationR`) VALUES
(10, 2, 10, '2025-10-04 01:50:00', NULL, 'Returned', 2, 'Shelf A', 'Row A'),
(11, 2, 222, '2025-10-07 03:34:00', NULL, 'New', 2, 'Shelf A', 'Row A'),
(12, 3, 222, '2025-10-07 03:35:00', NULL, 'New', 2, 'Shelf A', 'Row A'),
(13, 9, 11, '2025-10-07 04:31:00', NULL, 'New', 1, 'Shelf A', 'Row A');

--
-- Triggers `newaddition`
--
DELIMITER $$
CREATE TRIGGER `after_newaddition_insert` AFTER INSERT ON `newaddition` FOR EACH ROW BEGIN
    DECLARE v_Price DECIMAL(10,2);
    DECLARE v_Supplier_ID VARCHAR(50);
    DECLARE v_ExpirationDate DATE;
    DECLARE v_Barcode VARCHAR(100);
    DECLARE v_NewInventory INT;
    DECLARE v_Status VARCHAR(20);

    -- Get values from product table
    SELECT StorePrice, Supplier_ID, ExpirationDate, Barcode
    INTO v_Price, v_Supplier_ID, v_ExpirationDate, v_Barcode
    FROM product
    WHERE Product_ID = NEW.Product_ID;

    -- Check if product already exists in inventory at the same location
    IF EXISTS (
        SELECT 1 FROM inventory 
        WHERE Product_ID = NEW.Product_ID
          AND LocationS = NEW.LocationS
          AND LocationR = NEW.LocationR
    ) THEN
        -- Get current inventory
        SELECT Inventory INTO v_NewInventory
        FROM inventory
        WHERE Product_ID = NEW.Product_ID
          AND LocationS = NEW.LocationS
          AND LocationR = NEW.LocationR;

        -- Add the incoming quantity
        SET v_NewInventory = v_NewInventory + NEW.Quantity;

        -- Set the new status
        SET v_Status = CASE 
            WHEN v_NewInventory > 10 THEN 'IN-STOCK'
            WHEN v_NewInventory > 0 THEN 'LOW-STOCK'
            ELSE 'OUT-OF-STOCK'
        END;

        -- ✅ Update existing inventory EXCLUDING Supplier_ID
        UPDATE inventory
        SET 
            Inventory = v_NewInventory,
            UnitIN = NEW.Date_Added,
            Status = v_Status,
            ExpirationDate = v_ExpirationDate,
            Price = v_Price,
            Barcode = v_Barcode
        WHERE Product_ID = NEW.Product_ID
          AND LocationS = NEW.LocationS
          AND LocationR = NEW.LocationR;

    ELSE
        -- New inventory entry
        SET v_NewInventory = NEW.Quantity;

        -- Set the new status
        SET v_Status = CASE 
            WHEN v_NewInventory > 10 THEN 'IN-STOCK'
            WHEN v_NewInventory > 0 THEN 'LOW-STOCK'
            ELSE 'OUT-OF-STOCK'
        END;

        -- ✅ Insert all values (including Supplier_ID for new records)
        INSERT INTO inventory (
            Product_ID,
            LocationS,
            LocationR,
            Price,
            Inventory,
            UnitIN,
            UnitOut,
            Status,
            Supplier_ID,
            ExpirationDate,
            Barcode
        ) VALUES (
            NEW.Product_ID,
            NEW.LocationS,
            NEW.LocationR,
            v_Price,
            v_NewInventory,
            NEW.Date_Added,
            NULL,
            v_Status,
            v_Supplier_ID,
            v_ExpirationDate,
            v_Barcode
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_ID` int NOT NULL,
  `ProductName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Type` enum('Exhaust','Tires','Brakes','Stand','Forks','Rims','Mirror','Suspension','Box','Oil') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ReordingPoints` int DEFAULT NULL,
  `UnitsOrdered` int DEFAULT NULL,
  `UnitSold` int DEFAULT NULL,
  `StorePrice` int NOT NULL,
  `SupplierPrice` int NOT NULL,
  `Image` blob,
  `Supplier_ID` int NOT NULL,
  `ExpirationDate` date DEFAULT NULL,
  `Barcode` varchar(100) NOT NULL,
  `LocationS` text NOT NULL,
  `LocationR` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `ProductName`, `Type`, `ReordingPoints`, `UnitsOrdered`, `UnitSold`, `StorePrice`, `SupplierPrice`, `Image`, `Supplier_ID`, `ExpirationDate`, `Barcode`, `LocationS`, `LocationR`) VALUES
(9, 'nmax 155 left or right motorcycle Brake Maste', 'Brakes', NULL, 58, 57, 1000, 10, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(11, 'Russi Exhaust 302', 'Exhaust', NULL, 41, 40, 11400, 10000, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(12, 'Ps16 Dual Brake Master Original Ps16 X Ps13 Brake Master Assembly With Big Tank ', 'Brakes', NULL, 55, 25, 1000, 100, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(13, 'LEO RAPTOR TIRE 4.10-18, 2.75-21/ 2.75 x 18/ 2.50 TIRES BRAND NEW DUAL SPORTS TYPE ALL TERRAIN TIRES', 'Tires', NULL, 10, 8, 1000, 100, 0x363866323532636364656261645f696d616765732e6a666966, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(14, 'MICHELIN City Grip 2 Bias Reinf City Grip 2 Tyre TL Front/Rear MICHELIN', 'Tires', NULL, 20, 20, 800, 500, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(15, 'Nmax Tires', 'Tires', NULL, 25, 25, 10099, 5, NULL, 2, NULL, '20250900006', 'Shelf C', 'Row D'),
(17, 'Russi Forks', 'Forks', NULL, 13, 20, 50001, 50, NULL, 2, NULL, '20250900006', 'Shelf A', 'Row A'),
(18, 'TRC Racing Honda XRM 110 Power Pipe', 'Exhaust', NULL, 102, 65, 100, 50, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(20, 'Russi Forks', 'Forks', NULL, 15, 15, 2000, 200, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A');

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `delete_product` BEFORE DELETE ON `product` FOR EACH ROW BEGIN
    DELETE FROM inventory
    WHERE Product_ID = OLD.Product_ID;
    
    DELETE FROM expiration
    WHERE Product_ID = OLD.Product_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `new_product_to_invetory` AFTER INSERT ON `product` FOR EACH ROW BEGIN
    DECLARE v_Price DECIMAL(10,2);
    DECLARE v_Supplier_ID VARCHAR(50);
    DECLARE v_ExpirationDate DATE;
    DECLARE v_Barcode VARCHAR(100);
    DECLARE v_NewInventory INT;
    DECLARE v_Status VARCHAR(20);
    DECLARE v_NewRestock DATE;

    
    
    
    
    
    
    
    
    
    
    

    -- Get product details
    SELECT StorePrice, Supplier_ID, ExpirationDate, Barcode
    INTO v_Price, v_Supplier_ID, v_ExpirationDate, v_Barcode
    FROM product
    WHERE Product_ID = NEW.Product_ID;

    -- Compute new inventory
    SET v_NewInventory = NEW.UnitsOrdered - NEW.UnitSold;

    -- Determine stock status
    SET v_Status = CASE 
        WHEN v_NewInventory > 10 THEN 'IN-STOCK'
        WHEN v_NewInventory > 0 THEN 'LOW-STOCK'
        ELSE 'OUT-OF-STOCK'
    END;

    -- Get most recent restock date
    SELECT Date_Received INTO v_NewRestock
    FROM restock 
    WHERE Product_ID = NEW.Product_ID
    ORDER BY Date_Received DESC
    LIMIT 1;

    -- Check if inventory exists
    IF EXISTS (
        SELECT 1 FROM inventory
        WHERE Product_ID = NEW.Product_ID
          AND LocationS = NEW.LocationS
          AND LocationR = NEW.LocationR
    ) THEN
        UPDATE inventory
        SET 
            Inventory = v_NewInventory,
            UnitIN = v_NewRestock,
            Status = v_Status,
            ExpirationDate = v_ExpirationDate,
            Price = v_Price,
            Barcode = v_Barcode
        WHERE Product_ID = NEW.Product_ID
          AND LocationS = NEW.LocationS
          AND LocationR = NEW.LocationR;
    ELSE
        INSERT INTO inventory (
            Product_ID,
            LocationS,
            LocationR,
            Price,
            Inventory,
            UnitIN,
            UnitOut,
            Status,
            Supplier_ID,
            ExpirationDate,
            Barcode
        ) VALUES (
            NEW.Product_ID,
            NEW.LocationS,
            NEW.LocationR,
            v_Price,
            v_NewInventory,
            v_NewRestock,
            NULL,
            v_Status,
            v_Supplier_ID,
            v_ExpirationDate,
            v_Barcode
        );
    END IF;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pulledoutitems`
--

CREATE TABLE `pulledoutitems` (
  `Pulled_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Reason` text NOT NULL,
  `PulledDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restock`
--

CREATE TABLE `restock` (
  `Orestock_ID` int NOT NULL,
  `Type` enum('New','Re-Order') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Quantity` int DEFAULT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Product_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Status` enum('Requested','Out-for-Delivery','Cancelled','Received') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Image` blob,
  `DeliveryStatus` enum('','On-Time','Delayed','Early') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Date_Received` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `TotalReceived` int DEFAULT NULL,
  `withIssue` int DEFAULT '0',
  `ExpirationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `restock`
--

INSERT INTO `restock` (`Orestock_ID`, `Type`, `Quantity`, `OrderDate`, `Product_ID`, `Supplier_ID`, `Status`, `Image`, `DeliveryStatus`, `Date_Received`, `TotalReceived`, `withIssue`, `ExpirationDate`) VALUES
(10, 'New', 10, '2025-10-07 02:09:00', 2, 2, 'Received', '', '', '2025-10-07 05:00:45', 0, 0, NULL),
(11, 'New', 10, '2025-10-07 02:13:00', 1, 1, 'Received', '', '', '2025-10-07 05:00:45', 10, 0, NULL),
(12, 'New', 10, '2025-10-07 02:22:00', 1, 1, 'Received', '', '', '2025-10-07 05:00:45', 10, 0, NULL),
(13, 'Re-Order', 12, '2025-10-07 04:01:04', 4, 1, 'Received', '', '', '2025-10-07 05:00:45', 12, 0, NULL),
(14, 'New', 12, '2025-10-07 04:02:50', 2, 2, 'Received', '', '', '2025-10-07 05:00:45', 12, 0, NULL),
(15, 'New', 12, '2025-10-07 04:11:43', 9, 1, 'Received', '', '', '2025-10-07 05:00:45', 12, 0, NULL),
(16, 'Re-Order', 10, '2025-10-07 05:08:07', 9, 2, 'Received', '', '', '2025-10-07 05:26:00', 10, 0, NULL),
(17, 'Re-Order', 10, '2025-10-07 05:18:52', 2, 2, 'Received', '', NULL, NULL, 0, 0, NULL),
(18, 'New', 12, '2025-10-07 05:21:46', 5, 1, 'Received', '', 'On-Time', '2025-10-07 05:25:00', 12, 0, NULL),
(19, 'Re-Order', 12, '2025-10-07 05:28:25', 9, 2, 'Received', '', 'On-Time', '2025-10-07 05:28:00', 12, 0, NULL),
(20, 'New', 1, '2025-10-07 05:29:27', 9, 2, 'Received', '', 'Early', '2025-10-07 05:30:00', 1, 0, NULL),
(21, 'Re-Order', 12, '2025-10-07 05:43:32', 9, 1, 'Received', '', 'Early', '2025-10-07 05:44:00', 12, 0, NULL),
(22, 'New', 15, '2025-10-07 06:09:13', 11, 1, 'Received', '', 'On-Time', '2025-10-07 06:10:00', 15, 0, NULL),
(23, 'Re-Order', 10, '2025-10-07 06:17:49', 11, 1, 'Received', '', 'On-Time', '2025-10-07 06:18:00', 10, 0, NULL),
(24, 'New', 15, '2025-10-07 06:20:37', 12, 1, 'Received', '', 'On-Time', '2025-10-07 06:20:00', 15, 0, NULL),
(25, 'New', 10, '2025-10-10 16:13:29', 13, 1, 'Received', '', 'On-Time', '2025-10-10 16:13:00', 10, 0, NULL),
(26, 'New', 20, '2025-10-14 02:49:10', 14, 2, 'Received', '', 'On-Time', '2025-10-14 02:49:00', 20, 0, NULL),
(27, NULL, 20, '2025-10-14 05:31:48', 15, 3, 'Received', '', 'Delayed', '2025-10-14 05:32:00', 20, 0, NULL),
(28, NULL, 5, '2025-10-14 05:37:31', 15, 2, 'Received', '', 'Delayed', '2025-10-14 05:37:00', 5, 0, NULL),
(29, NULL, 20, '2025-10-14 05:49:26', 16, 2, 'Received', '', 'Early', '2025-10-14 05:49:00', 20, 0, NULL),
(30, NULL, 20, '2025-10-14 05:57:18', 17, 3, 'Received', '', 'On-Time', '2025-10-14 05:57:00', 20, 0, NULL),
(31, NULL, 20, '2025-10-14 07:32:46', 18, 2, 'Received', '', 'On-Time', '2025-10-14 07:32:46', 20, 0, '2025-10-18'),
(32, NULL, 10, '2025-10-14 07:33:42', 18, 3, 'Received', '', 'Delayed', '2025-10-14 07:33:42', 10, 0, '2025-10-25'),
(33, NULL, 20, '2025-10-14 07:42:28', 18, 1, 'Received', '', 'Delayed', '2025-10-14 07:42:28', 20, 0, '2025-10-28'),
(34, NULL, 5, '2025-10-14 07:44:12', 18, 2, 'Received', '', 'On-Time', '2025-10-14 07:44:12', 12, 0, '2025-10-18'),
(35, NULL, 20, '2025-10-14 08:24:23', 18, 3, 'Received', '', 'Delayed', '2025-10-14 08:24:23', 20, 0, '2025-10-27'),
(36, NULL, 20, '2025-10-14 08:45:26', 18, 1, 'Received', '', 'Delayed', '2025-10-14 08:45:26', 20, 0, '2025-11-19'),
(37, NULL, 10, '2025-10-15 04:33:15', 18, 3, 'Received', NULL, 'Delayed', '2025-10-15 04:33:15', 11, 0, NULL),
(38, NULL, 20, '2025-10-15 05:11:55', 17, 3, 'Received', NULL, 'On-Time', '2025-10-15 05:11:55', 2222222, 0, NULL),
(39, NULL, 20, '2025-10-15 05:14:59', 17, 1, 'Received', NULL, 'Delayed', '2025-10-15 05:14:59', 21, 0, NULL),
(40, NULL, 20, '2025-10-15 05:18:23', 17, 2, 'Received', NULL, 'Early', '2025-10-15 05:18:23', 21, 0, NULL),
(41, NULL, 10, '2025-10-15 05:27:42', 9, 3, 'Received', NULL, 'Delayed', '2025-10-15 05:27:42', 11, 1, NULL),
(42, NULL, 10, '2025-10-15 05:30:31', 17, 3, 'Received', NULL, 'Early', '2025-10-15 05:30:31', 11, 0, NULL),
(43, NULL, 1, '2025-10-15 05:41:05', 18, 3, 'Received', NULL, 'Delayed', '2025-10-15 05:41:05', 222222222, 0, NULL),
(44, NULL, 99, '2025-10-15 05:46:34', 18, 1, 'Cancelled', NULL, 'Early', '2025-10-15 05:46:34', 100, 0, NULL),
(45, NULL, 10, '2025-10-15 06:03:29', 17, 2, 'Received', NULL, 'Delayed', '2025-10-15 06:03:29', 11, 0, NULL),
(46, NULL, 10, '2025-10-15 06:05:35', 17, 1, 'Received', NULL, 'On-Time', '2025-10-15 06:05:35', 1111, 0, NULL),
(47, NULL, 12, '2025-10-15 06:10:41', 17, 3, 'Cancelled', NULL, '', '2025-10-15 06:10:41', 0, 12, NULL),
(50, NULL, 20, '2025-10-15 06:42:04', 16, 2, 'Received', NULL, 'Delayed', '2025-10-15 06:42:04', 2, 18, NULL),
(51, NULL, 10, '2025-10-16 06:20:10', 17, 3, 'Received', NULL, 'Early', '2025-10-16 06:20:10', 10, 0, NULL),
(52, NULL, 10, '2025-10-16 06:25:58', 16, 3, 'Received', NULL, 'On-Time', '2025-10-16 06:25:58', 10, 0, NULL),
(53, NULL, 20, '2025-10-17 08:23:41', 11, 2, 'Received', NULL, 'Delayed', '2025-10-17 08:23:41', 15, 5, NULL),
(54, NULL, 20, '2025-10-17 08:44:57', 19, 1, 'Received', NULL, 'Delayed', '2025-10-17 08:44:57', 10, 10, NULL),
(55, NULL, 20, '2025-10-17 08:50:53', 19, 4, 'Received', NULL, '', '2025-10-17 08:50:53', 20, 0, NULL),
(56, NULL, 20, '2025-10-17 08:52:51', 19, 5, 'Received', NULL, 'Early', '2025-10-17 08:52:51', 20, 0, NULL),
(57, NULL, 20, '2025-10-17 08:53:49', 19, 3, 'Received', NULL, 'On-Time', '2025-10-17 08:53:49', 20, 0, NULL),
(58, NULL, 20, '2025-10-17 08:55:02', 20, 4, 'Received', NULL, 'Delayed', '2025-10-17 08:55:02', 15, 5, NULL),
(59, NULL, 80, '2025-10-17 14:18:59', 18, 4, 'Received', NULL, 'Early', '2025-10-17 14:18:59', 80, 0, NULL),
(60, NULL, 1, '2025-10-17 14:19:56', 17, 2, 'Received', NULL, 'Early', '2025-10-17 14:19:56', 1, 0, NULL),
(61, NULL, 1, '2025-10-17 14:20:29', 3, 2, 'Received', NULL, 'Delayed', '2025-10-17 14:20:29', 1, 0, NULL),
(62, NULL, 1, '2025-10-17 14:20:49', 2, 2, 'Received', NULL, 'Delayed', '2025-10-17 14:20:49', 1, 0, NULL),
(63, NULL, 20, '2025-10-17 16:28:02', 12, 2, 'Received', NULL, 'On-Time', '2025-10-17 16:28:02', 20, 0, '2026-10-18'),
(64, NULL, 20, '2025-10-17 16:30:42', 12, 3, 'Received', NULL, 'Delayed', '2025-10-17 16:30:42', 20, 0, '2026-06-18');

--
-- Triggers `restock`
--
DELIMITER $$
CREATE TRIGGER `update_product_unitorder` BEFORE UPDATE ON `restock` FOR EACH ROW BEGIN
    DECLARE v_NewInventory INT;
    DECLARE new_status VARCHAR(20);
    DECLARE v_BatchNum VARCHAR(50);
    DECLARE v_BatchCount INT;
    DECLARE v_ReceivedDiff INT DEFAULT 0;

    -- Use IFNULL to avoid NULL arithmetic (NULL - anything => NULL)
    SET v_ReceivedDiff = IFNULL(NEW.TotalReceived, 0) - IFNULL(OLD.TotalReceived, 0);

    -- Only act when the record is (now) marked as Received
    IF NEW.Status = 'Received' THEN

        -- Adjust UnitsOrdered by the difference (guard against negative final value)
        UPDATE product
        SET UnitsOrdered = GREATEST(IFNULL(UnitsOrdered, 0) + v_ReceivedDiff, 0)
        WHERE Product_ID = NEW.Product_ID;

        -- Recalculate inventory from product (ensure product values are not NULL)
        SELECT IFNULL(UnitsOrdered, 0) - IFNULL(UnitSold, 0)
        INTO v_NewInventory
        FROM product
        WHERE Product_ID = NEW.Product_ID;

        -- Determine stock status
        SET new_status = CASE 
            WHEN v_NewInventory > 10 THEN 'IN-STOCK'
            WHEN v_NewInventory > 0 THEN 'LOW-STOCK'
            ELSE 'OUT-OF-STOCK'
        END;

        -- Update inventory table
        UPDATE inventory
        SET 
            UnitIN = NEW.Date_Received,
            Inventory = v_NewInventory,
            Status = new_status,
            ExpirationDate = NEW.ExpirationDate
        WHERE Product_ID = NEW.Product_ID;
    END IF;

    -- Handle expiration batches (only if an expiration date exists)
    IF NEW.ExpirationDate IS NOT NULL THEN
        SELECT COUNT(*) + 1 INTO v_BatchCount
        FROM expiration
        WHERE Product_ID = NEW.Product_ID;

        SET v_BatchNum = CONCAT('BATCH-', NEW.Product_ID, '-', LPAD(v_BatchCount, 3, '0'));

        IF EXISTS (
            SELECT 1 FROM expiration 
            WHERE Product_ID = NEW.Product_ID 
              AND ExpirationDate = NEW.ExpirationDate
        ) THEN
            -- Adjust existing expiration batch quantity; don't allow negative result
            UPDATE expiration
            SET Quantity = GREATEST(IFNULL(Quantity, 0) + v_ReceivedDiff, 0)
            WHERE Product_ID = NEW.Product_ID 
              AND ExpirationDate = NEW.ExpirationDate;
        ELSE
            -- Insert a new expiration batch only if there's positive quantity to add
            IF IFNULL(NEW.TotalReceived, 0) > 0 THEN
                INSERT INTO expiration (Product_ID, BatchNum, ExpirationDate, Quantity)
                VALUES (NEW.Product_ID, v_BatchNum, NEW.ExpirationDate, NEW.TotalReceived);
            END IF;
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_restock_request` BEFORE UPDATE ON `restock` FOR EACH ROW BEGIN
	-- UPDATE restock
	-- SET 
    -- Product_ID = New.Product_ID,
	-- Supplier_ID = New.Supplier_ID,
    -- Quantity = New.Quantity;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `Order_ID` int NOT NULL,
  `Transaction_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Unit_Price` int NOT NULL,
  `Quantity` int NOT NULL,
  `TotalPrice` int DEFAULT NULL,
  `Barcode` varchar(100) NOT NULL,
  `SalesDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int DEFAULT NULL,
  `BatchNum` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`Order_ID`, `Transaction_ID`, `Product_ID`, `ProductName`, `Unit_Price`, `Quantity`, `TotalPrice`, `Barcode`, `SalesDate`, `id`, `BatchNum`) VALUES
(91, 7, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-04 01:16:00', NULL, NULL),
(92, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-04 01:19:00', NULL, NULL),
(93, 8, 3, 'MIRROR2', 10000, 10, 100000, '11111', '2025-10-04 01:20:00', NULL, NULL),
(94, 8, 4, 'MIRROR3', 1000, 10, 10000, '11111111111', '2025-10-04 01:20:00', NULL, NULL),
(95, 8, 5, 'Honda Rims 123', 5000, 5, 25000, '20250900003', '2025-10-04 01:20:00', NULL, NULL),
(96, 7, 4, 'MIRROR3', 1000, 2, 2000, '11111111111', '2025-10-04 06:33:00', NULL, NULL),
(97, 7, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-03 06:34:00', NULL, NULL),
(98, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-02 06:34:00', NULL, NULL),
(99, 7, 3, 'MIRROR2', 10000, 12, 120000, '11111', '2025-10-03 06:35:00', NULL, NULL),
(100, 9, 3, 'MIRROR2', 10000, 5, 50000, '11111', '2025-10-02 07:41:00', NULL, NULL),
(101, 8, 2, 'MIRROR', 1000, 110, 110000, '11111', '2025-10-01 07:57:00', NULL, NULL),
(102, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-07 03:41:54', NULL, NULL),
(103, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-07 03:43:15', NULL, NULL),
(104, 8, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 5, 12500, '1100110011', '2025-10-07 03:54:50', NULL, NULL),
(105, 8, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-07 03:58:22', NULL, NULL),
(106, 7, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-07 03:59:23', NULL, NULL),
(107, 9, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-07 04:06:42', NULL, NULL),
(108, 9, 9, 'cake', 1000, 2, 2000, '20250900006', '2025-10-07 04:11:22', NULL, NULL),
(109, 7, 9, 'cake', 1000, 1, 1000, '20250900006', '2025-10-07 04:17:36', NULL, NULL),
(111, 7, 9, 'cake', 1000, 2, 2000, '20250900006', '2025-10-07 04:31:23', NULL, NULL),
(112, 8, 9, 'cake', 1000, 1, 1000, '20250900006', '2025-10-07 05:27:39', NULL, NULL),
(113, 7, 9, 'cake', 1000, 3, 3000, '20250900006', '2025-10-07 05:45:03', NULL, NULL),
(114, 10, 9, 'cake', 1000, 10, 10000, '20250900006', '2025-10-07 05:49:36', NULL, NULL),
(115, 7, 12, 'Payong', 1000, 11, 11000, '20250900006', '2025-10-07 06:22:14', NULL, NULL),
(116, 8, 9, 'cake', 1000, 30, 30000, '20250900006', '2025-10-07 06:24:34', NULL, NULL),
(117, 8, 11, 'Russi', 11400, 25, 285000, '20250900006', '2025-10-07 06:26:38', NULL, NULL),
(118, 8, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 0, 0, '1100110011', '2025-10-08 04:05:06', NULL, NULL),
(119, 7, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 20, 50000, '1100110011', '2025-10-08 04:05:37', NULL, NULL),
(120, 7, 12, 'Payong', 1000, 4, 4000, '20250900006', '2025-10-08 04:06:14', NULL, NULL),
(123, 9, 13, '0000000', 1000, 2, 2000, '20250900006', '2025-10-12 03:01:49', NULL, NULL),
(128, 14, 13, '0000000', 1000, 2, 2000, '20250900006', '2025-10-14 02:37:42', NULL, NULL),
(129, 14, 13, '0000000', 1000, 2, 2000, '20250900006', '2025-10-14 02:40:42', NULL, NULL),
(130, 14, 13, '0000000', 1000, 2, 2000, '20250900006', '2025-10-14 02:46:50', NULL, NULL),
(131, 14, 14, 'Safeguard', 800, 10, 8000, '20250900006', '2025-10-14 02:49:51', NULL, NULL),
(132, 14, 15, 'TINAPAY', 10099, 5, 50495, '20250900006', '2025-10-14 05:40:53', NULL, NULL),
(133, 7, 15, 'TINAPAY', 10099, 5, 50495, '20250900006', '2025-10-14 05:43:31', NULL, NULL),
(134, 7, 15, 'TINAPAY', 10099, 4, 40396, '20250900006', '2025-10-14 05:44:53', NULL, NULL),
(135, 7, 15, 'TINAPAY', 10099, 1, 10099, '20250900006', '2025-10-14 05:45:47', NULL, NULL),
(136, 8, 15, 'TINAPAY', 10099, 1, 10099, '20250900006', '2025-10-14 05:47:30', NULL, NULL),
(137, 10, 15, 'TINAPAY', 10099, 1, 10099, '20250900006', '2025-10-14 05:47:44', NULL, NULL),
(138, 11, 15, 'TINAPAY', 10099, 2, 20198, '20250900006', '2025-10-14 05:48:08', NULL, NULL),
(139, 12, 16, 'SUMAN', 10002, 5, 50010, '20250900006', '2025-10-14 05:50:19', NULL, NULL),
(140, 8, 16, 'SUMAN', 10002, 1, 10002, '20250900006', '2025-10-14 05:53:17', NULL, NULL),
(141, 10, 17, 'LUMPIA', 50001, 7, 350007, '20250900006', '2025-10-14 05:58:03', NULL, NULL),
(142, 11, 18, 'Saging', 100, 20, 2000, '20250900006', '2025-10-14 07:45:17', NULL, NULL),
(143, 11, 18, 'Saging', 100, 5, 500, '20250900006', '2025-10-14 08:46:20', NULL, 'BATCH-18-005'),
(144, 8, 17, 'LUMPIA', 50001, 2, 100002, '20250900006', '2025-10-14 08:47:24', NULL, NULL),
(145, 10, 18, 'Saging', 100, 5, 500, '20250900006', '2025-10-14 08:47:52', NULL, 'BATCH-18-004'),
(146, 8, 18, 'Saging', 100, 10, 1000, '20250900006', '2025-10-14 08:48:28', NULL, 'BATCH-18-004'),
(147, 14, 16, 'SUMAN', 10002, 2, 20004, '20250900006', '2025-10-15 06:28:22', NULL, 'BATCH-16-001'),
(148, 9, 14, 'Safeguard', 800, 2, 1600, '20250900006', '2025-10-15 06:36:32', NULL, NULL),
(149, 9, 14, 'Safeguard', 800, 2, 1600, '20250900006', '2025-10-15 06:37:09', NULL, NULL),
(150, 9, 14, 'Safeguard', 800, 2, 1600, '20250900006', '2025-10-15 06:37:22', NULL, NULL),
(151, 9, 14, 'Safeguard', 800, 2, 1600, '20250900006', '2025-10-15 06:37:25', NULL, NULL),
(152, 9, 14, 'Safeguard', 800, 2, 1600, '20250900006', '2025-10-15 06:37:30', NULL, NULL),
(153, 10, 16, 'SUMAN', 10002, 2, 20004, '20250900006', '2025-10-15 06:38:53', NULL, 'BATCH-16-001'),
(154, 10, 16, 'SUMAN', 10002, 2, 20004, '20250900006', '2025-10-15 06:39:48', NULL, 'BATCH-16-001'),
(155, 9, 18, 'Saging', 100, 12, 1200, '20250900006', '2025-10-15 06:44:22', NULL, 'BATCH-18-005'),
(156, 9, 17, 'LUMPIA', 50001, 9, 450009, '20250900006', '2025-10-15 06:47:20', NULL, 'BATCH-17-001'),
(157, 8, 17, 'LUMPIA', 50001, 2, 100002, '20250900006', '2025-10-16 03:56:17', NULL, 'BATCH-17-001'),
(158, 8, 16, 'SUMAN', 10002, 5, 50010, '20250900006', '2025-10-16 04:27:47', NULL, 'BATCH-16-001'),
(159, 13, 18, 'Saging', 100, 7, 700, '20250900006', '2025-10-16 04:28:42', NULL, 'BATCH-18-001'),
(160, 8, 16, 'SUMAN', 10002, 2, 20004, 'undefined', '2025-10-16 06:05:45', NULL, 'BATCH-16-001'),
(161, 15, 18, 'Saging', 100, 3, 300, '20250900006', '2025-10-17 01:24:42', NULL, 'BATCH-18-004'),
(163, 16, 18, 'Saging', 100, 2, 200, '20250900006', '2025-10-17 04:40:25', NULL, 'BATCH-18-005'),
(164, 16, 16, 'SUMAN', 10002, 1, 10002, '20250900006', '2025-10-17 04:41:09', NULL, 'BATCH-16-001'),
(165, 16, 18, 'Saging', 100, 1, 100, '20250900006', '2025-10-17 04:42:27', NULL, 'BATCH-18-004'),
(166, 15, 20, 'hipon', 2000, 5, 10000, '20250900006', '2025-10-17 10:15:14', 1, NULL),
(167, 17, 12, 'Ps16 Dual Brake Master Original Ps16 X Ps13 Brake Master Assembly With Big Tank ', 1000, 10, 10000, '20250900006', '2025-10-17 17:00:57', 1, 'BATCH-12-002'),
(168, 18, 15, 'Nmax Tires', 10099, 1, 10099, '20250900006', '2025-10-17 17:32:42', 1, NULL),
(169, 19, 20, 'Russi Forks', 2000, 10, 20000, '20250900006', '2025-10-17 17:33:42', 1, NULL),
(170, 19, 9, 'nmax 155 left or right motorcycle Brake Maste', 1000, 10, 10000, '20250900006', '2025-10-17 17:35:14', 1, NULL),
(171, 20, 11, 'Russi Exhaust 302', 11400, 5, 57000, '20250900006', '2025-10-17 17:41:22', 1, NULL),
(172, 20, 11, 'Russi Exhaust 302', 11400, 10, 114000, '20250900006', '2025-10-17 17:47:55', 1, NULL);

--
-- Triggers `sales`
--
DELIMITER $$
CREATE TRIGGER `after_insert_sales` AFTER INSERT ON `sales` FOR EACH ROW BEGIN
    -- UPDATE product
    -- SET UnitSold = UnitSold + NEW.Quantity
    -- WHERE Product_ID = NEW.Product_ID;
    
    -- UPDATE expiration
    -- SET Quantity = NEW.Quantity
    -- WHERE Product_ID = NEW.Product_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sales_insert_aggregration` AFTER INSERT ON `sales` FOR EACH ROW BEGIN
    DECLARE daily_start DATE;
    DECLARE daily_end DATE;
    
    DECLARE weekly_start DATE;
    DECLARE weekly_end DATE;
    
    DECLARE monthly_start DATE;
    DECLARE monthly_end DATE;

    -- Compute period boundaries
    SET daily_start = DATE(NEW.SalesDate);
    SET daily_end = daily_start;

    SET weekly_start = DATE_SUB(NEW.SalesDate, INTERVAL WEEKDAY(NEW.SalesDate) DAY);
    SET weekly_end = DATE_ADD(weekly_start, INTERVAL 6 DAY);

    SET monthly_start = DATE_FORMAT(NEW.SalesDate, '%Y-%m-01');
    SET monthly_end = LAST_DAY(NEW.SalesDate);

    -- DAILY AGGREGATION
    INSERT INTO daily_sales (
        PeriodStart, PeriodEnd, Product_ID, TotalSales, TotalQuantity
    )
    VALUES (
         daily_start, daily_end, NEW.Product_ID, NEW.TotalPrice, NEW.Quantity
    )
    ON DUPLICATE KEY UPDATE
        TotalSales = TotalSales + NEW.TotalPrice,
        TotalQuantity = TotalQuantity + NEW.Quantity;

    -- WEEKLY AGGREGATION (no PeriodType)
    INSERT INTO weekly_sales (
        PeriodStart, PeriodEnd, Product_ID, TotalSales, TotalQuantity
    )
    VALUES (
        weekly_start, weekly_end, NEW.Product_ID, NEW.TotalPrice, NEW.Quantity
    )
    ON DUPLICATE KEY UPDATE
        TotalSales = TotalSales + NEW.TotalPrice,
        TotalQuantity = TotalQuantity + NEW.Quantity;

    -- MONTHLY AGGREGATION
    INSERT INTO monthly_sales (
         PeriodStart, PeriodEnd, Product_ID, TotalSales, TotalQuantity
    )
    VALUES (
         monthly_start, monthly_end, NEW.Product_ID, NEW.TotalPrice, NEW.Quantity
    )
    ON DUPLICATE KEY UPDATE
        TotalSales = TotalSales + NEW.TotalPrice,
        TotalQuantity = TotalQuantity + NEW.Quantity;



    -- DAILY TOTAL AGGREGATION (all products)
    INSERT INTO daily_total_sales (
        PeriodStart, PeriodEnd, TotalSales, TotalQuantity
    )
    VALUES (
        daily_start, daily_end, NEW.TotalPrice, NEW.Quantity
    )
    ON DUPLICATE KEY UPDATE
        TotalSales = TotalSales + NEW.TotalPrice,
        TotalQuantity = TotalQuantity + NEW.Quantity;

    -- WEEKLY TOTAL AGGREGATION (all products)
    INSERT INTO weekly_total_sales (
        PeriodStart, PeriodEnd, TotalSales, TotalQuantity
    )
    VALUES (
        weekly_start, weekly_end, TotalSales, TotalQuantity
    )
    ON DUPLICATE KEY UPDATE
        TotalSales = TotalSales + NEW.TotalPrice,
        TotalQuantity = TotalQuantity + NEW.Quantity;

    -- MONTHLY TOTAL AGGREGATION (all products)
    INSERT INTO monthly_total_sales (
        PeriodStart, PeriodEnd, TotalSales, TotalQuantity
    )
    VALUES (
        monthly_start, monthly_end, TotalSales, TotalQuantity
    )
    ON DUPLICATE KEY UPDATE
        TotalSales = TotalSales + NEW.TotalPrice,
        TotalQuantity = TotalQuantity + NEW.Quantity;








END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sales_insert_inventory` AFTER INSERT ON `sales` FOR EACH ROW BEGIN
    DECLARE current_inventory INT DEFAULT 0;
    DECLARE new_inventory INT DEFAULT 0;
    DECLARE new_status VARCHAR(20);
    DECLARE TransactionDate DATETIME;

    -- 1️⃣ Get current inventory for this product
    SELECT Inventory INTO current_inventory
    FROM inventory
    WHERE Product_ID = NEW.Product_ID
    LIMIT 1;

    -- 2️⃣ Compute new inventory
    SET new_inventory = current_inventory - NEW.Quantity;
    
    SELECT Transaction_Date INTO TransactionDate
    FROM transactions
    WHERE Transaction_ID = NEW.Transaction_ID;

    -- 3️⃣ Determine stock status
    SET new_status = CASE 
        WHEN new_inventory > 10 THEN 'IN-STOCK'
        WHEN new_inventory > 0 THEN 'LOW-STOCK'
        ELSE 'OUT-OF-STOCK'
    END;

    -- 4️⃣ Update main inventory table
    UPDATE inventory
    SET 
        Inventory = new_inventory,
        -- UnitOut = NEW.SalesDate,
        UnitOut = TransactionDate,
        Status = new_status
    WHERE Product_ID = NEW.Product_ID;

    -- 5️⃣ Update total sold units in product table
    UPDATE product
    SET UnitSold = UnitSold + NEW.Quantity
    WHERE Product_ID = NEW.Product_ID;

    -- 6️⃣ Deduct from the correct expiration batch only
    UPDATE expiration
    SET Quantity = Quantity - NEW.Quantity
    WHERE Product_ID = NEW.Product_ID
      AND BatchNum = NEW.BatchNum; 

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sales_update_inventory` BEFORE UPDATE ON `sales` FOR EACH ROW BEGIN
    DECLARE new_inventory INT;
    DECLARE new_status VARCHAR(20);
    DECLARE DATErelease DATETIME;

    -- Calculate new inventory from product table
    SELECT UnitsOrdered - UnitSold INTO new_inventory
    FROM product
    WHERE Product_ID = NEW.Product_ID;

    -- Determine the new status
    SET new_status = CASE 
        WHEN new_inventory > 10 THEN 'IN-STOCK'
        WHEN new_inventory > 0 THEN 'LOW-STOCK'
        ELSE 'OUT-OF-STOCK'
    END;

    -- Update the inventory table
    SELECT Transaction_Date INTO DATErelease
    FROM transactions
    WHERE Transaction_ID = NEW.Transaction_ID;
    
    UPDATE inventory
    SET 
        Inventory = new_inventory,
        UnitOut = DATErelease,
        Status = new_status
    WHERE Product_ID = NEW.Product_ID;
    

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sales_to_transactions` AFTER INSERT ON `sales` FOR EACH ROW BEGIN
    -- Update the inventory table
    
    DECLARE Price INT;
    SET Price = New.TotalPrice;
    
    UPDATE transactions
    SET 
        Total_Price = COALESCE(Total_Price, 0) + Price
        -- UnitOut = NEW.SalesDate,
        -- Status = new_status
    WHERE Transaction_ID = NEW.Transaction_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sales_to_transactions_UPDATE` AFTER UPDATE ON `sales` FOR EACH ROW BEGIN
    DECLARE price_difference DECIMAL(10,2);

    -- Compute the difference between new and old total prices
    SET price_difference = NEW.TotalPrice - OLD.TotalPrice;

    -- Apply only the difference to the transaction's total
    UPDATE transactions
    SET Total_Price = COALESCE(Total_Price, 0) + price_difference
    WHERE Transaction_ID = NEW.Transaction_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `salesaggregration`
--

CREATE TABLE `salesaggregration` (
  `Aggregation_ID` int NOT NULL,
  `PeriodType` enum('daily','weekly','monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `PeriodStart` date NOT NULL,
  `PeriodEnd` date NOT NULL,
  `Product_ID` int NOT NULL,
  `TotalSales` decimal(10,2) NOT NULL,
  `TotalQuantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `Supplier_ID` int NOT NULL,
  `SupplierName` varchar(100) NOT NULL,
  `Location` varchar(250) NOT NULL,
  `Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `PhoneNumber` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `OfferedProductsType` enum('Exhaust','Tires','Brakes','Stand','Forks','Rims','Mirror','Suspension','Box','Oil') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`Supplier_ID`, `SupplierName`, `Location`, `Email`, `PhoneNumber`, `OfferedProductsType`) VALUES
(1, 'James', 'Caloocan', 'James2025@gmail.com', '0938572641', 'Exhaust'),
(2, 'Clark', 'Malabon', 'Clark@gmail.com', '09384723512', 'Tires'),
(3, 'Michael', 'Caloocan', 'Michael@gmail.com', '09837284652', 'Exhaust'),
(4, 'KALOY', 'BULACAN', 'gomezcarlo333@gmail.com', '09382747531', 'Tires'),
(5, 'KALOY', 'Bulacan', 'gomezcarlo333@gmail.com', '09382747531', 'Brakes');

-- --------------------------------------------------------

--
-- Table structure for table `supplierreturns`
--

CREATE TABLE `supplierreturns` (
  `SReturns_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  `ReturnedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('Pending','Out-for-Delivery','Cancelled','Delivered') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Reason` enum('Wrong-Item','Damaged','Missing-Parts','Accidental','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplierreturns`
--

INSERT INTO `supplierreturns` (`SReturns_ID`, `Supplier_ID`, `Product_ID`, `Quantity`, `ReturnedDate`, `Status`, `Reason`) VALUES
(28, 1, 2, 1, '2025-10-17 04:57:34', 'Delivered', 'Damaged'),
(29, 1, 2, 1, '2025-10-17 04:57:34', 'Delivered', 'Damaged'),
(30, 1, 2, 1, '2025-10-17 04:57:34', 'Delivered', 'Damaged'),
(31, 1, 2, 1, '2025-10-17 04:57:34', 'Delivered', 'Damaged'),
(32, 1, 2, 1, '2025-10-17 04:57:34', 'Delivered', 'Damaged'),
(33, 1, 4, 1, '2025-10-17 04:58:12', 'Delivered', 'Accidental'),
(34, 3, 18, 222, '2025-10-17 04:58:48', 'Delivered', 'Damaged'),
(35, 3, 1, 1, '2025-10-17 05:18:04', 'Pending', 'Wrong-Item'),
(36, 3, 3, 1, '2025-10-17 05:18:14', 'Out-for-Delivery', 'Damaged'),
(37, 3, 3, 1, '2025-10-17 05:18:24', 'Cancelled', 'Missing-Parts'),
(38, 3, 2, 1, '2025-10-17 05:18:37', 'Pending', 'Accidental'),
(39, 2, 4, 1, '2025-10-17 05:18:49', 'Out-for-Delivery', 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `Transaction_ID` int NOT NULL,
  `Customer_ID` int DEFAULT NULL,
  `ReferenceNo` varchar(100) NOT NULL,
  `PurchaseType` enum('','Online','Over-the-Counter') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `PaymentMethod` enum('','Cash','eWallet') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ServiceType` enum('','Pick Up','Delivery') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Transaction_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Total_Price` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`Transaction_ID`, `Customer_ID`, `ReferenceNo`, `PurchaseType`, `PaymentMethod`, `ServiceType`, `Transaction_Date`, `Total_Price`) VALUES
(7, 3, '20250900001', 'Online', 'Cash', '', '2025-10-03 13:31:00', 100990),
(8, 4, '20250900002', 'Over-the-Counter', 'Cash', '', '2025-10-03 13:32:00', 291119),
(9, 3, '20250900001', 'Over-the-Counter', 'Cash', '', '2025-10-04 01:15:00', 459209),
(10, 4, '20250900001', 'Online', 'Cash', '', '2025-10-06 08:04:00', 400614),
(11, 3, '20250900001', 'Online', 'Cash', '', '2025-10-07 02:29:02', 22698),
(12, 3, '20250900001', 'Over-the-Counter', 'Cash', '', '2025-10-07 02:29:24', 50010),
(13, 5, '20250900002', 'Over-the-Counter', 'eWallet', 'Pick Up', '2025-10-07 02:31:09', 700),
(14, 8, '20250900001', 'Over-the-Counter', 'Cash', 'Pick Up', '2025-10-14 02:34:37', 80499),
(15, 4, '20250900010', 'Online', 'Cash', 'Delivery', '2025-10-17 01:24:16', 10300),
(16, 8, '20250900002', 'Online', 'eWallet', 'Pick Up', '2025-10-17 04:39:18', 10302),
(17, 3, '20250900001', 'Over-the-Counter', 'Cash', 'Pick Up', '2025-10-17 17:00:42', 10000),
(18, 3, '20250900001', 'Over-the-Counter', 'Cash', 'Pick Up', '2025-10-16 17:31:00', 10099),
(19, 4, '20250900001', 'Over-the-Counter', 'eWallet', 'Pick Up', '2025-10-16 17:33:00', 30000),
(20, 6, '20250900001', 'Over-the-Counter', 'eWallet', 'Pick Up', '2025-10-16 17:40:00', 171000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `userName` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `image` blob,
  `reset_token_hash` varchar(100) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userName`, `email`, `password`, `phone`, `image`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1, 'CARLOJAY', 'gomezcarlo222@gmail.com', '09fa386f06b9af7966cf63ec4effa3ae', '09167549519', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_sales_forecast`
-- (See below for the actual view)
--
CREATE TABLE `v_sales_forecast` (
`DailyQuantity` decimal(32,0)
,`MovingAverage3` decimal(35,2)
,`Product_ID` int
,`ProductName` varchar(100)
,`SalesDate` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_sales_next_forecast`
-- (See below for the actual view)
--
CREATE TABLE `v_sales_next_forecast` (
`ForecastDate` date
,`ForecastValue` decimal(35,2)
,`Product_ID` int
,`ProductName` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast` (
`DailyTotalQuantity` decimal(32,0)
,`MovingAverage3` decimal(35,2)
,`SalesDate` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_all`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_all` (
`Daily_MA3` decimal(35,2)
,`DailyTotalQuantity` decimal(32,0)
,`Monthly_MA3` decimal(35,2)
,`MonthlyTotalQuantity` decimal(32,0)
,`MonthStart` date
,`SalesDate` date
,`Weekly_MA3` decimal(35,2)
,`WeeklyTotalQuantity` decimal(32,0)
,`WeekStart` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_daily`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_daily` (
`MovingAverage3` decimal(35,2)
,`Period` date
,`TotalQuantity` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_monthly`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_monthly` (
`MovingAverage3` decimal(35,2)
,`Period` varchar(7)
,`TotalQuantity` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_weekly`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_weekly` (
`MovingAverage3` decimal(35,2)
,`Period` int
,`TotalQuantity` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `weekly_sales`
--

CREATE TABLE `weekly_sales` (
  `WeeklySales_ID` int NOT NULL,
  `PeriodStart` date DEFAULT NULL,
  `PeriodEnd` date DEFAULT NULL,
  `Product_ID` int DEFAULT NULL,
  `TotalSales` decimal(10,2) DEFAULT NULL,
  `TotalQuantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `weekly_sales`
--

INSERT INTO `weekly_sales` (`WeeklySales_ID`, `PeriodStart`, `PeriodEnd`, `Product_ID`, `TotalSales`, `TotalQuantity`) VALUES
(64, '2025-09-29', '2025-10-05', 1, 5000.00, 2),
(65, '2025-09-29', '2025-10-05', 2, 116000.00, 116),
(66, '2025-09-29', '2025-10-05', 3, 270000.00, 27),
(67, '2025-09-29', '2025-10-05', 4, 12000.00, 12),
(68, '2025-09-29', '2025-10-05', 5, 25000.00, 5),
(75, '2025-10-06', '2025-10-12', 2, 4000.00, 4),
(77, '2025-10-06', '2025-10-12', 1, 77500.00, 31),
(81, '2025-10-06', '2025-10-12', 9, 49000.00, 49),
(88, '2025-10-06', '2025-10-12', 12, 15000.00, 15),
(90, '2025-10-06', '2025-10-12', 11, 285000.00, 25),
(96, '2025-10-06', '2025-10-12', 13, 2000.00, 2),
(101, '2025-10-13', '2025-10-19', 13, 6000.00, 6),
(104, '2025-10-13', '2025-10-19', 14, 16000.00, 20),
(105, '2025-10-13', '2025-10-19', 15, 201980.00, 20),
(112, '2025-10-13', '2025-10-19', 16, 200040.00, 20),
(114, '2025-10-13', '2025-10-19', 17, 1000020.00, 20),
(115, '2025-10-13', '2025-10-19', 18, 6500.00, 65),
(139, '2025-10-13', '2025-10-19', 20, 30000.00, 15),
(140, '2025-10-13', '2025-10-19', 12, 10000.00, 10),
(143, '2025-10-13', '2025-10-19', 9, 10000.00, 10),
(144, '2025-10-13', '2025-10-19', 11, 171000.00, 15);

-- --------------------------------------------------------

--
-- Table structure for table `weekly_total_sales`
--

CREATE TABLE `weekly_total_sales` (
  `EntireWeeklySales_ID` int NOT NULL,
  `PeriodStart` date NOT NULL,
  `PeriodEnd` date NOT NULL,
  `TotalSales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `TotalQuantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `weekly_total_sales`
--

INSERT INTO `weekly_total_sales` (`EntireWeeklySales_ID`, `PeriodStart`, `PeriodEnd`, `TotalSales`, `TotalQuantity`) VALUES
(6, '2025-09-29', '2025-10-05', 423000.00, 160),
(17, '2025-10-06', '2025-10-12', 430500.00, 124),
(43, '2025-10-13', '2025-10-19', 1649540.00, 199);

-- --------------------------------------------------------

--
-- Structure for view `daily_sales_summary`
--
DROP TABLE IF EXISTS `daily_sales_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `daily_sales_summary`  AS SELECT `sales`.`Product_ID` AS `Product_ID`, `sales`.`ProductName` AS `ProductName`, `sales`.`SalesDate` AS `SalesDate`, sum(`sales`.`Quantity`) AS `TotalQuantity`, sum(`sales`.`TotalPrice`) AS `TotalRevenue` FROM `sales` GROUP BY `sales`.`Product_ID`, `sales`.`ProductName`, `sales`.`SalesDate` ORDER BY `sales`.`SalesDate` ASC, `sales`.`Product_ID` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_sales_forecast`
--
DROP TABLE IF EXISTS `v_sales_forecast`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_sales_forecast`  AS SELECT `sales`.`Product_ID` AS `Product_ID`, `sales`.`ProductName` AS `ProductName`, cast(`sales`.`SalesDate` as date) AS `SalesDate`, sum(`sales`.`Quantity`) AS `DailyQuantity`, round(avg(sum(`sales`.`Quantity`)) OVER (PARTITION BY `sales`.`Product_ID` ORDER BY cast(`sales`.`SalesDate` as date) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM `sales` GROUP BY `sales`.`Product_ID`, `sales`.`ProductName`, cast(`sales`.`SalesDate` as date) ORDER BY `sales`.`Product_ID` ASC, cast(`sales`.`SalesDate` as date) ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_sales_next_forecast`
--
DROP TABLE IF EXISTS `v_sales_next_forecast`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_sales_next_forecast`  AS SELECT `last3`.`Product_ID` AS `Product_ID`, `last3`.`ProductName` AS `ProductName`, (max(`last3`.`SalesDate`) + interval 1 day) AS `ForecastDate`, round(avg(`last3`.`DailyTotal`),2) AS `ForecastValue` FROM (select `sales`.`Product_ID` AS `Product_ID`,`sales`.`ProductName` AS `ProductName`,cast(`sales`.`SalesDate` as date) AS `SalesDate`,sum(`sales`.`TotalPrice`) AS `DailyTotal` from `sales` group by `sales`.`Product_ID`,`sales`.`ProductName`,cast(`sales`.`SalesDate` as date) order by `sales`.`Product_ID`,`SalesDate` desc limit 3) AS `last3` GROUP BY `last3`.`Product_ID`, `last3`.`ProductName` ;

-- --------------------------------------------------------

--
-- Structure for view `v_store_sales_forecast`
--
DROP TABLE IF EXISTS `v_store_sales_forecast`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast`  AS SELECT cast(`s`.`SalesDate` as date) AS `SalesDate`, sum(`s`.`Quantity`) AS `DailyTotalQuantity`, round(avg(sum(`s`.`Quantity`)) OVER (ORDER BY cast(`s`.`SalesDate` as date) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM `sales` AS `s` GROUP BY cast(`s`.`SalesDate` as date) ORDER BY cast(`s`.`SalesDate` as date) ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_store_sales_forecast_all`
--
DROP TABLE IF EXISTS `v_store_sales_forecast_all`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast_all`  AS WITH     `daily` as (select cast(`sales`.`SalesDate` as date) AS `SalesDate`,sum(`sales`.`Quantity`) AS `DailyTotalQuantity`,round(avg(sum(`sales`.`Quantity`)) OVER (ORDER BY cast(`sales`.`SalesDate` as date) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `Daily_MA3` from `sales` group by cast(`sales`.`SalesDate` as date)), `weekly` as (select yearweek(`sales`.`SalesDate`,1) AS `WeekNum`,min(cast(`sales`.`SalesDate` as date)) AS `WeekStart`,sum(`sales`.`Quantity`) AS `WeeklyTotalQuantity`,round(avg(sum(`sales`.`Quantity`)) OVER (ORDER BY yearweek(`sales`.`SalesDate`,1) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `Weekly_MA3` from `sales` group by yearweek(`sales`.`SalesDate`,1)), `monthly` as (select date_format(`sales`.`SalesDate`,'%Y-%m') AS `Month`,min(cast(`sales`.`SalesDate` as date)) AS `MonthStart`,sum(`sales`.`Quantity`) AS `MonthlyTotalQuantity`,round(avg(sum(`sales`.`Quantity`)) OVER (ORDER BY date_format(`sales`.`SalesDate`,'%Y-%m') ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `Monthly_MA3` from `sales` group by date_format(`sales`.`SalesDate`,'%Y-%m')) select `d`.`SalesDate` AS `SalesDate`,`d`.`DailyTotalQuantity` AS `DailyTotalQuantity`,`d`.`Daily_MA3` AS `Daily_MA3`,`w`.`WeekStart` AS `WeekStart`,`w`.`WeeklyTotalQuantity` AS `WeeklyTotalQuantity`,`w`.`Weekly_MA3` AS `Weekly_MA3`,`m`.`MonthStart` AS `MonthStart`,`m`.`MonthlyTotalQuantity` AS `MonthlyTotalQuantity`,`m`.`Monthly_MA3` AS `Monthly_MA3` from ((`daily` `d` left join `weekly` `w` on((yearweek(`d`.`SalesDate`,1) = `w`.`WeekNum`))) left join `monthly` `m` on((date_format(`d`.`SalesDate`,'%Y-%m') = `m`.`Month`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `v_store_sales_forecast_daily`
--
DROP TABLE IF EXISTS `v_store_sales_forecast_daily`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast_daily`  AS SELECT cast(`t`.`Transaction_Date` as date) AS `Period`, sum(`s`.`Quantity`) AS `TotalQuantity`, round(avg(sum(`s`.`Quantity`)) OVER (ORDER BY cast(`t`.`Transaction_Date` as date) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM (`sales` `s` join `transactions` `t` on((`s`.`Transaction_ID` = `t`.`Transaction_ID`))) GROUP BY cast(`t`.`Transaction_Date` as date) ORDER BY cast(`t`.`Transaction_Date` as date) ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_store_sales_forecast_monthly`
--
DROP TABLE IF EXISTS `v_store_sales_forecast_monthly`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast_monthly`  AS SELECT date_format(`t`.`Transaction_Date`,'%Y-%m') AS `Period`, sum(`s`.`Quantity`) AS `TotalQuantity`, round(avg(sum(`s`.`Quantity`)) OVER (ORDER BY date_format(`t`.`Transaction_Date`,'%Y-%m') ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM (`sales` `s` join `transactions` `t` on((`s`.`Transaction_ID` = `t`.`Transaction_ID`))) GROUP BY date_format(`t`.`Transaction_Date`,'%Y-%m') ORDER BY date_format(`t`.`Transaction_Date`,'%Y-%m') ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_store_sales_forecast_weekly`
--
DROP TABLE IF EXISTS `v_store_sales_forecast_weekly`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast_weekly`  AS SELECT yearweek(`t`.`Transaction_Date`,1) AS `Period`, sum(`s`.`Quantity`) AS `TotalQuantity`, round(avg(sum(`s`.`Quantity`)) OVER (ORDER BY yearweek(`t`.`Transaction_Date`,1) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM (`sales` `s` join `transactions` `t` on((`s`.`Transaction_ID` = `t`.`Transaction_ID`))) GROUP BY yearweek(`t`.`Transaction_Date`,1) ORDER BY yearweek(`t`.`Transaction_Date`,1) ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`AnalyticsID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`Customer_ID`);

--
-- Indexes for table `customersreturns`
--
ALTER TABLE `customersreturns`
  ADD PRIMARY KEY (`CReturn_ID`);

--
-- Indexes for table `daily_sales`
--
ALTER TABLE `daily_sales`
  ADD PRIMARY KEY (`DailySales_ID`),
  ADD UNIQUE KEY `uq_daily` (`PeriodStart`,`PeriodEnd`,`Product_ID`),
  ADD UNIQUE KEY `uniq_period_product` (`PeriodStart`,`PeriodEnd`,`Product_ID`);

--
-- Indexes for table `daily_total_sales`
--
ALTER TABLE `daily_total_sales`
  ADD PRIMARY KEY (`EntireDailySales_ID`),
  ADD UNIQUE KEY `unique_period` (`PeriodStart`,`PeriodEnd`);

--
-- Indexes for table `expiration`
--
ALTER TABLE `expiration`
  ADD PRIMARY KEY (`Expiration_ID`),
  ADD UNIQUE KEY `unique_product_batch` (`Product_ID`,`BatchNum`);

--
-- Indexes for table `forecast`
--
ALTER TABLE `forecast`
  ADD PRIMARY KEY (`Forecast_ID`);

--
-- Indexes for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  ADD PRIMARY KEY (`MonthlySales_ID`),
  ADD UNIQUE KEY `uq_monthly` (`PeriodStart`,`PeriodEnd`,`Product_ID`),
  ADD UNIQUE KEY `uniq_period_product` (`PeriodStart`,`PeriodEnd`,`Product_ID`);

--
-- Indexes for table `monthly_total_sales`
--
ALTER TABLE `monthly_total_sales`
  ADD PRIMARY KEY (`EntireMonthlySales_ID`),
  ADD UNIQUE KEY `unique_period` (`PeriodStart`,`PeriodEnd`);

--
-- Indexes for table `newaddition`
--
ALTER TABLE `newaddition`
  ADD PRIMARY KEY (`Inventory_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_ID`);

--
-- Indexes for table `pulledoutitems`
--
ALTER TABLE `pulledoutitems`
  ADD PRIMARY KEY (`Pulled_ID`);

--
-- Indexes for table `restock`
--
ALTER TABLE `restock`
  ADD PRIMARY KEY (`Orestock_ID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`Order_ID`);

--
-- Indexes for table `salesaggregration`
--
ALTER TABLE `salesaggregration`
  ADD PRIMARY KEY (`Aggregation_ID`),
  ADD UNIQUE KEY `unique_aggregation` (`PeriodType`,`PeriodStart`,`Product_ID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`Supplier_ID`);

--
-- Indexes for table `supplierreturns`
--
ALTER TABLE `supplierreturns`
  ADD PRIMARY KEY (`SReturns_ID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`Transaction_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekly_sales`
--
ALTER TABLE `weekly_sales`
  ADD PRIMARY KEY (`WeeklySales_ID`),
  ADD UNIQUE KEY `uq_weekly` (`PeriodStart`,`PeriodEnd`,`Product_ID`),
  ADD UNIQUE KEY `uniq_period_product` (`PeriodStart`,`PeriodEnd`,`Product_ID`);

--
-- Indexes for table `weekly_total_sales`
--
ALTER TABLE `weekly_total_sales`
  ADD PRIMARY KEY (`EntireWeeklySales_ID`),
  ADD UNIQUE KEY `unique_period` (`PeriodStart`,`PeriodEnd`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analytics`
--
ALTER TABLE `analytics`
  MODIFY `AnalyticsID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `Customer_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customersreturns`
--
ALTER TABLE `customersreturns`
  MODIFY `CReturn_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `daily_sales`
--
ALTER TABLE `daily_sales`
  MODIFY `DailySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `daily_total_sales`
--
ALTER TABLE `daily_total_sales`
  MODIFY `EntireDailySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `expiration`
--
ALTER TABLE `expiration`
  MODIFY `Expiration_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `forecast`
--
ALTER TABLE `forecast`
  MODIFY `Forecast_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  MODIFY `MonthlySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `monthly_total_sales`
--
ALTER TABLE `monthly_total_sales`
  MODIFY `EntireMonthlySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `newaddition`
--
ALTER TABLE `newaddition`
  MODIFY `Inventory_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pulledoutitems`
--
ALTER TABLE `pulledoutitems`
  MODIFY `Pulled_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `Orestock_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `Order_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `salesaggregration`
--
ALTER TABLE `salesaggregration`
  MODIFY `Aggregation_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `Supplier_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplierreturns`
--
ALTER TABLE `supplierreturns`
  MODIFY `SReturns_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `Transaction_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `weekly_sales`
--
ALTER TABLE `weekly_sales`
  MODIFY `WeeklySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `weekly_total_sales`
--
ALTER TABLE `weekly_total_sales`
  MODIFY `EntireWeeklySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expiration`
--
ALTER TABLE `expiration`
  ADD CONSTRAINT `expiration_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
