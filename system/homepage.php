<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobo 2 Celle - Inventory Management System</title>
    <link rel="stylesheet" href="homepagestyles.css">
</head>
<body>
    <div id="dashboard-container" class="dashboard-container" >
        <div class="sidebar">
            <div class="logo"><img src="https://i.imgur.com/80i3q58.png" alt="Lobo 2 Celle Logo"></div>
            <div class="sidebar-nav">
                <a class="nav-link" onclick="showContentSection('home')">Home</a>
                <a class="nav-link" onclick="showContentSection('inventory')">Inventory</a>
                <a class="nav-link" onclick="showContentSection('new-additions')">New Additions</a>
                <a class="nav-link" onclick="showContentSection('products')">Products</a>
                <a class="nav-link" onclick="showContentSection('suppliers')">Suppliers</a>
                <a class="nav-link" onclick="showContentSection('customer')">Customer</a>
                <a class="nav-link" onclick="showContentSection('returns')">Returns</a>
                <a class="nav-link" onclick="showContentSection('order-restock')">Order & Restock</a>
                <a class="nav-link" onclick="showContentSection('transaction-sales')">Transaction & Sales</a>
                <a class="nav-link" onclick="showContentSection('sales-aggregation')">Sales Aggregation</a>
                <a class="nav-link" onclick="showContentSection('forecast-analytics')">Forecast & Analytics</a>
                <a class="nav-link" onclick="showContentSection('stock-adjustments')">Stock Adjustments</a>
                <a class="nav-link" onclick="showContentSection('account')">Account</a>
            </div>
            <div class="sidebar-footer">
                <a class="footer-link" onclick="showContentSection('settings')"><span>⚙️</span> Settings</a>
                <a href="logout.php" class="footer-link" ><span>↪</span> Log Out</a>
            </div>
        </div>

        <!-- NEW: Sidebar Toggle Button -->
        <div id="sidebar-toggle">«</div>

        <div class="main-content">
            <div class="content-area">
                <!-- Each section now contains its own header -->
                <div id="home" class="content-section">
                    <div class="custom-header">
                         <div class="top-bar"><div class="tab">HOME</div>
                            <div class="user-controls">
                                <div>Manager 1</div><div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2>Welcome Back!</h2><img src="https://i.imgur.com/b5R4i7V.png" class="page-image">
                </div>
                
<script src="homepagescript.js"></script>
</body>
</html>