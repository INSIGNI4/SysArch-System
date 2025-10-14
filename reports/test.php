<?php

    
$query="
BEGIN
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
        
    IF NEW.ExpirationDate IS NOT NULL THEN
        Declare v_BatchNum VARCHAR(50);
        Declare v_BatchCount INT;

        SELECT COUNT(*) + 1 INTO v_BatchCount
        FROM expiration
        WHERE Product_ID = NEW.Product_ID;

        -- (BATCH-1-001)
        SET v_BatchNum = CONCAT('BATCH-', NEW.Product_ID, '-', LPAD(v_BatchCount, 3, '0'));

        IF EXISTS (
            SELECT 1 FROM expiration 
            WHERE Product_ID = NEW.Product_ID 
              AND ExpirationDate = NEW.ExpirationDate
        ) THEN
            UPDATE expiration
            SET Quantity = Quantity + NEW.UnitsOrdered
            WHERE Product_ID = NEW.Product_ID 
              AND ExpirationDate = NEW.ExpirationDate;
        ELSE
            INSERT INTO expiration (
                Product_ID, BatchNum, ExpirationDate, Quantity
            ) VALUES (
                NEW.Product_ID, v_BatchNum, NEW.ExpirationDate, NEW.UnitsOrdered
            );
        END IF;
    END IF;
END




"

?>