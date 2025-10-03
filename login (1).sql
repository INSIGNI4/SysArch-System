-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 10:21 AM
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
(1, 'Arnold', 'Bulacan', 'arnold.arnold@gmail.com', 903850356),
(2, 'jay', 'navotas', 'JAY@GMAIL.COM', 938274753);

-- --------------------------------------------------------

--
-- Table structure for table `customersreturns`
--

CREATE TABLE `customersreturns` (
  `CReturn_ID` int NOT NULL,
  `ReferenceNo` varchar(100) NOT NULL,
  `Quantity` int NOT NULL,
  `ReturnedDate` timestamp NOT NULL,
  `ReasonForReturn` enum('Wrong Item','Damaged','Not Match','Faulty','Missing Parts','Not Fit','Accidental','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Customer_ID` int DEFAULT NULL,
  `Product_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customersreturns`
--

INSERT INTO `customersreturns` (`CReturn_ID`, `ReferenceNo`, `Quantity`, `ReturnedDate`, `ReasonForReturn`, `Customer_ID`, `Product_ID`) VALUES
(3, '09012930291', 1, '2025-09-22 07:16:00', 'Damaged', NULL, 1);

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
(1, '2025-09-28', '2025-09-28', 5, 10000.00, 2),
(2, '2025-09-28', '2025-09-28', 3, 170000.00, 17),
(4, '2025-09-20', '2025-09-20', 3, 50000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `forecast`
--

CREATE TABLE `forecast` (
  `Forecast_ID` int NOT NULL,
  `ForecastType` text NOT NULL,
  `ProductScope` int NOT NULL,
  `ForecastPeriod` text NOT NULL,
  `ForecastStart` date NOT NULL,
  `ForecastEnd` date NOT NULL,
  `ProjectedSales` int NOT NULL,
  `ConfidenceLevel` int NOT NULL,
  `Account_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `Product_ID` int NOT NULL,
  `LocationS` varchar(100) NOT NULL,
  `LocationR` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Inventory` int NOT NULL,
  `UnitIN` datetime DEFAULT NULL,
  `UnitOut` timestamp NULL DEFAULT NULL,
  `Status` varchar(100) NOT NULL,
  `ExpirationDate` date DEFAULT NULL,
  `Barcode` varchar(100) NOT NULL,
  `Supplier_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`Product_ID`, `LocationS`, `LocationR`, `Price`, `Inventory`, `UnitIN`, `UnitOut`, `Status`, `ExpirationDate`, `Barcode`, `Supplier_ID`) VALUES
(1, 'Shelf A', 'Row A', 150000.00, -4, '2025-09-20 09:15:00', '2025-09-27 05:19:00', 'OUT-OF-STOCK', NULL, '1100110011', 1),
(3, 'Shelf A', 'Row A', 10000.00, -14, '2025-09-20 09:21:00', '2025-09-20 08:11:00', 'OUT-OF-STOCK', NULL, '11111', 1),
(2, 'Shelf A', 'Row A', 1000.00, 9, '2025-09-23 13:21:00', '2025-09-27 05:17:00', 'LOW-STOCK', '2025-09-20', '11111', 1),
(4, 'Shelf C', 'Row B', 1000.00, 13, '2025-09-23 13:24:00', '2025-09-28 03:33:00', 'IN-STOCK', '2025-09-22', '11111111111', 1),
(5, 'Shelf A', 'Row A', 5000.00, -2, '2025-09-27 13:50:00', '2025-09-28 05:43:00', 'OUT-OF-STOCK', NULL, '20250900003', 1);

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
(1, '2025-09-01', '2025-09-30', 5, 10000.00, 2),
(2, '2025-09-01', '2025-09-30', 3, 220000.00, 22);

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
(1, 1, 11, '2025-09-21 00:52:00', NULL, 'New', 1, 'Shelf A', 'Row A'),
(2, 1, 1, '2025-09-20 01:15:00', NULL, 'New', 1, 'Shelf A', 'Row A'),
(4, 3, 10, '2025-09-20 01:21:00', NULL, 'New', 1, 'Shelf A', 'Row A'),
(5, 2, 11, '2025-09-23 05:21:00', NULL, 'New', 1, 'Shelf A', 'Row A'),
(8, 4, 15, '2025-09-23 05:24:00', NULL, 'New', 3, 'Shelf C', 'Row B'),
(9, 5, 10, '2025-09-27 05:50:00', NULL, 'New', 1, 'Shelf A', 'Row A');

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
  `Barcode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `ProductName`, `Type`, `ReordingPoints`, `UnitsOrdered`, `UnitSold`, `StorePrice`, `SupplierPrice`, `Image`, `Supplier_ID`, `ExpirationDate`, `Barcode`) VALUES
(1, 'TRC Racing Honda XRM 110 Power Pipe', 'Exhaust', NULL, 10, NULL, 2500, 2200, 0x363863643839373934653761335f73686f7070696e672e77656270, 1, NULL, '1100110011'),
(2, 'MIRROR', 'Mirror', NULL, 10, NULL, 1000, 800, 0x363863653030633337323030655f73686f7070696e672e77656270, 1, '2025-09-20', '11111'),
(3, 'MIRROR2', 'Mirror', NULL, 10, NULL, 10000, 8000, 0x363864613262393362623235665f73686f7070696e672e77656270, 2, NULL, '11111'),
(4, 'MIRROR3', 'Mirror', NULL, NULL, NULL, 1000, 100, NULL, 1, '2025-09-22', '11111111111'),
(5, 'Honda Rims 123', 'Rims', NULL, NULL, NULL, 5000, 4000, NULL, 1, NULL, '20250900003'),
(6, 'Exhaust Misibaby', 'Exhaust', NULL, NULL, NULL, 3000, 2800, NULL, 2, NULL, '20250900006');

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
  `Type` enum('New','Re-Order') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Quantity` int NOT NULL,
  `OrderDate` timestamp NOT NULL,
  `Product_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Status` enum('Requested','Out-for-Delivery','Cancelled','Received') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Image` blob,
  `DeliveryStatus` enum('','On-Time','Delayed','Early') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `restock`
--

INSERT INTO `restock` (`Orestock_ID`, `Type`, `Quantity`, `OrderDate`, `Product_ID`, `Supplier_ID`, `Status`, `Image`, `DeliveryStatus`) VALUES
(6, 'New', 10, '2025-09-29 06:23:00', 2, 2, 'Cancelled', '', 'On-Time'),
(7, 'New', 1, '2025-09-29 06:38:00', 2, 1, 'Cancelled', 0x73686f7070696e672e77656270, 'Delayed'),
(8, 'Re-Order', 10, '2025-09-29 07:43:00', 2, 2, 'Received', 0x363864613361373336363331625f73686f7070696e672e77656270, 'Early'),
(9, 'Re-Order', 10, '2025-09-29 08:00:00', 3, 2, 'Requested', 0x73686f7070696e672e77656270, ''),
(10, 'Re-Order', 10, '2025-09-29 08:08:00', 3, 1, 'Requested', 0x363864613365656533663938355f736c696465322e706e67, '');

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
  `TotalPrice` int NOT NULL,
  `Barcode` varchar(100) NOT NULL,
  `SalesDate` timestamp NOT NULL,
  `Account_ID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`Order_ID`, `Transaction_ID`, `Product_ID`, `ProductName`, `Unit_Price`, `Quantity`, `TotalPrice`, `Barcode`, `SalesDate`, `Account_ID`) VALUES
(15, 4, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-09-27 05:17:00', NULL),
(16, 4, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-09-27 05:19:00', NULL),
(17, 5, 5, 'Honda Rims 123', 5000, 10, 50000, '20250900003', '2025-09-27 05:51:00', NULL),
(18, 4, 6, 'Exhaust Misibaby', 3000, 2, 6000, '20250900006', '2025-09-28 03:31:00', NULL),
(19, 5, 3, 'MIRROR2', 10000, 2, 20000, '11111', '2025-09-28 03:33:00', NULL),
(20, 4, 4, 'MIRROR3', 1000, 2, 2000, '11111111111', '2025-09-28 03:33:00', NULL),
(21, 4, 5, 'Honda Rims 123', 5000, 2, 10000, '20250900003', '2025-09-28 05:43:00', NULL),
(22, 4, 3, 'MIRROR2', 10000, 12, 120000, '11111', '2025-09-28 06:16:00', NULL),
(23, 4, 3, 'MIRROR2', 10000, 5, 50000, '11111', '2025-09-28 08:11:00', NULL),
(24, 5, 3, 'MIRROR2', 10000, 5, 50000, '11111', '2025-09-20 08:11:00', NULL);

--
-- Triggers `sales`
--
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

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sales_insert_inventory` AFTER INSERT ON `sales` FOR EACH ROW BEGIN
    DECLARE new_inventory INT;
    DECLARE new_status VARCHAR(20);

    -- Calculate new inventory
    SELECT Inventory - NEW.Quantity INTO new_inventory
    FROM inventory
    WHERE Product_ID = NEW.Product_ID;

    -- Determine the new status
    SET new_status = CASE 
        WHEN new_inventory > 10 THEN 'IN-STOCK'
        WHEN new_inventory > 0 THEN 'LOW-STOCK'
        ELSE 'OUT-OF-STOCK'
    END;

    -- Update the inventory
    UPDATE inventory
    SET 
        Inventory = new_inventory,
        UnitOut = NEW.SalesDate,
        Status = new_status
    WHERE Product_ID = NEW.Product_ID;
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

--
-- Dumping data for table `salesaggregration`
--

INSERT INTO `salesaggregration` (`Aggregation_ID`, `PeriodType`, `PeriodStart`, `PeriodEnd`, `Product_ID`, `TotalSales`, `TotalQuantity`) VALUES
(1, 'daily', '2025-09-20', '2025-09-20', 1, 105000.00, 20),
(2, 'weekly', '2025-09-15', '2025-09-21', 1, 105000.00, 20),
(3, 'monthly', '2025-09-01', '2025-09-30', 1, 126000.00, 26),
(7, 'daily', '2025-09-23', '2025-09-23', 1, 11000.00, 2),
(8, 'weekly', '2025-09-22', '2025-09-28', 1, 21000.00, 6),
(10, 'daily', '2025-09-27', '2025-09-27', 1, 10000.00, 4),
(13, 'daily', '2025-09-27', '2025-09-27', 2, 2000.00, 2),
(14, 'weekly', '2025-09-22', '2025-09-28', 2, 2000.00, 2),
(15, 'monthly', '2025-09-01', '2025-09-30', 2, 2000.00, 2),
(19, 'daily', '2025-09-27', '2025-09-27', 5, 50000.00, 10),
(20, 'weekly', '2025-09-22', '2025-09-28', 5, 50000.00, 10),
(21, 'monthly', '2025-09-01', '2025-09-30', 5, 50000.00, 10),
(22, 'daily', '2025-09-28', '2025-09-28', 6, 6000.00, 2),
(23, 'weekly', '2025-09-22', '2025-09-28', 6, 6000.00, 2),
(24, 'monthly', '2025-09-01', '2025-09-30', 6, 6000.00, 2),
(25, 'daily', '2025-09-28', '2025-09-28', 3, 20000.00, 2),
(26, 'weekly', '2025-09-22', '2025-09-28', 3, 20000.00, 2),
(27, 'monthly', '2025-09-01', '2025-09-30', 3, 20000.00, 2),
(28, 'daily', '2025-09-28', '2025-09-28', 4, 2000.00, 2),
(29, 'weekly', '2025-09-22', '2025-09-28', 4, 2000.00, 2),
(30, 'monthly', '2025-09-01', '2025-09-30', 4, 2000.00, 2);

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
(3, 'Michael', 'Caloocan', 'Michael@gmail.com', '09837284652', 'Exhaust');

-- --------------------------------------------------------

--
-- Table structure for table `supplierreturns`
--

CREATE TABLE `supplierreturns` (
  `SReturns_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  `ReturnedDate` date NOT NULL,
  `Status` enum('Pending','Out for Delivery','Cancelled','Delivered') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Reason` enum('Wrong Item','Damaged','Missing Parts','Accidental','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplierreturns`
--

INSERT INTO `supplierreturns` (`SReturns_ID`, `Supplier_ID`, `Product_ID`, `Quantity`, `ReturnedDate`, `Status`, `Reason`) VALUES
(2, 1, 1, 1, '2025-09-23', 'Out for Delivery', 'Damaged');

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
  `Transaction_Date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`Transaction_ID`, `Customer_ID`, `ReferenceNo`, `PurchaseType`, `PaymentMethod`, `ServiceType`, `Transaction_Date`) VALUES
(4, 1, '20250900001', 'Online', 'Cash', 'Pick Up', '2025-09-27 04:36:00'),
(5, NULL, '20250900002', 'Online', 'Cash', 'Pick Up', '2025-09-27 04:38:00');

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
(1, '2025-09-22', '2025-09-28', 5, 10000.00, 2),
(2, '2025-09-22', '2025-09-28', 3, 170000.00, 17),
(4, '2025-09-15', '2025-09-21', 3, 50000.00, 5);

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
  ADD UNIQUE KEY `uq_daily` (`PeriodStart`,`PeriodEnd`,`Product_ID`);

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
  ADD UNIQUE KEY `uq_monthly` (`PeriodStart`,`PeriodEnd`,`Product_ID`);

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
  ADD UNIQUE KEY `uq_weekly` (`PeriodStart`,`PeriodEnd`,`Product_ID`);

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
  MODIFY `Customer_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customersreturns`
--
ALTER TABLE `customersreturns`
  MODIFY `CReturn_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `daily_sales`
--
ALTER TABLE `daily_sales`
  MODIFY `DailySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forecast`
--
ALTER TABLE `forecast`
  MODIFY `Forecast_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  MODIFY `MonthlySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `newaddition`
--
ALTER TABLE `newaddition`
  MODIFY `Inventory_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pulledoutitems`
--
ALTER TABLE `pulledoutitems`
  MODIFY `Pulled_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `Orestock_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `Order_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `salesaggregration`
--
ALTER TABLE `salesaggregration`
  MODIFY `Aggregation_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `Supplier_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supplierreturns`
--
ALTER TABLE `supplierreturns`
  MODIFY `SReturns_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `Transaction_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `weekly_sales`
--
ALTER TABLE `weekly_sales`
  MODIFY `WeeklySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
