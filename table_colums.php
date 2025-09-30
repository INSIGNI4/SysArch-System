<?php

$table_colums_mapping = [
    /// ======= INVENTORY DATABASE ======= ///
    'product' => [
        'Product_ID', 'ProductName', 'Type', 'ReordingPoints', 'UnitsOrdered',
        'UnitSold', 'StorePrice', 'SupplierPrice', 'Image', 'Supplier_ID', 'ExpirationDate', 'Barcode'
    ],
    'inventory' => [
        'Product_ID', 'LocationS','LocationR', 'Price', 'Inventory', 'UnitIN',
        'UnitOut', 'Status', 'ExpirationDate','Barcode','Supplier_ID'
    ],
    'newaddition' => [
        'Inventory_ID','Product_ID', 'Quantity', 'Date_Added',
        'Expiration_Date', 'Status', 'Supplier_ID','LocationS','LocationR'
    ],
    'sales' => [
        'Order_ID', 'Transaction_ID', 'Product_ID','ProductName','Unit_Price', 'Quantity', 'TotalPrice',
        'Barcode', 'SalesDate', 'Account_ID'
    ],
    'transactions' => [
        'Transaction_ID', 'Customer_ID', 'ReferenceNo', 'PurchaseType', 'PaymentMethod', 'ServiceType','Transaction_Date'
    ],
    'customers' => [
        'Customer_ID', 'CustomerName', 'Location', 'Email', 'PhoneNumber'
    ],
    'customersreturns' => [
        'CReturn_ID', 'ReferenceNo', 'Quantity', 'ReturnedDate', 'ReasonForReturn', 'Customer_ID',
        'Product_ID' 
    ],
    'supplier' => [
        'Supplier_ID', 'SupplierName', 'Location', 'Email', 'PhoneNumber', 'OfferedProductsType' 
    ],
    'supplierreturns' => [
        'SReturns_ID', 'Supplier_ID', 'Product_ID', 'Quantity', 'ReturnedDate', 'Status', 'Reason'
    ],
    'pulledoutitems' => [
        'Pulled_ID', 'Product_ID', 'Supplier_ID', 'Quantity', 'Reason', 'PulledDate'
    ],
    'restock' => [
        'Orestock_ID', 'Type', 'Quantity', 'OrderDate', 'Product_ID',
        'Supplier_ID', 'Status', 'Image', 'DeliveryStatus'
    ],

    
    // /// ======= FORECASTING & ANALYTICS DATABASE ======= ///
    'forecast' => [
        'Forecast_ID', 'ForecastType', 'ProductScope', 'ForecastPeriod',
        'ForecastStart', 'ForecastEnd', 'ProjectedSales', 'ConfidenceLevel', 'Account_ID'
    ],
    'analytics' => [
        'AnalyticsID', 'Forecast_ID', 'Forecast_Type','Report_Date', 'ProductScope', 'PeriodType', 'SalesMetrics',
        'Inventory_ID', 'Account_ID'
    ],
    'salesaggregration' => [
        'Aggregation_ID', 'PeriodType', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    ],

    'daily_sales' => [
        'DailySales_ID ', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    ],

    'weekly_sales' => [
        'WeeklySales_ID ', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    ],

    'monthly_sales' => [
        'MonthlySales_ID ', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    ],




];


