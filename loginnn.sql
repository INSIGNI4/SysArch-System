-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 10:48 AM
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
  `ReturnedDate` timestamp NOT NULL,
  `ReasonForReturn` enum('Wrong Item','Damaged','Not Match','Faulty','Missing Parts','Not Fit','Accidental','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Customer_ID` int DEFAULT NULL,
  `Product_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customersreturns`
--

INSERT INTO `customersreturns` (`CReturn_ID`, `ReferenceNo`, `Quantity`, `ReturnedDate`, `ReasonForReturn`, `Customer_ID`, `Product_ID`) VALUES
(4, '20250900005', 1, '2025-10-04 01:01:00', 'Not Fit', 4, 5),
(5, '20250900001', 1, '2025-10-06 07:54:00', 'Damaged', 4, 2);

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
(93, '2025-10-08', '2025-10-08', 12, 4000.00, 4);

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
(34, '2025-10-08', '2025-10-08', 54000.00, 24);

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
  `Status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ExpirationDate` date DEFAULT NULL,
  `Barcode` varchar(100) NOT NULL,
  `Supplier_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`Product_ID`, `LocationS`, `LocationR`, `Price`, `Inventory`, `UnitIN`, `UnitOut`, `Status`, `ExpirationDate`, `Barcode`, `Supplier_ID`) VALUES
(2, 'Shelf A', 'Row A', 1000.00, 114, '2025-10-07 11:34:00', '2025-10-07 03:43:15', 'IN-STOCK', '2025-09-20', '11111', 1),
(3, 'Shelf A', 'Row A', 10000.00, 222, '2025-10-07 11:35:00', NULL, 'IN-STOCK', NULL, '11111', 2),
(9, 'Shelf A', 'Row A', 1000.00, 0, '2025-10-07 13:44:00', '2025-10-07 06:24:34', 'OUT-OF-STOCK', NULL, '20250900006', 1),
(11, 'Shelf A', 'Row A', 11400.00, 0, '2025-10-07 14:18:00', '2025-10-07 06:26:38', 'OUT-OF-STOCK', NULL, '20250900006', 1),
(12, 'Shelf A', 'Row A', 1000.00, 0, '2025-10-07 14:20:00', '2025-10-08 04:06:14', 'OUT-OF-STOCK', NULL, '20250900006', 1);

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
(81, '2025-10-01', '2025-10-31', 9, 49000.00, 49),
(88, '2025-10-01', '2025-10-31', 12, 15000.00, 15),
(90, '2025-10-01', '2025-10-31', 11, 285000.00, 25);

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
(6, '2025-10-01', '2025-10-31', 853500.00, 284);

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
(1, 'TRC Racing Honda XRM 110 Power Pipe', 'Tires', 10, 30, 30, 2500, 2200, 0x363863643839373934653761335f73686f7070696e672e77656270, 1, NULL, '1100110011', '', ''),
(2, 'MIRROR', 'Mirror', 10, 22, NULL, 1000, 800, 0x363863653030633337323030655f73686f7070696e672e77656270, 1, '2025-09-20', '20250900003', '', ''),
(3, 'MIRROR2', 'Mirror', 10, 10, NULL, 10000, 8000, 0x363864636564313963333832395f73686f7070696e672e77656270, 2, NULL, '20250900003', '', ''),
(4, 'MIRROR3', 'Mirror', 10, NULL, NULL, 1000, 100, NULL, 1, '2025-09-22', '20250900003', '', ''),
(5, 'Honda Rims 123', 'Rims', NULL, NULL, NULL, 5000, 4000, NULL, 1, NULL, '20250900003', '', ''),
(6, 'Exhaust RUSSI', 'Exhaust', NULL, NULL, NULL, 3000, 2800, NULL, 2, NULL, '20250900006', '', ''),
(7, 'TRC Racing Honda XRM 110 Power Pipe', 'Exhaust', NULL, NULL, NULL, 2300, 2000, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(8, 'PASTILLAS', 'Stand', NULL, NULL, NULL, 100, 10, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(9, 'cake', 'Brakes', NULL, 47, 47, 1000, 10, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(11, 'Russi', 'Exhaust', NULL, 25, 25, 11400, 10000, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A'),
(12, 'Payong', 'Exhaust', NULL, 15, 15, 1000, 100, NULL, 1, NULL, '20250900006', 'Shelf A', 'Row A');

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `new_product_to_invetory` AFTER INSERT ON `product` FOR EACH ROW BEGIN
    DECLARE v_Price DECIMAL(10,2);
    DECLARE v_Supplier_ID VARCHAR(50);
    DECLARE v_ExpirationDate DATE;
    DECLARE v_Barcode VARCHAR(100);
    DECLARE v_NewInventory INT;
    DECLARE v_Status VARCHAR(20);
    DECLARE v_NewRestock DATE;

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
        -- Calculate new inventory
        SET v_NewInventory = NEW.UnitsOrdered - NEW.UnitSold;

        -- Set the new status
        SET v_Status = CASE 
            WHEN v_NewInventory > 10 THEN 'IN-STOCK'
            WHEN v_NewInventory > 0 THEN 'LOW-STOCK'
            ELSE 'OUT-OF-STOCK'
        END;

        -- Get latest restock date
        SELECT Date_Received INTO v_NewRestock
        FROM restock 
        WHERE Product_ID = NEW.Product_ID
        ORDER BY Date_Received DESC
        LIMIT 1;

        -- Update existing inventory (excluding Supplier_ID)
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
        -- Calculate new inventory
        SET v_NewInventory = NEW.UnitsOrdered - NEW.UnitSold;

        -- Set the new status
        SET v_Status = CASE 
            WHEN v_NewInventory > 10 THEN 'IN-STOCK'
            WHEN v_NewInventory > 0 THEN 'LOW-STOCK'
            ELSE 'OUT-OF-STOCK'
        END;

        -- Insert new inventory record (including Supplier_ID)
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
            NULL,
            NULL,
            NULL,
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
  `Type` enum('New','Re-Order') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Quantity` int DEFAULT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Product_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Status` enum('Requested','Out-for-Delivery','Cancelled','Received') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Image` blob,
  `DeliveryStatus` enum('','On-Time','Delayed','Early') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Date_Received` timestamp NULL DEFAULT NULL,
  `TotalReceived` int DEFAULT NULL,
  `withIssue` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `restock`
--

INSERT INTO `restock` (`Orestock_ID`, `Type`, `Quantity`, `OrderDate`, `Product_ID`, `Supplier_ID`, `Status`, `Image`, `DeliveryStatus`, `Date_Received`, `TotalReceived`, `withIssue`) VALUES
(10, 'New', 10, '2025-10-07 02:09:00', 2, 2, 'Received', '', '', '2025-10-07 05:00:45', 0, 0),
(11, 'New', 10, '2025-10-07 02:13:00', 1, 1, 'Received', '', '', '2025-10-07 05:00:45', 10, 0),
(12, 'New', 10, '2025-10-07 02:22:00', 1, 1, 'Received', '', '', '2025-10-07 05:00:45', 10, 0),
(13, 'Re-Order', 12, '2025-10-07 04:01:04', 4, 1, 'Received', '', '', '2025-10-07 05:00:45', 12, 0),
(14, 'New', 12, '2025-10-07 04:02:50', 2, 2, 'Received', '', '', '2025-10-07 05:00:45', 12, 0),
(15, 'New', 12, '2025-10-07 04:11:43', 9, 1, 'Received', '', '', '2025-10-07 05:00:45', 12, 0),
(16, 'Re-Order', 10, '2025-10-07 05:08:07', 9, 2, 'Received', '', '', '2025-10-07 05:26:00', 10, 0),
(17, 'Re-Order', 10, '2025-10-07 05:18:52', 2, 2, 'Received', '', NULL, NULL, 0, 0),
(18, 'New', 12, '2025-10-07 05:21:46', 5, 1, 'Received', '', 'On-Time', '2025-10-07 05:25:00', 12, 0),
(19, 'Re-Order', 12, '2025-10-07 05:28:25', 9, 2, 'Received', '', 'On-Time', '2025-10-07 05:28:00', 12, 0),
(20, 'New', 1, '2025-10-07 05:29:27', 9, 2, 'Received', '', 'Early', '2025-10-07 05:30:00', 1, 0),
(21, 'Re-Order', 12, '2025-10-07 05:43:32', 9, 1, 'Received', '', 'Early', '2025-10-07 05:44:00', 12, 0),
(22, 'New', 15, '2025-10-07 06:09:13', 11, 1, 'Received', '', 'On-Time', '2025-10-07 06:10:00', 15, 0),
(23, 'Re-Order', 10, '2025-10-07 06:17:49', 11, 1, 'Received', '', 'On-Time', '2025-10-07 06:18:00', 10, 0),
(24, 'New', 15, '2025-10-07 06:20:37', 12, 1, 'Received', '', 'On-Time', '2025-10-07 06:20:00', 15, 0);

--
-- Triggers `restock`
--
DELIMITER $$
CREATE TRIGGER `update_product_unitorder` BEFORE UPDATE ON `restock` FOR EACH ROW BEGIN
    DECLARE v_NewInventory INT;
    DECLARE new_status VARCHAR(20);

    -- Only do this if status is changing TO "Received" from something else
    IF NEW.Status = 'Received' AND OLD.Status != 'Received' THEN
        -- Update UnitsOrdered in product
        UPDATE product
        SET UnitsOrdered = UnitsOrdered + NEW.TotalReceived
        WHERE Product_ID = NEW.Product_ID;

        -- Calculate new inventory value
        SELECT UnitsOrdered - UnitSold
        INTO v_NewInventory
        FROM product
        WHERE Product_ID = NEW.Product_ID;
        
        
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
            Status = new_status
        WHERE Product_ID = NEW.Product_ID;
    END IF;
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
  `Account_ID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`Order_ID`, `Transaction_ID`, `Product_ID`, `ProductName`, `Unit_Price`, `Quantity`, `TotalPrice`, `Barcode`, `SalesDate`, `Account_ID`) VALUES
