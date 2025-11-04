<?php

$table_colums_mapping = [
    /// ======= INVENTORY DATABASE ======= ///

    'users' => [
        'id', 'userName', 'email', 'password', 'phone', 'image', 'reset_token_hash', 'reset_token_expires_at','account_activation_hash' 
    ],
    
    'forecast' => [
        'Forecast_ID', 'ForecastType', 'Product_ID', 'ForecastPeriod',
        'ForecastStart', 'ForecastEnd', 'ProjectedSales', 'ConfidenceLevel'
    ],

    'product' => [
        'Product_ID', 'ProductName', 'Type', 'ReordingPoints', 'UnitsOrdered',
        'UnitSold', 'StorePrice', 'SupplierPrice', 'Image', 'Supplier_ID', 'ExpirationDate', 
        'Barcode',
        'LocationS','LocationR'
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
        'Barcode',
         'SalesDate', 'id','BatchNum','userName'
    ],
    'transactions' => [
        'Transaction_ID', 'Customer_ID', 'ReferenceNo', 'PurchaseType', 'PaymentMethod', 'ServiceType',
        'Transaction_Date','Total_Price'
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
        'Supplier_ID', 'Status', 'Image', 'DeliveryStatus','Date_Received'
        ,'TotalReceived','withIssue','ExpirationDate'
    ],


    
    // /// ======= FORECASTING & ANALYTICS DATABASE ======= ///



    'daily_forecast' => [
        'DailyForecast_ID', 'ForecastType', 'ProductScope', 'ForecastPeriod',
        'ForecastStart', 'ForecastEnd', 'ProjectedSales', 'ConfidenceLevel', 'Account_ID'
    ],
    'weekly_forecast' => [
        'WeeklyForecast_ID', 'ForecastType', 'ProductScope', 'ForecastPeriod',
        'ForecastStart', 'ForecastEnd', 'ProjectedSales', 'ConfidenceLevel', 'Account_ID'
    ],
    'monthly_forecast' => [
        'MonthlyForecast_ID', 'ForecastType', 'ProductScope', 'ForecastPeriod',
        'ForecastStart', 'ForecastEnd', 'ProjectedSales', 'ConfidenceLevel', 'Account_ID'
    ],
    
    'analytics' => [
        'AnalyticsID', 'Forecast_ID', 'Forecast_Type','Report_Date', 'ProductScope', 'PeriodType', 'SalesMetrics',
        'Inventory_ID', 'Account_ID'
    ],
    
    
    // 'salesaggregration' => [
    //     'Aggregation_ID', 'PeriodType', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    // ],





    'daily_sales' => [
        'DailySales_ID ', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    ],

    'weekly_sales' => [
        'WeeklySales_ID ', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    ],

    'monthly_sales' => [
        'MonthlySales_ID ', 'PeriodStart', 'PeriodEnd', 'Product_ID', 'TotalSales', 'TotalQuantity'
    ],



    'daily_total_sales' => [
        'EntireDailySales_ID ', 'PeriodStart', 'PeriodEnd', 'TotalSales', 'TotalQuantity'
    ],

    'weekly_total_sales' => [
        'EntireWeeklySales_ID ', 'PeriodStart', 'PeriodEnd', 'TotalSales', 'TotalQuantity'
    ],

    'monthly_total_sales' => [
        'EntireMonthlySales_ID ', 'PeriodStart', 'PeriodEnd', 'TotalSales', 'TotalQuantity'
    ],

    




];


