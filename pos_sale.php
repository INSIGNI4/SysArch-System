<?php
	include('connect.php');

	function getSaleCustomer($customer_id){
		$conn = $GLOBALS['conn_pos'];
		
		// Prepared statement for mysqli
		$stmt = $conn->prepare("SELECT * FROM customers WHERE Customer_ID = ?");
		$stmt->bind_param("i", $customer_id);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$customer = $result->fetch_assoc();
		
		$stmt->close();
		return $customer;
	}

	function getOrderItems($sales_id)
	{
		$conn = $GLOBALS['conn_pos'];
		
		$stmt = $conn->prepare("SELECT * FROM sales_item WHERE Sales_ID = ?");
		$stmt->bind_param("i", $sales_id);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$rows = $result->fetch_all(MYSQLI_ASSOC);
		
		$stmt->close();
		return $rows;
	}

	function getSale($sale_id)
	{
		$conn = $GLOBALS['conn_pos'];
		
		$stmt = $conn->prepare("SELECT * FROM sales WHERE Sales_ID = ?");
		$stmt->bind_param("i", $sale_id);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$sale = $result->fetch_assoc();
		$stmt->close();

		if (!$sale) {
			return null;
		}

		// Get customer data using Customer_ID
		$customers_data = getSaleCustomer($sale['Customer_ID']);

		// Get order items data
		$items = getOrderItems($sale['Sales_ID']);
		$items_data = [];

		$inv_conn = $GLOBALS['conn'];
		foreach($items as $item){
			$pid = $item['Product_ID'];

			$stmtInv = $inv_conn->prepare("SELECT ProductName FROM product WHERE Product_ID = ?");
			$stmtInv->bind_param("i", $pid);
			$stmtInv->execute();
			
			$resInv = $stmtInv->get_result();
			$product = $resInv->fetch_assoc();
			$stmtInv->close();

			$item_id = $item['SalesItem_ID'];
			$items_data[$item_id] = $item;
			$items_data[$item_id]['ProductName'] = $product['ProductName'] ?? 'Unknown Product';
		}

		return [
			'sales' => $sale,
			'items' => $items_data,
			'customer' => $customers_data
		];
	}
?>

    






<!-- 

this.addByBarcode = function(barcode) {
    // 1. Check if the scanned barcode matches a product ID or product barcode key
    let foundProductId = null;

    // Direct lookup by ID/Barcode key
    if (this.products[barcode]) {
        foundProductId = barcode;
    } else {
        // Fallback search across product records
        for (let pid in this.products) {
            if (this.products[pid].barcode === barcode || pid === barcode) {
                foundProductId = pid;
                break;
            }
        }
    }

    // 2. Handle item addition logic
    if (foundProductId) {
        let productInfo = this.products[foundProductId];

        if (productInfo.stock <= 0) {
            this.dialogError("Product is currently Out of Stock");
            return;
        }

        // Increment item quantity in cart or add new line item
        if (this.orderItems[foundProductId]) {
            if (this.orderItems[foundProductId].qty + 1 > productInfo.stock) {
                this.dialogError("Cannot add more than available stock.");
                return;
            }
            this.orderItems[foundProductId].qty += 1;
        } else {
            this.orderItems[foundProductId] = {
                id: foundProductId,
                name: productInfo.name,
                price: parseFloat(productInfo.price),
                qty: 1
            };
        }

        // 3. Refresh POS table/totals UI
        this.updateOrderItemTable();
    } else {
        // Option B: If barcode isn't loaded in local JS memory, query backend API
        this.fetchAndAddByBarcode(barcode);
    }
};

// Query PHP backend if product barcode is not in client-side memory
this.fetchAndAddByBarcode = function(barcode) {
    $.ajax({
        url: 'pos_live-search.php',
        type: 'GET',
        data: { search_term: barcode, barcode_exact: 1 },
        dataType: 'json',
        success: (response) => {
            if (response.data && response.data.length > 0) {
                let item = response.data[0];
                let pid = item.Product_ID;

                // Sync product into memory
                this.products[pid] = {
                    name: item.ProductName,
                    stock: parseInt(item.CurrentStock) || 0,
                    price: parseFloat(item.StorePrice)
                };

                // Add to order container
                this.addByBarcode(pid);
            } else {
                this.dialogError("Barcode not found: " + barcode);
            }
        }
    });
};	 -->