(91, 7, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-04 01:16:00', NULL),
(92, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-04 01:19:00', NULL),
(93, 8, 3, 'MIRROR2', 10000, 10, 100000, '11111', '2025-10-04 01:20:00', NULL),
(94, 8, 4, 'MIRROR3', 1000, 10, 10000, '11111111111', '2025-10-04 01:20:00', NULL),
(95, 8, 5, 'Honda Rims 123', 5000, 5, 25000, '20250900003', '2025-10-04 01:20:00', NULL),
(96, 7, 4, 'MIRROR3', 1000, 2, 2000, '11111111111', '2025-10-04 06:33:00', NULL),
(97, 7, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-03 06:34:00', NULL),
(98, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-02 06:34:00', NULL),
(99, 7, 3, 'MIRROR2', 10000, 12, 120000, '11111', '2025-10-03 06:35:00', NULL),
(100, 9, 3, 'MIRROR2', 10000, 5, 50000, '11111', '2025-10-02 07:41:00', NULL),
(101, 8, 2, 'MIRROR', 1000, 110, 110000, '11111', '2025-10-01 07:57:00', NULL),
(102, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-07 03:41:54', NULL),
(103, 8, 2, 'MIRROR', 1000, 2, 2000, '11111', '2025-10-07 03:43:15', NULL),
(104, 8, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 5, 12500, '1100110011', '2025-10-07 03:54:50', NULL),
(105, 8, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-07 03:58:22', NULL),
(106, 7, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-07 03:59:23', NULL),
(107, 9, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 2, 5000, '1100110011', '2025-10-07 04:06:42', NULL),
(108, 9, 9, 'cake', 1000, 2, 2000, '20250900006', '2025-10-07 04:11:22', NULL),
(109, 7, 9, 'cake', 1000, 1, 1000, '20250900006', '2025-10-07 04:17:36', NULL),
(111, 7, 9, 'cake', 1000, 2, 2000, '20250900006', '2025-10-07 04:31:23', NULL),
(112, 8, 9, 'cake', 1000, 1, 1000, '20250900006', '2025-10-07 05:27:39', NULL),
(113, 7, 9, 'cake', 1000, 3, 3000, '20250900006', '2025-10-07 05:45:03', NULL),
(114, 10, 9, 'cake', 1000, 10, 10000, '20250900006', '2025-10-07 05:49:36', NULL),
(115, 7, 12, 'Payong', 1000, 11, 11000, '20250900006', '2025-10-07 06:22:14', NULL),
(116, 8, 9, 'cake', 1000, 30, 30000, '20250900006', '2025-10-07 06:24:34', NULL),
(117, 8, 11, 'Russi', 11400, 25, 285000, '20250900006', '2025-10-07 06:26:38', NULL),
(118, 8, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 0, 0, '1100110011', '2025-10-08 04:05:06', NULL),
(119, 7, 1, 'TRC Racing Honda XRM 110 Power Pipe', 2500, 20, 50000, '1100110011', '2025-10-08 04:05:37', NULL),
(120, 7, 12, 'Payong', 1000, 4, 4000, '20250900006', '2025-10-08 04:06:14', NULL);

--
-- Triggers `sales`
--
DELIMITER $$
CREATE TRIGGER `after_insert_sales` AFTER INSERT ON `sales` FOR EACH ROW BEGIN
    UPDATE product
    SET UnitSold = UnitSold + NEW.Quantity
    WHERE Product_ID = NEW.Product_ID;
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
    DECLARE new_inventory INT;
    DECLARE new_status VARCHAR(20);

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
    UPDATE inventory
    SET 
        Inventory = new_inventory,
        UnitOut = NEW.SalesDate,
        Status = new_status
    WHERE Product_ID = NEW.Product_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sales_update_inventory` BEFORE UPDATE ON `sales` FOR EACH ROW BEGIN
    DECLARE new_inventory INT;
    DECLARE new_status VARCHAR(20);

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
  `Transaction_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`Transaction_ID`, `Customer_ID`, `ReferenceNo`, `PurchaseType`, `PaymentMethod`, `ServiceType`, `Transaction_Date`) VALUES
(7, 3, '20250900001', 'Online', 'Cash', '', '2025-10-03 13:31:00'),
(8, 4, '20250900002', 'Over-the-Counter', 'Cash', '', '2025-10-03 13:32:00'),
(9, 3, '20250900001', 'Over-the-Counter', 'Cash', '', '2025-10-04 01:15:00'),
(10, 4, '20250900001', 'Online', 'Cash', '', '2025-10-06 08:04:00'),
(11, 3, '20250900001', 'Online', 'Cash', '', '2025-10-07 02:29:02'),
(12, 3, '20250900001', 'Over-the-Counter', 'Cash', '', '2025-10-07 02:29:24'),
(13, 5, '20250900002', 'Over-the-Counter', 'eWallet', 'Pick Up', '2025-10-07 02:31:09');

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
(64, '2025-09-29', '2025-10-05', 1, 5000.00, 2),
(65, '2025-09-29', '2025-10-05', 2, 116000.00, 116),
(66, '2025-09-29', '2025-10-05', 3, 270000.00, 27),
(67, '2025-09-29', '2025-10-05', 4, 12000.00, 12),
(68, '2025-09-29', '2025-10-05', 5, 25000.00, 5),
(75, '2025-10-06', '2025-10-12', 2, 4000.00, 4),
(77, '2025-10-06', '2025-10-12', 1, 77500.00, 31),
(81, '2025-10-06', '2025-10-12', 9, 49000.00, 49),
(88, '2025-10-06', '2025-10-12', 12, 15000.00, 15),
(90, '2025-10-06', '2025-10-12', 11, 285000.00, 25);

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
(17, '2025-10-06', '2025-10-12', 428500.00, 122);

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
  MODIFY `CReturn_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `daily_sales`
--
ALTER TABLE `daily_sales`
  MODIFY `DailySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `daily_total_sales`
--
ALTER TABLE `daily_total_sales`
  MODIFY `EntireDailySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `forecast`
--
ALTER TABLE `forecast`
  MODIFY `Forecast_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  MODIFY `MonthlySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `monthly_total_sales`
--
ALTER TABLE `monthly_total_sales`
  MODIFY `EntireMonthlySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `newaddition`
--
ALTER TABLE `newaddition`
  MODIFY `Inventory_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pulledoutitems`
--
ALTER TABLE `pulledoutitems`
  MODIFY `Pulled_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `Orestock_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `Order_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

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
  MODIFY `Transaction_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `weekly_sales`
--
ALTER TABLE `weekly_sales`
  MODIFY `WeeklySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `weekly_total_sales`
--
ALTER TABLE `weekly_total_sales`
  MODIFY `EntireWeeklySales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
