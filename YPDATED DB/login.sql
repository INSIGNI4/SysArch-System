-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 08:28 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `expiration`
--

CREATE TABLE `expiration` (
  `Expiration_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `BatchNum` varchar(50) NOT NULL,
  `ExpirationDate` date NOT NULL,
  `Quantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expiration`
--

INSERT INTO `expiration` (`Expiration_ID`, `Product_ID`, `BatchNum`, `ExpirationDate`, `Quantity`) VALUES
(1, 1, 'BATCH-1-001', '2026-10-24', 37),
(2, 2, 'BATCH-2-001', '2026-10-24', 38),
(3, 3, 'BATCH-3-001', '2026-10-24', 15),
(4, 4, 'BATCH-4-001', '2026-10-24', 5),
(5, 5, 'BATCH-5-001', '2026-10-24', 5),
(6, 6, 'BATCH-6-001', '2026-10-24', 10),
(7, 7, 'BATCH-7-001', '2026-10-24', 12),
(8, 8, 'BATCH-8-001', '2026-10-24', 12),
(9, 9, 'BATCH-9-001', '2026-10-24', 30),
(10, 10, 'BATCH-10-001', '2026-10-24', 40),
(11, 11, 'BATCH-11-001', '2026-10-24', 40),
(12, 12, 'BATCH-12-001', '2026-10-24', 16),
(13, 13, 'BATCH-13-001', '2026-10-24', 40),
(14, 14, 'BATCH-14-001', '2026-10-24', 24),
(15, 15, 'BATCH-15-001', '2026-10-24', 24),
(16, 16, 'BATCH-16-001', '2026-10-24', 40),
(17, 1, 'BATCH-1-002', '2026-11-24', 40);

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
(1, 'Shelf A', 'Row A', 78.00, 77, '2026-09-24 15:26:38', '2026-09-24 06:22:21', 'IN-STOCK', '2026-10-24', '6976693307846', 1),
(2, 'Shelf A', 'Row B', 60.00, 38, '2026-09-24 14:45:52', '2026-09-24 06:53:18', 'IN-STOCK', '2026-10-24', '6936520871469', 1),
(3, 'Shelf A', 'Row B', 280.00, 15, '2026-09-24 14:48:31', NULL, 'IN-STOCK', '2026-10-24', '2000224312185', 2),
(4, 'Shelf A', 'Row B', 315.00, 5, '2026-09-24 14:48:52', '2026-09-24 06:23:25', 'LOW-STOCK', '2026-10-24', '2000219213022', 2),
(5, 'Shelf A', 'Row C', 250.00, 5, '2026-09-24 14:49:07', '2026-09-24 06:50:48', 'LOW-STOCK', '2026-10-24', '2000213074353', 3),
(6, 'Shelf A', 'Row C', 135.00, 10, '2026-09-24 14:49:27', '2026-09-24 06:55:48', 'LOW-STOCK', '2026-10-24', '2000218723423', 3),
(7, 'Shelf A', 'Row D', 190.00, 12, '2026-09-24 14:49:47', NULL, 'IN-STOCK', '2026-10-24', '2000225506484', 4),
(8, 'Shelf A', 'Row D', 190.00, 12, '2026-09-24 14:51:01', NULL, 'IN-STOCK', '2026-10-24', '11111111111', 4),
(9, 'Shelf B', 'Row A', 40.00, 30, '2026-09-24 14:51:56', NULL, 'IN-STOCK', '2026-10-24', '2000212162167', 5),
(10, 'Shelf A', 'Row A', 55.00, 40, '2026-09-24 14:52:10', NULL, 'IN-STOCK', '2026-10-24', '2222222222222', 5),
(11, 'Shelf B', 'Row B', 55.00, 40, '2026-09-24 14:52:24', NULL, 'IN-STOCK', '2026-10-24', '2000225506484', 6),
(12, 'Shelf B', 'Row B', 99.00, 16, '2026-09-24 14:52:38', NULL, 'IN-STOCK', '2026-10-24', '3333333333333', 6),
(13, 'Shelf B', 'Row C', 45.00, 40, '2026-09-24 14:52:53', NULL, 'IN-STOCK', '2026-10-24', '6932808788887', 7),
(14, 'Shelf B', 'Row C', 75.00, 24, '2026-09-24 14:53:08', NULL, 'IN-STOCK', '2026-10-24', '4444444444444', 7),
(15, 'Shelf B', 'Row D', 420.00, 24, '2026-09-24 15:23:31', NULL, 'IN-STOCK', '2026-10-24', '6976693307846', 8),
(16, 'Shelf B', 'Row D', 580.00, 40, '2026-09-24 15:24:42', NULL, 'IN-STOCK', '2026-10-24', '5555555555555', 8);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_batch`
--

CREATE TABLE `inventory_batch` (
  `Batch_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Restock_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `BatchNum` varchar(50) NOT NULL,
  `ExpirationDate` date DEFAULT NULL,
  `ReceivedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Quantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_to_order`
--

CREATE TABLE `item_to_order` (
  `ItemToOrder_ID` int NOT NULL,
  `ListToOrder_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Supplier_ID` int DEFAULT NULL,
  `Current_Stock` int NOT NULL DEFAULT '0',
  `Predicted_Demand` int NOT NULL DEFAULT '0',
  `Net_Demand` int NOT NULL DEFAULT '0',
  `Pack_Size` int NOT NULL DEFAULT '1',
  `Recommended_Order_Quantity` int NOT NULL DEFAULT '0',
  `Ordered_Quantity` int NOT NULL DEFAULT '0',
  `Unit_Cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Line_Total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Received_Quantity` int NOT NULL DEFAULT '0',
  `Item_Status` enum('Pending','Ordered','Partially-Received','Received','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `item_to_order`
--

INSERT INTO `item_to_order` (`ItemToOrder_ID`, `ListToOrder_ID`, `Product_ID`, `Supplier_ID`, `Current_Stock`, `Predicted_Demand`, `Net_Demand`, `Pack_Size`, `Recommended_Order_Quantity`, `Ordered_Quantity`, `Unit_Cost`, `Line_Total`, `Received_Quantity`, `Item_Status`, `Notes`) VALUES
(1, 1, 1, 1, 0, 0, 0, 40, 0, 40, 55.00, 2200.00, 40, 'Received', ''),
(2, 1, 2, 1, 0, 0, 0, 40, 0, 40, 42.00, 1680.00, 40, 'Received', ''),
(3, 2, 3, 2, 0, 0, 0, 15, 0, 15, 190.00, 2850.00, 15, 'Received', ''),
(4, 2, 4, 2, 0, 0, 0, 10, 0, 10, 210.00, 2100.00, 10, 'Received', ''),
(5, 3, 5, 3, 0, 0, 0, 12, 0, 12, 175.00, 2100.00, 12, 'Received', ''),
(6, 3, 6, 3, 0, 0, 0, 12, 0, 12, 90.00, 1080.00, 12, 'Received', ''),
(7, 4, 7, 4, 0, 0, 0, 12, 0, 12, 140.00, 1680.00, 12, 'Received', ''),
(8, 4, 8, 4, 0, 0, 0, 12, 0, 12, 140.00, 1680.00, 12, 'Received', ''),
(9, 5, 9, 5, 0, 0, 0, 30, 0, 30, 26.00, 780.00, 30, 'Received', ''),
(10, 5, 10, 5, 0, 0, 0, 40, 0, 40, 35.00, 1400.00, 40, 'Received', ''),
(11, 6, 11, 6, 0, 0, 0, 40, 0, 40, 38.00, 1520.00, 40, 'Received', ''),
(12, 6, 12, 6, 0, 0, 0, 16, 0, 16, 68.00, 1088.00, 16, 'Received', ''),
(13, 7, 13, 7, 0, 0, 0, 40, 0, 40, 25.00, 1000.00, 40, 'Received', ''),
(14, 7, 14, 7, 0, 0, 0, 24, 0, 24, 45.00, 1080.00, 24, 'Received', ''),
(15, 8, 15, 8, 0, 0, 0, 24, 0, 24, 280.00, 6720.00, 24, 'Received', ''),
(16, 8, 16, 8, 0, 0, 0, 40, 0, 40, 390.00, 15600.00, 40, 'Received', ''),
(17, 9, 1, 1, 39, 0, 0, 40, 0, 40, 55.00, 2200.00, 40, 'Received', '');

--
-- Triggers `item_to_order`
--
DELIMITER $$
CREATE TRIGGER `trg_item_to_order_after_delete` AFTER DELETE ON `item_to_order` FOR EACH ROW BEGIN
    UPDATE list_to_order
    SET Total_Amount = (
        SELECT IFNULL(SUM(Line_Total), 0)
        FROM item_to_order
        WHERE ListToOrder_ID = OLD.ListToOrder_ID
    )
    WHERE ListToOrder_ID = OLD.ListToOrder_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_item_to_order_after_insert` AFTER INSERT ON `item_to_order` FOR EACH ROW BEGIN
    UPDATE list_to_order
    SET Total_Amount = (
        SELECT IFNULL(SUM(Line_Total), 0)
        FROM item_to_order
        WHERE ListToOrder_ID = NEW.ListToOrder_ID
    )
    WHERE ListToOrder_ID = NEW.ListToOrder_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_item_to_order_after_update` AFTER UPDATE ON `item_to_order` FOR EACH ROW BEGIN
    UPDATE list_to_order
    SET Total_Amount = (
        SELECT IFNULL(SUM(Line_Total), 0)
        FROM item_to_order
        WHERE ListToOrder_ID = NEW.ListToOrder_ID
    )
    WHERE ListToOrder_ID = NEW.ListToOrder_ID;

    IF OLD.ListToOrder_ID <> NEW.ListToOrder_ID THEN

        UPDATE list_to_order
        SET Total_Amount = (
            SELECT IFNULL(SUM(Line_Total), 0)
            FROM item_to_order
            WHERE ListToOrder_ID = OLD.ListToOrder_ID
        )
        WHERE ListToOrder_ID = OLD.ListToOrder_ID;

    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_item_to_order_before_insert` BEFORE INSERT ON `item_to_order` FOR EACH ROW BEGIN
    SET NEW.Net_Demand =
        GREATEST(
            IFNULL(NEW.Predicted_Demand, 0)
            - IFNULL(NEW.Current_Stock, 0),
            0
        );

    SET NEW.Line_Total =
        IFNULL(NEW.Ordered_Quantity, 0)
        * IFNULL(NEW.Unit_Cost, 0);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_item_to_order_before_update` BEFORE UPDATE ON `item_to_order` FOR EACH ROW BEGIN
    SET NEW.Net_Demand =
        GREATEST(
            IFNULL(NEW.Predicted_Demand, 0)
            - IFNULL(NEW.Current_Stock, 0),
            0
        );

    SET NEW.Line_Total =
        IFNULL(NEW.Ordered_Quantity, 0)
        * IFNULL(NEW.Unit_Cost, 0);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_list_order_status` AFTER UPDATE ON `item_to_order` FOR EACH ROW BEGIN

    DECLARE v_TotalItems INT DEFAULT 0;
    DECLARE v_ReceivedItems INT DEFAULT 0;
    DECLARE v_PartialItems INT DEFAULT 0;
    DECLARE v_OrderedItems INT DEFAULT 0;
    DECLARE v_PendingItems INT DEFAULT 0;
    DECLARE v_CancelledItems INT DEFAULT 0;


    /*
     * Total items inside this list.
     */
    SELECT COUNT(*)
    INTO v_TotalItems
    FROM item_to_order
    WHERE ListToOrder_ID = NEW.ListToOrder_ID;


    /*
     * Completely received items.
     */
    SELECT COUNT(*)
    INTO v_ReceivedItems
    FROM item_to_order
    WHERE ListToOrder_ID = NEW.ListToOrder_ID
      AND Item_Status = 'Received';


    /*
     * Partially received items.
     */
    SELECT COUNT(*)
    INTO v_PartialItems
    FROM item_to_order
    WHERE ListToOrder_ID = NEW.ListToOrder_ID
      AND Item_Status = 'Partially-Received';


    /*
     * Ordered items.
     */
    SELECT COUNT(*)
    INTO v_OrderedItems
    FROM item_to_order
    WHERE ListToOrder_ID = NEW.ListToOrder_ID
      AND Item_Status = 'Ordered';


    /*
     * Pending items.
     */
    SELECT COUNT(*)
    INTO v_PendingItems
    FROM item_to_order
    WHERE ListToOrder_ID = NEW.ListToOrder_ID
      AND Item_Status = 'Pending';


    /*
     * Cancelled items.
     */
    SELECT COUNT(*)
    INTO v_CancelledItems
    FROM item_to_order
    WHERE ListToOrder_ID = NEW.ListToOrder_ID
      AND Item_Status = 'Cancelled';


    /*
     * =========================================================
     * DETERMINE OVERALL LIST STATUS
     * =========================================================
     */

    /*
     * Every item is completely received.
     */
    IF v_TotalItems > 0
       AND v_ReceivedItems = v_TotalItems THEN

        UPDATE list_to_order
        SET Order_Status = 'Received'
        WHERE ListToOrder_ID = NEW.ListToOrder_ID;


    /*
     * At least one item has been partially/completely received.
     */
    ELSEIF v_PartialItems > 0
        OR v_ReceivedItems > 0 THEN

        UPDATE list_to_order
        SET Order_Status = 'Partially-Received'
        WHERE ListToOrder_ID = NEW.ListToOrder_ID;


    /*
     * At least one item is still ordered.
     */
    ELSEIF v_OrderedItems > 0 THEN

        UPDATE list_to_order
        SET Order_Status = 'Ordered'
        WHERE ListToOrder_ID = NEW.ListToOrder_ID;


    /*
     * Every item is pending.
     */
    ELSEIF v_PendingItems = v_TotalItems THEN

        UPDATE list_to_order
        SET Order_Status = 'Confirmed'
        WHERE ListToOrder_ID = NEW.ListToOrder_ID;


    /*
     * Every item is cancelled.
     */
    ELSEIF v_CancelledItems = v_TotalItems THEN

        UPDATE list_to_order
        SET Order_Status = 'Cancelled'
        WHERE ListToOrder_ID = NEW.ListToOrder_ID;

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `list_to_order`
--

CREATE TABLE `list_to_order` (
  `ListToOrder_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Order_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Expected_Receive_Date` datetime DEFAULT NULL,
  `Forecast_Start_Date` date DEFAULT NULL,
  `Forecast_End_Date` date DEFAULT NULL,
  `Order_Status` enum('Pending','Confirmed','Ordered','Partially-Received','Received','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `Total_Amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `Created_By` varchar(100) DEFAULT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `list_to_order`
--

INSERT INTO `list_to_order` (`ListToOrder_ID`, `Supplier_ID`, `Order_Date`, `Expected_Receive_Date`, `Forecast_Start_Date`, `Forecast_End_Date`, `Order_Status`, `Total_Amount`, `Created_By`, `Notes`) VALUES
(1, 1, '2026-09-24 14:33:06', '2026-09-29 00:00:00', NULL, NULL, 'Received', 3880.00, 'gomezcarlo222@gmail.com', ''),
(2, 2, '2026-09-24 14:39:04', '2026-09-29 00:00:00', NULL, NULL, 'Received', 4950.00, 'gomezcarlo222@gmail.com', ''),
(3, 3, '2026-09-24 14:39:36', '2026-09-29 00:00:00', NULL, NULL, 'Received', 3180.00, 'gomezcarlo222@gmail.com', ''),
(4, 4, '2026-09-24 14:40:04', '2026-09-29 00:00:00', NULL, NULL, 'Received', 3360.00, 'gomezcarlo222@gmail.com', ''),
(5, 5, '2026-09-24 14:41:12', '2026-09-29 00:00:00', NULL, NULL, 'Received', 2180.00, 'gomezcarlo222@gmail.com', ''),
(6, 6, '2026-09-24 14:41:47', '2026-09-29 00:00:00', NULL, NULL, 'Received', 2608.00, 'gomezcarlo222@gmail.com', ''),
(7, 7, '2026-09-24 14:42:21', '2026-09-29 00:00:00', NULL, NULL, 'Received', 2080.00, 'gomezcarlo222@gmail.com', ''),
(8, 8, '2026-09-24 14:43:16', '2026-09-29 00:00:00', NULL, NULL, 'Received', 22320.00, 'gomezcarlo222@gmail.com', ''),
(9, 1, '2026-09-24 15:22:01', '2026-09-22 00:00:00', NULL, NULL, 'Received', 2200.00, 'gomezcarlo222@gmail.com', '');

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
  `Type` enum('Noodles and Instant Meals','Frozen Hotpot Goods','Side Dishes','Canned Goods','Beverages','Sweets and Snacks','Ice Cream','Beauty & Personal Care') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ReordingPoints` int DEFAULT NULL,
  `UnitsOrdered` int DEFAULT NULL,
  `UnitSold` int DEFAULT NULL,
  `StorePrice` int NOT NULL,
  `SupplierPrice` int NOT NULL,
  `Image` blob,
  `Supplier_ID` int NOT NULL,
  `Pack_Size` int NOT NULL DEFAULT '1',
  `Barcode` varchar(100) NOT NULL,
  `LocationS` text NOT NULL,
  `LocationR` text NOT NULL,
  `CurrentStock` int GENERATED ALWAYS AS ((`UnitsOrdered` - `UnitSold`)) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `ProductName`, `Type`, `ReordingPoints`, `UnitsOrdered`, `UnitSold`, `StorePrice`, `SupplierPrice`, `Image`, `Supplier_ID`, `Pack_Size`, `Barcode`, `LocationS`, `LocationR`) VALUES
(1, 'Samyang Buldak Carbonara Ramen 130g', 'Noodles and Instant Meals', NULL, 80, 3, 78, 55, 0x366162346261396562336138315f73616d79616e672e6a666966, 1, 40, '6976693307846', 'Shelf A', 'Row A'),
(2, 'Nongshim Shin Ramyun Spicy 120g', 'Noodles and Instant Meals', NULL, 40, 2, 60, 42, 0x366162346262396135663739645f6e6f6e677368696d2e6a666966, 1, 40, '6936520871469', 'Shelf A', 'Row B'),
(3, 'Premium Sliced Beef Samgyupsal 500g', 'Frozen Hotpot Goods', NULL, 15, 0, 280, 190, 0x366162346263303331633137315f426565662d53616d6779757073616c2d353030672d312e706e67, 2, 15, '2000224312185', 'Shelf A', 'Row B'),
(4, 'Pork & Cabbage Mandu Dumplings 1kg', 'Frozen Hotpot Goods', NULL, 10, 5, 315, 210, 0x366162346263363830313965645f696d61676573202833292e6a666966, 2, 10, '2000219213022', 'Shelf A', 'Row B'),
(5, 'Jongga Mat Kimchi 500g', 'Side Dishes', NULL, 12, 7, 250, 175, 0x366162346264343564363638385f696d61676573202834292e6a666966, 3, 12, '2000213074353', 'Shelf A', 'Row C'),
(6, 'Daesang Season Pickled Radish 400g', 'Side Dishes', NULL, 12, 2, 135, 90, 0x366162346265303436363034645f696d61676573202835292e6a666966, 3, 12, '2000218723423', 'Shelf A', 'Row C'),
(7, 'Spam Classic Luncheon Meat 340g', 'Canned Goods', NULL, 12, 0, 190, 140, 0x366162346266363632623837395f36313130393030623961396164353338393632353839626338383335333263362e6a7067, 4, 12, '2000225506484', 'Shelf A', 'Row D'),
(8, 'Spam Less Sodium Luncheon Meat 340g', 'Canned Goods', NULL, 12, 0, 190, 140, 0x366162346266653264626131665f3831673839766d304e614c2e5f41435f5546313030302c313030305f514c38305f2e6a7067, 4, 12, '1111111111111', 'Shelf A', 'Row D'),
(9, 'Lotte Milkis Carbonated Milk Drink 250ml', 'Beverages', NULL, 30, 0, 40, 26, 0x366162346330346561623437395f70682d31313133343230372d38317a74632d6d6c6a6b77646d766469777766362e6a666966, 5, 30, '2000212162167', 'Shelf B', 'Row A'),
(10, 'Cantata Sweet Americano Liquid Pouch Drink 230ml', 'Beverages', NULL, 40, 0, 55, 35, 0x366162346330393635343236325f363156412d42475245674c2e6a7067, 5, 40, '2222222222222', 'Shelf A', 'Row A'),
(11, 'Lotte Pepero Chocolate Covered Biscuit Sticks 47g', 'Sweets and Snacks', NULL, 40, 0, 55, 38, 0x366162346330663532326266665f39613830613762353134343566363965653534343136323966333730643632332d66363062396336613334343837382d70657065726f2d63686f636f6c6174652d3437672e6a7067, 6, 40, '2000225506484', 'Shelf B', 'Row B'),
(12, 'Orion Turtle Chips Choco Churros Flavor 136g', 'Sweets and Snacks', NULL, 16, 0, 99, 68, 0x366162346331326461663164375f36317447494978474a654c2e6a7067, 6, 16, '3333333333333', 'Shelf B', 'Row B'),
(13, 'Binggrae Melona Melon Ice Cream Bar 80ml', 'Ice Cream', NULL, 40, 0, 45, 25, 0x366162346332383265383230615f34626334316232393837346363633130656238663539396439353833396666342e6a7067, 7, 40, '6932808788887', 'Shelf B', 'Row C'),
(14, 'Binggrae Samanco Red Bean Waffle Ice Cream 150ml', 'Ice Cream', NULL, 24, 0, 75, 45, 0x366162346332636365356333325f34323139633762336264643062316563396565653061343563323163353032622e6a70675f373230783732307138302e6a7067, 7, 24, '4444444444444', 'Shelf B', 'Row C'),
(15, 'COSRX Low pH Good Morning Gel Cleanser 150ml', 'Beauty & Personal Care', NULL, 24, 0, 420, 280, 0x366162346333356238333835625f696d61676573202836292e6a666966, 8, 24, '6976693307846', 'Shelf B', 'Row D'),
(16, 'Beauty of Joseon Relief Sun: Rice + Probiotics SPF50+ 50ml', 'Beauty & Personal Care', NULL, 40, 0, 580, 390, 0x366162346333643836643761365f696d61676573202837292e6a666966, 8, 40, '5555555555555', 'Shelf B', 'Row D');

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
    -- DECLARE v_ExpirationDate DATE;
    DECLARE v_Barcode VARCHAR(100);
    DECLARE v_NewInventory INT;
    DECLARE v_Status VARCHAR(20);
    DECLARE v_NewRestock DATE;

    
    
    
    
    
    
    
    
    
    
    

    -- Get product details
    SELECT StorePrice, Supplier_ID, 
    -- ExpirationDate, 
    Barcode
    INTO v_Price, v_Supplier_ID,
    -- v_ExpirationDate,
    v_Barcode
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
           -- ExpirationDate = v_ExpirationDate,
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
            -- ExpirationDate,
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
            -- v_ExpirationDate,
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
  `ItemToOrder_ID` int DEFAULT NULL,
  `Type` enum('New','Re-Order') NOT NULL,
  `Quantity` int NOT NULL DEFAULT '0',
  `OrderDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Product_ID` int NOT NULL,
  `Supplier_ID` int NOT NULL,
  `Status` enum('Requested','Out for Delivery','Partially-Received','Backorder-Fulfillment','Cancelled','Received') NOT NULL,
  `Image` blob,
  `DeliveryStatus` enum('','On-Time','Delayed','Early') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Date_Received` timestamp NULL DEFAULT NULL,
  `TotalReceived` int NOT NULL DEFAULT '0',
  `withIssue` int NOT NULL DEFAULT '0',
  `ExpirationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `restock`
--

INSERT INTO `restock` (`Orestock_ID`, `ItemToOrder_ID`, `Type`, `Quantity`, `OrderDate`, `Product_ID`, `Supplier_ID`, `Status`, `Image`, `DeliveryStatus`, `Date_Received`, `TotalReceived`, `withIssue`, `ExpirationDate`) VALUES
(1, 1, 'Re-Order', 40, '2026-09-24 06:43:37', 1, 1, 'Received', NULL, NULL, '2026-09-24 06:45:18', 40, 0, '2026-10-24'),
(2, 2, 'Re-Order', 40, '2026-09-24 06:43:37', 2, 1, 'Received', NULL, NULL, '2026-09-24 06:45:52', 40, 0, '2026-10-24'),
(4, 3, 'Re-Order', 15, '2026-09-24 06:43:42', 3, 2, 'Received', NULL, NULL, '2026-09-24 06:48:31', 15, 0, '2026-10-24'),
(5, 4, 'Re-Order', 10, '2026-09-24 06:43:42', 4, 2, 'Received', NULL, NULL, '2026-09-24 06:48:52', 10, 0, '2026-10-24'),
(7, 5, 'Re-Order', 12, '2026-09-24 06:43:47', 5, 3, 'Received', NULL, NULL, '2026-09-24 06:49:07', 12, 0, '2026-10-24'),
(8, 6, 'Re-Order', 12, '2026-09-24 06:43:47', 6, 3, 'Received', NULL, NULL, '2026-09-24 06:49:27', 12, 0, '2026-10-24'),
(10, 7, 'Re-Order', 12, '2026-09-24 06:43:52', 7, 4, 'Received', NULL, NULL, '2026-09-24 06:49:47', 12, 0, '2026-10-24'),
(11, 8, 'Re-Order', 12, '2026-09-24 06:43:52', 8, 4, 'Received', NULL, NULL, '2026-09-24 06:51:01', 12, 0, '2026-10-24'),
(13, 9, 'Re-Order', 30, '2026-09-24 06:43:56', 9, 5, 'Received', NULL, NULL, '2026-09-24 06:51:56', 30, 0, '2026-10-24'),
(14, 10, 'Re-Order', 40, '2026-09-24 06:43:56', 10, 5, 'Received', NULL, NULL, '2026-09-24 06:52:10', 40, 0, '2026-10-24'),
(16, 11, 'Re-Order', 40, '2026-09-24 06:44:01', 11, 6, 'Received', NULL, NULL, '2026-09-24 06:52:24', 40, 0, '2026-10-24'),
(17, 12, 'Re-Order', 16, '2026-09-24 06:44:01', 12, 6, 'Received', NULL, NULL, '2026-09-24 06:52:38', 16, 0, '2026-10-24'),
(19, 13, 'Re-Order', 40, '2026-09-24 06:44:08', 13, 7, 'Received', NULL, NULL, '2026-09-24 06:52:53', 40, 0, '2026-10-24'),
(20, 14, 'Re-Order', 24, '2026-09-24 06:44:08', 14, 7, 'Received', NULL, NULL, '2026-09-24 06:53:08', 24, 0, '2026-10-24'),
(22, 15, 'Re-Order', 24, '2026-09-24 06:44:13', 15, 8, 'Partially-Received', NULL, 'Early', '2026-09-24 07:22:37', 12, 0, '2026-10-24'),
(23, 16, 'Re-Order', 40, '2026-09-24 06:44:13', 16, 8, 'Received', NULL, 'Early', '2026-09-24 07:24:42', 40, 0, '2026-10-24'),
(25, 15, 'Re-Order', 12, '2026-09-24 07:22:37', 15, 8, 'Backorder-Fulfillment', NULL, 'Early', '2026-09-24 07:23:31', 12, 0, '2026-10-24'),
(26, 17, 'Re-Order', 40, '2026-09-24 07:26:13', 1, 1, 'Received', NULL, 'Delayed', '2026-09-24 07:26:38', 40, 0, '2026-11-24');

--
-- Triggers `restock`
--
DELIMITER $$
CREATE TRIGGER `restock_status_received` BEFORE UPDATE ON `restock` FOR EACH ROW BEGIN
    DECLARE v_ExpectedReceiveDate DATE;

    /* =========================================================
       1. Set Date_Received when actual received quantity increases
       ========================================================= */
    IF NEW.Status IN (
        'Partially-Received',
        'Backorder-Fulfillment',
        'Received'
    )
    AND IFNULL(NEW.TotalReceived, 0) >
        IFNULL(OLD.TotalReceived, 0)
    THEN

        SET NEW.Date_Received = CURRENT_TIMESTAMP;

    END IF;


    /* =========================================================
       2. Get Expected Receive Date from ListToOrder
       ========================================================= */
    IF NEW.ItemToOrder_ID IS NOT NULL
       AND NEW.Date_Received IS NOT NULL
    THEN

        SELECT l.Expected_Receive_Date
        INTO v_ExpectedReceiveDate
        FROM item_to_order i
        INNER JOIN list_to_order l
            ON l.ListToOrder_ID = i.ListToOrder_ID
        WHERE i.ItemToOrder_ID = NEW.ItemToOrder_ID
        LIMIT 1;


        /* =====================================================
           3. Compare Expected Date vs Actual Received Date
           ===================================================== */

        IF v_ExpectedReceiveDate IS NOT NULL THEN

            IF DATE(NEW.Date_Received) < v_ExpectedReceiveDate THEN

                SET NEW.DeliveryStatus = 'Early';

            ELSEIF DATE(NEW.Date_Received) =
                   v_ExpectedReceiveDate THEN

                SET NEW.DeliveryStatus = 'On-Time';

            ELSE

                SET NEW.DeliveryStatus = 'Delayed';

            END IF;

        END IF;

    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `restock_status_received_old` BEFORE UPDATE ON `restock` FOR EACH ROW BEGIN
    -- IF NEW.Status = 'Received' AND OLD.Status <> 'Received' THEN
        -- SET NEW.Date_Received = CURRENT_TIMESTAMP;
        -- SET NEW.DeliveryStatus = 'On-Time';
    -- END IF;
    
    
    
    
    
       -- IF NEW.Status IN (
        -- 'Partially-Received',
        -- 'Backorder-Fulfillment',
        -- 'Received'
    -- )
    -- AND IFNULL(NEW.TotalReceived, 0)
        -- > IFNULL(OLD.TotalReceived, 0)
    -- THEN

        -- SET NEW.Date_Received = CURRENT_TIMESTAMP;

    -- END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `restock_status_received_old2` BEFORE UPDATE ON `restock` FOR EACH ROW BEGIN

    -- IF NEW.Status IN (
        -- 'Partially-Received',
        -- 'Backorder-Fulfillment',
        -- 'Received'
    -- )
    -- AND IFNULL(NEW.TotalReceived, 0)
        -- > IFNULL(OLD.TotalReceived, 0)
    -- THEN

        -- SET NEW.Date_Received = CURRENT_TIMESTAMP;

    -- END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_product_unitorder` BEFORE UPDATE ON `restock` FOR EACH ROW BEGIN
    DECLARE v_NewInventory INT;
    DECLARE new_status VARCHAR(20);
    DECLARE v_BatchNum VARCHAR(50);
    DECLARE v_BatchCount INT;

    DECLARE v_ReceivedDiff INT DEFAULT 0;
    DECLARE v_NewReceivedTotal INT DEFAULT 0;


    /* =========================================================
       HOW MUCH NEW PRODUCT WAS ACTUALLY RECEIVED?
       ========================================================= */

    SET v_ReceivedDiff =
        IFNULL(NEW.TotalReceived, 0)
        - IFNULL(OLD.TotalReceived, 0);


    /* =========================================================
       CALCULATE NEW ITEM ORDER RECEIVED TOTAL
       BEFORE UPDATE
       ========================================================= */

    IF NEW.ItemToOrder_ID IS NOT NULL
       AND v_ReceivedDiff > 0 THEN

        SELECT
            IFNULL(Received_Quantity, 0)
            + v_ReceivedDiff
        INTO v_NewReceivedTotal
        FROM item_to_order
        WHERE ItemToOrder_ID = NEW.ItemToOrder_ID
        LIMIT 1;


        /* =====================================================
           UPDATE ITEM TO ORDER
           ===================================================== */

        UPDATE item_to_order
        SET
            Received_Quantity = v_NewReceivedTotal,

            Item_Status =
                CASE
                    WHEN v_NewReceivedTotal >= IFNULL(Ordered_Quantity, 0)
                    THEN 'Received'

                    ELSE 'Partially-Received'
                END

        WHERE ItemToOrder_ID = NEW.ItemToOrder_ID;

    END IF;


    /* =========================================================
       UPDATE PRODUCT / INVENTORY
       ========================================================= */

    IF v_ReceivedDiff > 0
       AND NEW.Status IN (
            'Partially-Received',
            'Backorder-Fulfillment',
            'Received'
       ) THEN

        UPDATE product
        SET UnitsOrdered = GREATEST(
            IFNULL(UnitsOrdered, 0)
            + v_ReceivedDiff,
            0
        )
        WHERE Product_ID = NEW.Product_ID;


        /* Calculate new inventory */

        SELECT
            IFNULL(UnitsOrdered, 0)
            - IFNULL(UnitSold, 0)
        INTO v_NewInventory
        FROM product
        WHERE Product_ID = NEW.Product_ID;


        SET new_status =
            CASE
                WHEN v_NewInventory > 10 THEN 'IN-STOCK'
                WHEN v_NewInventory > 0 THEN 'LOW-STOCK'
                ELSE 'OUT-OF-STOCK'
            END;


        UPDATE inventory
        SET
            UnitIN = COALESCE(
                NEW.Date_Received,
                CURRENT_TIMESTAMP
            ),

            Inventory = v_NewInventory,

            Status = new_status,

            ExpirationDate =
                CASE
                    WHEN ExpirationDate IS NULL
                        THEN NEW.ExpirationDate

                    WHEN NEW.ExpirationDate IS NULL
                        THEN ExpirationDate

                    WHEN NEW.ExpirationDate < ExpirationDate
                        THEN NEW.ExpirationDate

                    ELSE ExpirationDate
                END

        WHERE Product_ID = NEW.Product_ID;


        /* =====================================================
           UPDATE CURRENT SUPPLIER ONLY FOR CLEAN ON-TIME DELIVERY
           ===================================================== */

        IF NEW.DeliveryStatus = 'On-Time'
           AND IFNULL(NEW.withIssue, 0) = 0 THEN

            UPDATE product
            SET Supplier_ID = NEW.Supplier_ID
            WHERE Product_ID = NEW.Product_ID;

        END IF;

    END IF;


    /* =========================================================
       EXPIRATION / BATCH
       ========================================================= */

    IF v_ReceivedDiff > 0
       AND NEW.ExpirationDate IS NOT NULL THEN

        SELECT
            COUNT(*) + 1
        INTO v_BatchCount
        FROM expiration
        WHERE Product_ID = NEW.Product_ID;


        SET v_BatchNum = CONCAT(
            'BATCH-',
            NEW.Product_ID,
            '-',
            LPAD(v_BatchCount, 3, '0')
        );


        IF EXISTS (
            SELECT 1
            FROM expiration
            WHERE Product_ID = NEW.Product_ID
              AND ExpirationDate = NEW.ExpirationDate
        ) THEN

            UPDATE expiration
            SET Quantity =
                GREATEST(
                    IFNULL(Quantity, 0)
                    + v_ReceivedDiff,
                    0
                )

            WHERE Product_ID = NEW.Product_ID
              AND ExpirationDate = NEW.ExpirationDate;

        ELSE

            INSERT INTO expiration (
                Product_ID,
                BatchNum,
                ExpirationDate,
                Quantity
            )
            VALUES (
                NEW.Product_ID,
                v_BatchNum,
                NEW.ExpirationDate,
                v_ReceivedDiff
            );

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
  `BatchNum` varchar(50) DEFAULT NULL,
  `userName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `SupplierFName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `SupplierLName` varchar(100) NOT NULL,
  `Location` varchar(250) NOT NULL,
  `Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `PhoneNumber` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `OfferedProductsType` enum('Noodles and Instant Meals','Frozen Hotpot Goods','Side Dishes','Canned Goods','Beverages','Sweets and Snacks','Ice Cream','Beauty & Personal Care') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`Supplier_ID`, `SupplierFName`, `SupplierLName`, `Location`, `Email`, `PhoneNumber`, `OfferedProductsType`) VALUES
(1, 'Juan ', 'dela cruz', 'caloocan', 'juandelacruz@gmail.com', '09112233445', 'Noodles and Instant Meals'),
(2, 'JOSE', 'dela cruz', 'MAKATI', 'josedelacruz@gmail.com', '09112233445', 'Frozen Hotpot Goods'),
(3, 'James', 'DELAPAZ', 'Pasig', 'jamesdelapaz@gmail.com', '09112233445', 'Side Dishes'),
(4, 'john', 'de;lo santos', 'malabon', 'johndelosantos@gmail.com', '09112233445', 'Canned Goods'),
(5, 'michael', 'JACINTO', 'QUEZON CITY', 'michaeljacinto@gmail.com', '09112233445', 'Beverages'),
(6, 'thomas', 'hilario', 'tondo', 'thomashilario@gmail.com', '09112233445', 'Sweets and Snacks'),
(7, 'joshua', 'santos', 'recto', 'joshuasantos@gmail.com', '09112233445', 'Ice Cream'),
(8, 'alex', 'santos', 'baclaran', 'alexsantos@gmail.com', '09112233445', 'Beauty & Personal Care');

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

-- --------------------------------------------------------

--
-- Table structure for table `toorder`
--

CREATE TABLE `toorder` (
  `ToOrder` int NOT NULL,
  `Current_Stock` int NOT NULL,
  `Predicted_Demand` int NOT NULL,
  `Order_Amount` int NOT NULL,
  `Supplier` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `Transaction_ID` int NOT NULL,
  `ReferenceNo` varchar(100) NOT NULL,
  `PaymentMethod` enum('','Cash','eWallet') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Transaction_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Total_Price` int DEFAULT NULL,
  `User_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `reset_token_expires_at` datetime DEFAULT NULL,
  `account_activation_hash` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userName`, `email`, `password`, `phone`, `image`, `reset_token_hash`, `reset_token_expires_at`, `account_activation_hash`) VALUES
(17, 'Carlo', 'gomezcarlo222@gmail.com', 'c9e72a64b39834ba291fc2344d57f6f1', '09167549519', NULL, NULL, NULL, NULL),
(18, 'JAY', 'gomezcarlo333@gmail.com', 'c9e72a64b39834ba291fc2344d57f6f1', '09657763817', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_sales_forecast`
-- (See below for the actual view)
--
CREATE TABLE `v_sales_forecast` (
`Product_ID` int
,`ProductName` varchar(100)
,`SalesDate` date
,`DailyQuantity` decimal(32,0)
,`MovingAverage3` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_sales_next_forecast`
-- (See below for the actual view)
--
CREATE TABLE `v_sales_next_forecast` (
`Product_ID` int
,`ProductName` varchar(100)
,`ForecastDate` date
,`ForecastValue` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast` (
`SalesDate` date
,`DailyTotalQuantity` decimal(32,0)
,`MovingAverage3` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_all`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_all` (
`SalesDate` date
,`DailyTotalQuantity` decimal(32,0)
,`Daily_MA3` decimal(35,2)
,`WeekStart` date
,`WeeklyTotalQuantity` decimal(32,0)
,`Weekly_MA3` decimal(35,2)
,`MonthStart` date
,`MonthlyTotalQuantity` decimal(32,0)
,`Monthly_MA3` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_daily`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_daily` (
`Period` date
,`TotalQuantity` decimal(32,0)
,`MovingAverage3` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_monthly`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_monthly` (
`Period` varchar(7)
,`TotalQuantity` decimal(32,0)
,`MovingAverage3` decimal(35,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_store_sales_forecast_weekly`
-- (See below for the actual view)
--
CREATE TABLE `v_store_sales_forecast_weekly` (
`Period` int
,`TotalQuantity` decimal(32,0)
,`MovingAverage3` decimal(35,2)
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast_daily`  AS SELECT cast(`s`.`date_created` as date) AS `Period`, sum(`si`.`Quantity`) AS `TotalQuantity`, round(avg(sum(`si`.`Quantity`)) OVER (ORDER BY cast(`s`.`date_created` as date) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM (`point_of_sale`.`sales_item_test` `si` join `point_of_sale`.`sales_test` `s` on((`s`.`Sales_ID` = `si`.`Sales_ID`))) GROUP BY cast(`s`.`date_created` as date) ORDER BY cast(`s`.`date_created` as date) ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_store_sales_forecast_monthly`
--
DROP TABLE IF EXISTS `v_store_sales_forecast_monthly`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast_monthly`  AS SELECT date_format(`s`.`date_created`,'%Y-%m') AS `Period`, sum(`si`.`Quantity`) AS `TotalQuantity`, round(avg(sum(`si`.`Quantity`)) OVER (ORDER BY date_format(`s`.`date_created`,'%Y-%m') ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM (`point_of_sale`.`sales_item_test` `si` join `point_of_sale`.`sales_test` `s` on((`s`.`Sales_ID` = `si`.`Sales_ID`))) GROUP BY date_format(`s`.`date_created`,'%Y-%m') ORDER BY date_format(`s`.`date_created`,'%Y-%m') ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_store_sales_forecast_weekly`
--
DROP TABLE IF EXISTS `v_store_sales_forecast_weekly`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_store_sales_forecast_weekly`  AS SELECT yearweek(`s`.`date_created`,1) AS `Period`, sum(`si`.`Quantity`) AS `TotalQuantity`, round(avg(sum(`si`.`Quantity`)) OVER (ORDER BY yearweek(`s`.`date_created`,1) ROWS BETWEEN 2 PRECEDING AND CURRENT ROW) ,2) AS `MovingAverage3` FROM (`point_of_sale`.`sales_item_test` `si` join `point_of_sale`.`sales_test` `s` on((`s`.`Sales_ID` = `si`.`Sales_ID`))) GROUP BY yearweek(`s`.`date_created`,1) ORDER BY yearweek(`s`.`date_created`,1) ASC ;

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
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `forecast`
--
ALTER TABLE `forecast`
  ADD PRIMARY KEY (`Forecast_ID`);

--
-- Indexes for table `inventory_batch`
--
ALTER TABLE `inventory_batch`
  ADD PRIMARY KEY (`Batch_ID`),
  ADD UNIQUE KEY `uq_batch_number` (`BatchNum`),
  ADD KEY `fk_batch_product` (`Product_ID`),
  ADD KEY `fk_batch_restock` (`Restock_ID`),
  ADD KEY `fk_batch_supplier` (`Supplier_ID`);

--
-- Indexes for table `item_to_order`
--
ALTER TABLE `item_to_order`
  ADD PRIMARY KEY (`ItemToOrder_ID`),
  ADD KEY `fk_item_order_list` (`ListToOrder_ID`),
  ADD KEY `fk_item_order_product` (`Product_ID`);

--
-- Indexes for table `list_to_order`
--
ALTER TABLE `list_to_order`
  ADD PRIMARY KEY (`ListToOrder_ID`),
  ADD KEY `fk_list_order_supplier` (`Supplier_ID`);

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
  ADD PRIMARY KEY (`Orestock_ID`),
  ADD KEY `Product_ID` (`Product_ID`),
  ADD KEY `Supplier_ID` (`Supplier_ID`),
  ADD KEY `fk_restock_item_to_order` (`ItemToOrder_ID`);

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
-- Indexes for table `toorder`
--
ALTER TABLE `toorder`
  ADD PRIMARY KEY (`ToOrder`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`Transaction_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_activation_hash` (`account_activation_hash`);

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
  MODIFY `Customer_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customersreturns`
--
ALTER TABLE `customersreturns`
  MODIFY `CReturn_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_sales`
--
ALTER TABLE `daily_sales`
  MODIFY `DailySales_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_total_sales`
--
ALTER TABLE `daily_total_sales`
  MODIFY `EntireDailySales_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expiration`
--
ALTER TABLE `expiration`
  MODIFY `Expiration_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `forecast`
--
ALTER TABLE `forecast`
  MODIFY `Forecast_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_batch`
--
ALTER TABLE `inventory_batch`
  MODIFY `Batch_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_to_order`
--
ALTER TABLE `item_to_order`
  MODIFY `ItemToOrder_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `list_to_order`
--
ALTER TABLE `list_to_order`
  MODIFY `ListToOrder_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  MODIFY `MonthlySales_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_total_sales`
--
ALTER TABLE `monthly_total_sales`
  MODIFY `EntireMonthlySales_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newaddition`
--
ALTER TABLE `newaddition`
  MODIFY `Inventory_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pulledoutitems`
--
ALTER TABLE `pulledoutitems`
  MODIFY `Pulled_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `Orestock_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `Order_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salesaggregration`
--
ALTER TABLE `salesaggregration`
  MODIFY `Aggregation_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `Supplier_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supplierreturns`
--
ALTER TABLE `supplierreturns`
  MODIFY `SReturns_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `toorder`
--
ALTER TABLE `toorder`
  MODIFY `ToOrder` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `Transaction_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `weekly_sales`
--
ALTER TABLE `weekly_sales`
  MODIFY `WeeklySales_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weekly_total_sales`
--
ALTER TABLE `weekly_total_sales`
  MODIFY `EntireWeeklySales_ID` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_batch`
--
ALTER TABLE `inventory_batch`
  ADD CONSTRAINT `fk_batch_product` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_batch_supplier` FOREIGN KEY (`Supplier_ID`) REFERENCES `supplier` (`Supplier_ID`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `item_to_order`
--
ALTER TABLE `item_to_order`
  ADD CONSTRAINT `fk_item_order_list` FOREIGN KEY (`ListToOrder_ID`) REFERENCES `list_to_order` (`ListToOrder_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_order_product` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `list_to_order`
--
ALTER TABLE `list_to_order`
  ADD CONSTRAINT `fk_list_order_supplier` FOREIGN KEY (`Supplier_ID`) REFERENCES `supplier` (`Supplier_ID`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `restock`
--
ALTER TABLE `restock`
  ADD CONSTRAINT `fk_restock_item_to_order` FOREIGN KEY (`ItemToOrder_ID`) REFERENCES `item_to_order` (`ItemToOrder_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
