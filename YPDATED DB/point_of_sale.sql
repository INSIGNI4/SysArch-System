-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 08:29 AM
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
-- Database: `point_of_sale`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `Customer_ID` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `contact` varchar(150) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_test`
--

CREATE TABLE `customers_test` (
  `Customer_ID` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `contact` varchar(150) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ml_predictions`
--

CREATE TABLE `ml_predictions` (
  `Prediction_ID` int NOT NULL,
  `Product_ID` varchar(100) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `ForecastDate` date NOT NULL,
  `Predicted_Demand` decimal(12,2) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `Sales_ID` int NOT NULL,
  `Customer_ID` int NOT NULL,
  `User_ID` int NOT NULL,
  `total_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `amount_tendered` double(10,2) NOT NULL,
  `change_amt` double(10,2) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_item`
--

CREATE TABLE `sales_item` (
  `SalesItem_ID` int NOT NULL,
  `Sales_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Unit_Price` double(10,2) NOT NULL,
  `TotalPrice` double(10,2) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `BatchNum` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_item_test`
--

CREATE TABLE `sales_item_test` (
  `SalesItem_ID` int NOT NULL,
  `Sales_ID` int NOT NULL,
  `Product_ID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Unit_Price` double(10,2) NOT NULL,
  `TotalPrice` double(10,2) NOT NULL,
  `Expiration_ID` int DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `BatchNum` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_item_test`
--

INSERT INTO `sales_item_test` (`SalesItem_ID`, `Sales_ID`, `Product_ID`, `Quantity`, `Unit_Price`, `TotalPrice`, `Expiration_ID`, `date_created`, `date_updated`, `BatchNum`) VALUES
(1, 1, 1, 1, 78.00, 78.00, 1, '2026-09-24 08:59:31', '2026-09-24 08:59:31', NULL),
(2, 2, 1, 1, 78.00, 78.00, 1, '2026-09-24 11:23:42', '2026-09-24 11:23:42', NULL),
(3, 3, 1, 1, 78.00, 78.00, 1, '2026-09-24 14:22:21', '2026-09-24 14:22:21', NULL),
(4, 4, 4, 5, 315.00, 1575.00, 4, '2026-09-24 14:23:25', '2026-09-24 14:23:25', NULL),
(5, 5, 5, 2, 250.00, 500.00, 5, '2026-09-24 14:49:45', '2026-09-24 14:49:45', NULL),
(6, 6, 5, 4, 250.00, 1000.00, 5, '2026-09-24 14:50:26', '2026-09-24 14:50:26', NULL),
(7, 7, 5, 1, 250.00, 250.00, 5, '2026-09-24 14:50:48', '2026-09-24 14:50:48', NULL),
(8, 8, 2, 2, 60.00, 120.00, 2, '2026-09-24 14:53:18', '2026-09-24 14:53:18', NULL),
(9, 9, 6, 2, 135.00, 270.00, 6, '2026-09-24 14:55:48', '2026-09-24 14:55:48', NULL);

--
-- Triggers `sales_item_test`
--
DELIMITER $$
CREATE TRIGGER `after_sales_item_test_insert` AFTER INSERT ON `sales_item_test` FOR EACH ROW BEGIN
    UPDATE login.product
    SET UnitSold = COALESCE(UnitSold, 0) + NEW.Quantity
    WHERE Product_ID = NEW.Product_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_sales_item_test_inventory` AFTER INSERT ON `sales_item_test` FOR EACH ROW BEGIN
    DECLARE current_inventory INT DEFAULT 0;
    DECLARE new_inventory INT DEFAULT 0;
    DECLARE new_status VARCHAR(20);
    DECLARE sale_date DATETIME;

    SELECT Inventory INTO current_inventory
    FROM login.inventory
    WHERE Product_ID = NEW.Product_ID
    LIMIT 1;

    SET new_inventory = current_inventory - NEW.Quantity;

    SELECT date_created INTO sale_date
    FROM sales_test
    WHERE Sales_ID = NEW.Sales_ID;

    SET new_status = CASE
        WHEN new_inventory > 10 THEN 'IN-STOCK'
        WHEN new_inventory > 0 THEN 'LOW-STOCK'
        ELSE 'OUT-OF-STOCK'
    END;

    UPDATE login.inventory
    SET
        Inventory = new_inventory,
        UnitOut = sale_date,
        Status = new_status
    WHERE Product_ID = NEW.Product_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_test`
--

CREATE TABLE `sales_test` (
  `Sales_ID` int NOT NULL,
  `ReferenceNo` varchar(100) NOT NULL,
  `User_ID` int NOT NULL,
  `total_amount` double(10,2) NOT NULL DEFAULT '0.00',
  `amount_tendered` double(10,2) NOT NULL,
  `change_amt` double(10,2) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_test`
--

INSERT INTO `sales_test` (`Sales_ID`, `ReferenceNo`, `User_ID`, `total_amount`, `amount_tendered`, `change_amt`, `date_created`, `date_updated`) VALUES
(1, 'POS-20260924-085931', 17, 78.00, 100.00, 22.00, '2026-09-24 08:59:31', '2026-09-24 08:59:31'),
(2, 'POS-20260924-112342', 17, 78.00, 100.00, 22.00, '2026-09-24 11:23:42', '2026-09-24 11:23:42'),
(3, 'POS-20260924-142221', 17, 78.00, 100.00, 22.00, '2026-09-24 14:22:21', '2026-09-24 14:22:21'),
(4, 'POS-20260924-142325', 17, 1575.00, 2000.00, 425.00, '2026-09-24 14:23:25', '2026-09-24 14:23:25'),
(5, 'POS-20260924-144945', 17, 500.00, 1000.00, 500.00, '2026-09-24 14:49:45', '2026-09-24 14:49:45'),
(6, 'POS-20260924-145026', 17, 1000.00, 1000.00, 0.00, '2026-09-24 14:50:26', '2026-09-24 14:50:26'),
(7, 'POS-20260924-145048', 17, 250.00, 300.00, 50.00, '2026-09-24 14:50:48', '2026-09-24 14:50:48'),
(8, 'POS-20260924-145318', 17, 120.00, 150.00, 30.00, '2026-09-24 14:53:18', '2026-09-24 14:53:18'),
(9, 'POS-20260924-145548', 17, 270.00, 300.00, 30.00, '2026-09-24 14:55:48', '2026-09-24 14:55:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`Customer_ID`);

--
-- Indexes for table `customers_test`
--
ALTER TABLE `customers_test`
  ADD PRIMARY KEY (`Customer_ID`);

--
-- Indexes for table `ml_predictions`
--
ALTER TABLE `ml_predictions`
  ADD PRIMARY KEY (`Prediction_ID`),
  ADD KEY `idx_product_date` (`Product_ID`,`ForecastDate`),
  ADD KEY `idx_category` (`Category`),
  ADD KEY `idx_forecast_date` (`ForecastDate`);

--
-- Indexes for table `restock`
--
ALTER TABLE `restock`
  ADD PRIMARY KEY (`Orestock_ID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`Sales_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `sales_item`
--
ALTER TABLE `sales_item`
  ADD PRIMARY KEY (`SalesItem_ID`),
  ADD KEY `Sales_ID` (`Sales_ID`),
  ADD KEY `BatchNum` (`BatchNum`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `sales_item_test`
--
ALTER TABLE `sales_item_test`
  ADD PRIMARY KEY (`SalesItem_ID`),
  ADD KEY `Sales_ID` (`Sales_ID`),
  ADD KEY `BatchNum` (`BatchNum`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `sales_test`
--
ALTER TABLE `sales_test`
  ADD PRIMARY KEY (`Sales_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `Customer_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers_test`
--
ALTER TABLE `customers_test`
  MODIFY `Customer_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ml_predictions`
--
ALTER TABLE `ml_predictions`
  MODIFY `Prediction_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restock`
--
ALTER TABLE `restock`
  MODIFY `Orestock_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `Sales_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_item`
--
ALTER TABLE `sales_item`
  MODIFY `SalesItem_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_item_test`
--
ALTER TABLE `sales_item_test`
  MODIFY `SalesItem_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sales_test`
--
ALTER TABLE `sales_test`
  MODIFY `Sales_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customers` (`Customer_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `login`.`users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sales_item`
--
ALTER TABLE `sales_item`
  ADD CONSTRAINT `sales_item_ibfk_1` FOREIGN KEY (`Sales_ID`) REFERENCES `sales` (`Sales_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sales_item_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `login`.`product` (`Product_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
