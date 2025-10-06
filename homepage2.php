<?php
session_start();

if (isset($_SESSION['response'])) {
    echo "<script>alert('" . $_SESSION['response']['message'] . "');</script>";
    unset($_SESSION['response']);



}




// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";


include('show_data.php');

$product = fetchTableData($conn, 'product');
$supplier = fetchTableData($conn, 'supplier');
$customers = fetchTableData($conn, 'customers');
$restock = fetchTableData($conn, 'restock');
$customersreturns = fetchTableData($conn, 'customersreturns');
$supplierreturns = fetchTableData($conn, 'supplierreturns');
$sales = fetchTableData($conn, 'sales');
$transaction = fetchTableData($conn, 'transactions');

$pulledoutitems = fetchTableData($conn, 'pulledoutitems');
$salesaggregration = fetchTableData($conn, 'salesaggregration');
$newaddition = fetchTableData($conn, 'newaddition');
$inventory = fetchTableData($conn, 'inventory');

$dailysalesP = fetchTableData($conn, 'daily_sales');
$weeklysalesP = fetchTableData($conn, 'weekly_sales');
$monthlysalesP = fetchTableData($conn, 'monthly_sales');

$dailyforecast = fetchTableData($conn, 'daily_forecast');
$weeklyforecast = fetchTableData($conn, 'weekly_forecast');
$monthlyforecast = fetchTableData($conn, 'monthly_forecast');

$dailysalesE = fetchTableData($conn, 'daily_total_sales');
$weeklysalesE = fetchTableData($conn, 'weekly_total_sales');
$monthlysalesE = fetchTableData($conn, 'monthly_total_sales');





$analytics = fetchTableData($conn, 'analytics');

// $users = fetchTableData($conn, 'users');

if (!is_array($product)) {
    $product = [];  
}

if (!is_array($supplier)) {
    $supplier = [];  
}

if (!is_array($customers)) {
    $customers = [];  
}

if (!is_array($restock)) {
    $restock = [];  
}

if (!is_array($customersreturns)) {
    $customersreturns = [];  
}

if (!is_array($supplierreturns)) {
    $supplierreturns = [];  
}

if (!is_array($sales)) {
    $sales = [];  
}

if (!is_array($transaction)) {
    $transaction = [];  
}

if (!is_array($dailyforecast)) {
    $dailyforecast = [];  
}
if (!is_array($weeklyforecast)) {
    $weeklyforecast = [];  
}
if (!is_array($monthlyforecast)) {
    $monthlyforecast = [];  
}


if (!is_array($analytics)) {
    $analytics = [];  
}

if (!is_array($pulledoutitems)) {
    $pulledoutitems = [];  
}

if (!is_array($salesaggregration)) {
    $salesaggregration = [];  
}

if (!is_array($newaddition)) {
    $newaddition = [];  
}

if (!is_array($inventory)) {
    $inventory = [];  
}

if (!is_array($dailysalesP)) {
    $dailysalesP = [];  
}

if (!is_array($weeklysalesP)) {
    $weeklysalesP = [];  
}

if (!is_array($monthlysalesP)) {
    $monthlysalesP = [];  
}


if (!is_array($dailysalesE)) {
    $dailysalesE = [];  
}

if (!is_array($weeklysalesE)) {
    $weeklysalesE = [];  
}

if (!is_array($monthlysalesE)) {
    $monthlysalesE = [];  
}





if(isset($_SESSION['email'])){
    $email=$_SESSION['email'];
    $query=mysqli_query($conn, "SELECT users.* FROM users WHERE users.email='$email'");

    $row=mysqli_fetch_array($query);
    
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobo 2 Celle - Inventory Management System</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="homepagestyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">



</head>
<body>





    <!-- ======================= MAIN DASHBOARD ======================= -->
    <div id="dashboard-container" class="dashboard-container" >
    
        <div class="sidebar"  id="sidebar">
            
            <img src="sidebarlogo.png" alt="Lobo 2 Celle Logo" style="height: 140px; width: 235px; padding: 0rem 0rem 0.6rem;">
            
            <div class="sidebar-nav" >
                <a class="nav-link" onclick="showContentSection('home')">Home</a>
                
                <a class="nav-link" onclick="showContentSection('inventory')">Inventory</a>
                <a class="nav-link" onclick="showContentSection('new-additions')">New Additions</a>
                <a class="nav-link" onclick="showContentSection('products')">Products</a>
                <a class="nav-link" onclick="showContentSection('suppliers')">Suppliers</a>
                <a class="nav-link" onclick="showContentSection('customer')">Customer</a>
                <a class="nav-link has-dropdown" onclick="showContentSection('returns')">Returns</a>

                <a class="nav-link" onclick="showContentSection('order-restock')">Order & Restock</a>
                <a class="nav-link has-dropdown" onclick="showContentSection('transaction-sales')">Transaction & Sales</a>

                <a class="nav-link" onclick="showContentSection('sales-aggregation')">Sales Aggregation</a>
                <a class="nav-link has-dropdown" onclick="showContentSection('forecast-analytics')">Forecast & Analytics</a>

                <a class="nav-link" onclick="showContentSection('stock-adjustments')">Stock Adjustments</a>
                <a class="nav-link" onclick="showContentSection('account')">Account</a>
            </div>
            <div class="sidebar-footer">
                <a class="footer-link" onclick="showContentSection('settings')"><span>⚙️</span> Settings</a>
                <a href="logout.php" class="footer-link" ><span>↪</span> Log Out</a>
            </div>
        </div>

        <!-- NEW: Sidebar Toggle Button -->
        <div id="sidebar-toggle">«
        </div> <!-- end of #dashboard-container -->

        

        <div class="main-content">
            <div class="content-area" style="background:linear-gradient(to top,rgba(70, 70, 70, 1),rgba(255, 252, 252, 1));">
                <link rel="stylesheet" href="assets/dashboard.css">
                <link rel="stylesheet" href="dashboardAssets/analytics_dh.css">
                <link rel="stylesheet" href="dashboardAssets/forecast_dh.css">
                <link rel="stylesheet" href="dashboardAssets/topcustomer_dh.css">
                <link rel="stylesheet" href="dashboardAssets/summary.css">
                
                <div id="home" class="content-section" style=" margin: -25px;">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat; background-size: cover; height: 100px;">
                        <div class="top-bar">
                            <div class="tab">HOME</div>
                            <div class="user-controls">
                                <div>
                                <?php         

                                        echo $row['userName'];
                            
                                ?></div>
                                <div class="user-icon">👤</div>
                            </div>
                        </div>
                        <div class="home-header" style="display: flex; justify-content:space-between;font-weight:bolder;">
                            <div style="display: flex;">
                            <p style="font-display: none; margin: 20px; font-size:20px;">Welcome Back!                                 
                            </p>
                            <p style="font-display: none; margin: 13px; margin-left: -5px; font-size:24px; font-weight:bolder;color:beige;">
                            <?php
                                echo $row['userName'];
                            ?>
                            </p>
                            </div>
                            <div class="header-icons" style="margin-top: 10px; margin-right: 1rem;">
                                <span class="icon-wrapper">🖨️</span>
                                <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                            </div>
                        </div>
                    </div>

                        <div class="parent_dashboard">
                            <!-- ANALYTICS DASHBOARD JS -->
                            <script src="https://code.highcharts.com/highcharts.js"></script>
                            <script src="https://code.highcharts.com/modules/exporting.js"></script>
                            <script src="https://code.highcharts.com/modules/export-data.js"></script>
                            <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                            <script src="https://code.highcharts.com/themes/adaptive.js"></script>

                            <div class="analytics_dashboard">
                                <figure class="highcharts-figure">
                                    <div id="container"></div>
                                </figure>
                            </div>

                            <!-- FORECAST DASHBOARD JS -->
                            <script src="https://code.highcharts.com/highcharts.js"></script>
                            <script src="https://code.highcharts.com/modules/data.js"></script>
                            <script src="https://code.highcharts.com/modules/series-label.js"></script>
                            <script src="https://code.highcharts.com/modules/exporting.js"></script>
                            <script src="https://code.highcharts.com/modules/export-data.js"></script>
                            <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                            <script src="https://code.highcharts.com/themes/adaptive.js"></script>

                            <div class="forecast_dashboard">
                                <figure class="highcharts-figure">
                                    <div id="container1"></div>
                                </figure>
                            </div>

                           <div class="topcustomer_dashboard">
                                <div class="topcustomer_header">
                                    <h3>Top Customer <br><span>(Week)</span></h3>
                                </div>
                                <table class="topcustomer_table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Order</th>
                                        </tr>   
                                    </thead>
                                    <tbody id="topcustomer-body">
                                    </tbody>
                                </table>
                            </div>


                            <div class="return_dashboard">Return Summary</div>
                            <div class="products_dashboard">Products</div>
                            <div class="confidence_dashboard">Confidence Level</div>
                            <div class="projected_dashboard">Projected Sales</div>

                            <div class="totalproduct_dashboard card">
                                <div class="card-title">Total Products</div>
                                <div class="card-value" id="total-products">0</div>
                                <div class="card-footer" id="update-products">Update --</div>
                            </div>

                            <div class="growth_dashboard card">
                                <div class="card-title">Growth Rate</div>
                                <div class="card-value" id="growth-rate">0%</div>
                                <div class="card-footer" id="update-growth">Update --</div>
                                </div>

                            <div class="totalsold_dashboard card">
                                <div class="card-title">Total Sold</div>
                                <div class="card-value" id="total-sold">0</div>
                                <div class="card-footer" id="update-sold">Update --</div>
                            </div>

                            <div class="totalcustomer_dashboard card">
                                <div class="card-title">Total Customers</div>
                                <div class="card-value" id="total-customers">0</div>
                                <div class="card-footer" id="update-customers">Update --</div>
                            </div>


                            <div class="stock_dashboard">
                                <h2>Stock Availability</h2>
                                <div id="total-stock"></div>
                                <canvas id="low-stock-list"></canvas>
                            </div>

                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        
                        <script src="dashboardAssets/stock_avail.js"></script>
                        <script src="dashboardAssets/summary.js"></script>
                        <script src="dashboardAssets/forecast_dh.js"></script>
                        <script src="dashboardAssets/analytics_dh.js"></script>
                        <script src="dashboardAssets/topcustomer_dh.js"></script>



                </div>

                <div id="inventory" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover; height: 100px;">
                         <div class="top-bar">
                            <div class="tab">INVENTORY</div>
                            <div class="user-controls">
                                <div>
                                <?php
                                    echo $row['userName'];
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <!-- <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div> -->
                            </div>
                        </div>
                        <div class="controls-bar">
                            <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select><option></option></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option></option></select></div>
                                <button class="status-button">STATUS</button>
                                <!-- <div class="stock-info"><span>30</span><span class="in-stock-label">In Stock</span></div> -->
                            </div>

                            <div class="header-icons">
                                <span class="icon-wrapper">🖨️</span>
                                <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                            </div>

                        </div>

                    </div>

                    <div class="toolbar" style="display: flex; justify-content:space-between;">
                        <div>
                            <div class="stock-info" style="background-color: transparent;">
                                <span></span>
                                <span class="in-stock-label">In Stock</span>
                            </div>
                        </div>
                        <div style="display: flex; justify-content:space-between;" >
                            <div class="center-controls"><button class="last-updated-button">🔄 Last Updated</button></div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="inventory-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product ID</th>
                                <th colspan="2">Location</th>
                                <th>Price</th>
                                <th>Inventory</th>
                                <th>Unit In</th>
                                <th>Unit Out</th>
                                <th>Status</th>
                                <th>Expiration Date</th>
                                <th>BarCode</th>
                                <th>Supplier ID</th>
                                
                            </tr>
                            </thead>
                            <tbody>

                                <?php foreach($inventory as $index => $inventory){ ?>
                                    <tr>
                                    <td><?= $index + 1?></td>
                                    <td class="product-id-cell"><?= $inventory['Product_ID']?></td>
                                    <td><span class="Location-tag shelf"><?= $inventory['LocationS']?></span></td>
                                    <td><span class="Location-tag row"><?= $inventory['LocationR']?></span></td>
                                    <td><?= $inventory['Price']?></td>
                                    <td><?= $inventory['Inventory']?></td>
                                    <td><?= date('F d, Y h:i A', strtotime($inventory['UnitIN']))?></td>
                                    <!-- <td><?= date('F d, Y h:i A', strtotime($inventory['UnitOut']))?></td> -->
                                    <td style="color: red;"><?= empty($inventory['UnitOut']) ? '- - - N/A - - -' : date('F d, Y h:i A', strtotime($inventory['UnitOut'])) ?></td>
                                    <!-- <td><?= $inventory['Status']?></td> -->
                                    <td><span class="inventory-status status-<?= strtolower($inventory['Status']) ?>">
                                                                        <?= htmlspecialchars($inventory['Status']) ?>
                                        </span>
                            
                                    </td>
                                    <td style="color: red;"><?= empty($inventory['ExpirationDate']) ? '- - - N/A - - -' : date('F d, Y', strtotime($inventory['ExpirationDate'])) ?></td>
                                    <td><?= $inventory['Barcode']?></td>
                                    <td class="supplier-id-cell"><?= $inventory['Supplier_ID']?></td>
                                    

                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="new-additions" class="content-section" >
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">NEW ADDITIONS</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                    <div class="user-icon">👤</div>
                            </div>
                        </div>
                        <div class="controls-bar">
                            <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>DATE ADDED</option></select></div>
                            </div>
                            <div class="header-icons">
                                    <span>🖨️</span>
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                            </div>

                        </div>
                    </div>
                    <div class="toolbar" style="display: flex; justify-content:space-between">
                        <div>
                        <input type="checkbox">
                        <span id="na-count" class="na-count">30</span>
                        <span class="na-icon-btn">📝</span>
                        <span class="na-icon-btn">🗑️</span>
                        <span class="na-icon-btn">🖨️</span>
                        <button id="na-add-btn" class="na-btn na-btn-add">ADD</button>
                        </div>

                        <div class="actions" style="display: flex; align-items: space-between; gap: 35px;">
                                <button class="na-btn na-btn-import">IMPORT</button>
                                <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                        <div id="addNewAdditionModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Addition</h3>
                                <form id="newadditionForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="newaddition">

                                    <!-- <label>Supplier ID:</label>
                                    <input type="text" name="Supplier_ID" required> -->

                                    <!-- <label>Product ID:</label>
                                    <input type="text" name="Product_ID" required> -->

                                    <label for="Product_ID">Choose Product ID:</label>
                                    <select id="Product_ID" name="Product_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label>Quantity:</label>
                                    <input type="number" name="Quantity" required>

                                    <label>Date Added:</label>
                                    <input type="datetime-local" name="Date_Added" required>


                                    <label>Status:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                    <select class="newaddition-status-select" name="Status" required>
                                        <option value="New">New</option>
                                        <option value="Returned">Returned</option>
                                    </select>
                                    
                                    </div>

                                    <!-- <label>Supplier ID:</label>
                                    <input type="text" name="Supplier_ID" required> -->

                                    <label for="Supplier_ID">Choose Supplier ID:</label>
                                    <select id="Supplier_ID" name="Supplier_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>


                                    <!-- <label for="Product_ID">Choose Product ID:</label>
                                    <select id="Product_ID" name="Product_ID" class="shelf-row-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select> -->

                                    <label>Location:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                    <label>Shelf:</label>
                                    <select class="shelf-row-select" name="LocationS" required>
                                        <option value="Shelf A">Shelf A</option>
                                        <option value="Shelf B">Shelf B</option>
                                        <option value="Shelf C">Shelf C</option>
                                        <option value="Shelf D">Shelf D</option>
                                    </select>
                                    
                                    <!-- <input type="text" name="LocationS" required> -->

                                    <label>Row:</label>

                                    <select class="shelf-row-select" name="LocationR" required>
                                        <option value="Row A">Row A</option>
                                        <option value="Row B">Row B</option>
                                        <option value="Row C">Row C</option>
                                        <option value="Row D">Row D</option>
                                    </select>
                                    <!-- <input type="text" name="LocationR" required> -->
                                    </div>
                                    
                                    

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="newadd-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_newaddition.js" defer></script>
                        <script src="get/get_product.js" defer></script>
                        <script src="get/get_supplier.js" defer></script>
                        


                        
                    </div>
                    <div class="table-container na-table-container">
                        <table class="new-additions-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Inventory ID</th>
                                    <th>Product ID</th>
                                    <th>Quantity</th>
                                    <th>Date Added</th>
                                    <!-- <th>Expiration Date</th> -->
                                    <th>Status</th>
                                    <th>Supplier ID</th>
                                    <th colspan="2">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($newaddition as $index => $newaddition){ ?>
                                    <tr>
                                    <td><?= $index + 1?></td>
                                    <td class="inventory-id-cell"><?= $newaddition['Inventory_ID']?></td>
                                    <td class="product-id-cell"><?= $newaddition['Product_ID']?></td>
                                    <td><?= $newaddition['Quantity']?></td>
                                    <td><?= date('F d,Y h:i A', strtotime($newaddition['Date_Added']))?></td>
                                    
                                    
                                    <!-- <td style="color: red;"><?= empty($newaddition['Expiration_Date']) ? '- - - N/A - - -' : date('F d, Y', strtotime($newaddition['Expiration_Date'])) ?></td> -->
                                    <!-- <td><?= date('d m Y', strtotime($newaddition['Expiration_Date']))?></td> -->
                                    <!-- <td><?= $newaddition['Status']?></td> -->

                                    <td><span class="newaddition-status na-status-<?= strtolower($newaddition['Status']) ?>">
                                                                        <?= htmlspecialchars($newaddition['Status']) ?>
                                        </span>
                                            
                                    </td>
                                    <td class="supplier-id-cell"><?= $newaddition['Supplier_ID']?></td>
                                    <td><span class="Location-tag shelf"><?= $newaddition['LocationS']?></span></td>
                                    <td><span class="Location-tag row"><?= $newaddition['LocationR']?></span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                

                <!--- PRODUCTS SECTION --->
                <div id="products" class="content-section" >
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">PRODUCTS</div>
                            <div class="user-controls">
                                <div>
                                <?php
                                    echo $row['userName'];
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span>🖨️</span>
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                            <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>PRODUCT ID</option></select></div>
                            </div>
                            <div class="actions">
                                <!-- <div><b>Total Units :</b></div> -->
                                <div class="na-quick-search">🔍 Quick Search</div>
                            </div>
                        </div>
                    </div>
                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="products-count" class="na-count">21</span>
                        <button id="edit-product-btn" class="na-btn na-btn-add1" type="button">📝</button>
                        <button id="delete-product-btn" class="na-btn na-btn-add1" type="button">🗑️</button>
                        <!-- <span class="na-icon-btn">📝</span>
                        <span class="na-icon-btn">🗑️</span> -->
                        <span class="na-icon-btn">🖨️</span>
                        <button id="products-add-btn" class="na-btn na-btn-add" type="button">ADD</button>
                        <div id="addProductModal" class="modal">
                            <div class="modal-content">
                                <h3>Add New Product</h3>
                                <form id="productForm" action="add.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="table" value="product">
                                    <!-- <label>Product ID:</label>
                                    <input type="text" name="Product_ID" required> -->

                                    <label>Product Name:</label>
                                    <input type="text" name="ProductName" required>

                                    <!-- <label>Type:</label>
                                    <input type="text" name="Type" required> -->


                                    <label>Type:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                
                                    <select class="type-select" name="Type" required>
                                        <option value="NONE">...</option>
                                        <option value="Exhaust">Exhaust</option>
                                        <option value="Tires">Tires</option>
                                        <option value="Brakes">Brakes</option>
                                        <option value="Stand">Stand</option>
                                        <option value="Forks">Forks</option>
                                        <option value="Rims">Rims</option>
                                        <option value="Mirror">Mirror</option>
                                        <option value="Suspension">Suspension</option>
                                        <option value="Box">Box</option>
                                        <option value="Oil">Oil</option>
                                    </select>
                                    </div>

                                    <!-- <label>Reorder Points:</label>
                                    <input type="number" name="ReordingPoints" required> -->

                                    <!-- <label>Ordered:</label>
                                    <input type="number" name="UnitsOrdered" required> -->

                                    <!-- <label>Sold:</label>
                                    <input type="number" name="UnitSold"> -->

                                    <label>Store Price (PHP):</label>
                                    <input type="number" name="StorePrice" required>

                                    <label>Supplier Price (PHP):</label>
                                    <input type="number" name="SupplierPrice" required>

                                    <label>Image:</label>
                                    <!-- <input type="file" id="profile-upload" accept="image/*" style="display: none;"> -->
                                    <input type="file" accept="image/*" name="Image">

                                    <!-- <label>Supplier ID:</label>
                                    <input type="text" name="Supplier_ID" required> -->

                                    <label for="Supplier_ID">Choose Supplier ID:</label>
                                    <select id="Supplier_IDPROD" name="Supplier_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label>Expiration Date:
                                        <button type="button" id="toggleNullBtn">NULL?</button>
                                        <button type="button" id="toggleNotNullBtn" style="display: none;">NOT NULL?</button>
                                    </label>
                                    <input type="date" name="ExpirationDate" id="expiration_date">

                                    <!-- <label>Expiration Date:</label>
                                    <input type="date" name="ExpirationDate"> -->

                                    <label>Barcode:</label>
                                    <input type="text" name="Barcode" maxlength="11" required>

                                    

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <button type="button" id="cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_product.js" defer></script>
                        <script src="get/get_supplierPROD.js" defer></script>

                    

                        
                        <div id="editProductModal" class="modal">
                            <div class="modal-content">
                                <h3>Edit Product</h3>
                                <form action="update.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="table" value="product">
                                    <input type="hidden" name="id" id="edit-id">
                                    
                                    <label>Product Name:</label>
                                    <input type="text" name="ProductName" id="edit-name">

                                    <!-- <label>Type:</label>
                                    <input type="text" name="Type" id="edit-type"> -->

                                    <label>Type:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                    <select class="type-select" name="Type"  id="edit-type" required>
                                        <option value="">...</option>
                                        <option value="Exhaust">Exhaust</option>
                                        <option value="Tires">Tires</option>
                                        <option value="Brakes">Brakes</option>
                                        <option value="Stand">Stand</option>
                                        <option value="Forks">Forks</option>
                                        <option value="Rims">Rims</option>
                                        <option value="Mirror">Mirror</option>
                                        <option value="Suspension">Suspension</option>
                                        <option value="Box">Box</option>
                                        <option value="Oil">Oil</option>
                                    </select>
                                    </div>

                                    <!-- <label>Reorder Points:</label>
                                    <input type="number" name="ReordingPoints" id="edit-reorder"> -->

                                    <!-- <label>Ordered:</label>
                                    <input type="number" name="UnitsOrdered" id="edit-unitsordered"> -->

                                    <!-- <label>Sold:</label>
                                    <input type="number" name="UnitSold" id="edit-sold"> -->

                                    <label>Store Price (PHP):</label>
                                    <input type="number" name="StorePrice" id="edit-storeprice">

                                    <label>Supplier Price (PHP):</label>
                                    <input type="number" name="SupplierPrice" id="edit-supplierprice">

                                    <label>Image:</label>
                                    <!-- <input type="file" id="profile-upload" accept="image/*" style="display: none;"> -->
                                    <input type="file" accept="image/*" name="Image" id="edit-image">

                                    <!-- <label>Supplier ID:</label>
                                    <input type="text" name="Supplier_ID" id="edit-supplierid"> -->

                                    <label for="Supplier_ID">Choose Supplier ID:</label>
                                    <select id="edit-supplierid" name="Supplier_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <!-- <label>Expiration Date:</label>
                                    <input type="date" name="ExpirationDate" id="edit-expirationdate"> -->

                                    <label>Expiration Date:
                                        <button type="button" id="toggleNullBtn1">NULL?</button>
                                        <button type="button" id="toggleNotNullBtn1" style="display: none;">NOT NULL?</button>
                                    </label>
                                    <input type="date" name="ExpirationDate" id="edit-expirationdate"required>

                                    <label>Barcode:</label>
                                    <input type="text" name="Barcode" id="edit-barcode" maxlength="11" required>

                                    
                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <button type="button" id="editprod-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                            <link rel="stylesheet" href="assets/add_product.css">
                            <script src="assets/edit_product.js"></script>
                            <script src="get/edit_supplierPROD.js"></script>
                        </div>

                    </div>
                
                    <div class="table-container products-table-container">
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Type</th>
                                    <th>Reorder Points</th>
                                    <th>Ordered</th>
                                    <th>Sold</th>
                                    <th>Price</th>
                                    <th>Supplier Price</th>
                                    <th>Image</th>
                                    <th>Supplier ID</th>
                                    <th>Expiration Date</th>
                                    <th>Barcode</th>
                                    <th>       </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($product as $index => $products){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td class="product-id-cell"><?= $products['Product_ID']?></td>
                                        <td><?= $products['ProductName']?></td>
                                        <td><span class="type-tag-purple"><?= $products['Type']?></span></td>
                                        <td><?= $products['ReordingPoints']?></td>
                                        <td class="units-ordered"><?= $products['UnitsOrdered']?></td>
                                        <td class="units-sold"><?= $products['UnitSold']?></td>
                                        <td><?= $products['StorePrice']?></td>
                                        <td><?= $products['SupplierPrice']?></td>
                                        <td>
                                            <?php if (!empty($products['Image']) && file_exists("uploads/" . $products['Image'])): ?>
                                                <a href="uploads/<?= $products['Image']?>" target="_blank">
                                                    <img src="uploads/<?= $products['Image']?>" alt="Product Image" style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                                </a>
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                        </td>
                                        <td class="supplier-id-cell"><?= $products['Supplier_ID']?></td>
                                        <td style="color: red;"><?= empty($products['ExpirationDate']) ? '- - - N/A - - -' : date('F d, Y', strtotime($products['ExpirationDate'])) ?></td>
                                        <!-- <td><?= date('F d,Y', strtotime($products['ExpirationDate']))?></td> -->
                                        <td><?= $products['Barcode']?></td>
                                    
                                            <!-- data-reorder="<?= $products['ReordingPoints'] ?>" -->
                                             <!-- data-unitsordered="<?= $products['UnitsOrdered'] ?>" -->
                                        <td class="action-cell" >
                                            <!-- edit & delete action -->
                                            <button style="display: none;" class="select-product-btn"
                                                data-id="<?= $products['Product_ID'] ?>"
                                                data-name="<?= $products['ProductName'] ?>"
                                                data-type="<?= $products['Type'] ?>"
                                                
                                                
                                                data-unitsold="<?= $products['UnitSold'] ?>"
                                                data-storeprice="<?= $products['StorePrice'] ?>"
                                                data-supplierprice="<?= $products['SupplierPrice'] ?>"
                                                data-barcode="<?= $products['Barcode'] ?>"
                                                data-expirationdate="<?= $products['ExpirationDate'] ?>"
                                                data-supplierid="<?= $products['Supplier_ID'] ?>">
                                                
                                                
                                                📝 Edit
                                            </button>

                                            <form style="display: none;" id="display-delete" action="delete.php" class="delete-product-form" method="POST" style="display: inline;">
                                                <input type="hidden" name="table" value="product">
                                                <input type="hidden" name="id" value="<?= $products['Product_ID']?>">
                                                <button type="submit"  onclick="return confirm('Delete this product?')">🗑️ Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="suppliers" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">SUPPLIERS</div>
                            <div class="user-controls">
                                <div>
                                <?php
                                    echo $row['userName'];
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span>🖨️</span>
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                            <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>SUPPLIER ID</option></select></div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="suppliers-count" class="na-count">13</span>
                        <span class="na-icon-btn">📝</span>
                        <span class="na-icon-btn">🗑️</span>
                        <span class="na-icon-btn">🖨️</span>

                        <button id="sup-add-btn" class="na-btn na-btn-add" type="button">ADD</button>
                                   
                        <div id="addSupplierModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Supplier</h3>
                                <form id="supplierForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="supplier">

                                    <!-- <label>Supplier ID:</label>
                                    <input type="text" name="Supplier_ID" required> -->

                                    <label>Supplier Name:</label>
                                    <input type="text" name="SupplierName" required>

                                    <label>Location:</label>
                                    <input type="text" name="Location" required>

                                    <!-- <label>Location:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                
                                    <select class="type-select" name="Location" required>
                                        <option value="NONE">...</option>
                                        <option value="Exhaust">Exhaust</option>
                                        <option value="Tires">Tires</option>
                                        <option value="Brakes">Brakes</option>
                                        <option value="Stand">Stand</option>
                                        <option value="Forks">Forks</option>
                                        <option value="Rims">Rims</option>
                                        <option value="Mirror">Mirror</option>
                                        <option value="Suspension">Suspension</option>
                                        <option value="Box">Box</option>
                                        <option value="Oil">Oil</option>
                                    </select>
                                    </div> -->



                                    <label>Email:</label>
                                    <input type="text" name="Email" required>

                                    <label>Phone Number:</label>
                                    <input type="number" name="PhoneNumber" required>

                                    <!-- <label>Offered Products:</label>
                                    <input type="text" name="OfferedProductsType" required> -->
                                    <label>Offered Products::</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">                   
                                    
                                    <select class="shelf-row-select" name="OfferedProductsType" required>
                                        <option value="">...</option>
                                        <option value="Exhaust">Exhaust</option>
                                        <option value="Tires">Tires</option>
                                        <option value="Brakes">Brakes</option>
                                        <option value="Stand">Stand</option>
                                        <option value="Forks">Forks</option>
                                        <option value="Rims">Rims</option>
                                        <option value="Mirror">Mirror</option>
                                        <option value="Suspension">Suspension</option>
                                        <option value="Box">Box</option>
                                        <option value="Oil">Oil</option>
                                    </select>
                                    <!-- <input type="text" name="LocationR" required> -->
                                    </div>

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="sup-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_supplier2.js" defer></script>
                    </div>

                    <div class="table-container suppliers-table-container">
                        <table class="suppliers-table">
                           <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Supplier ID</th>
                                    <th>Supplier Name</th>
                                    <th>LOCATION</th>
                                    <!-- <th colspan="4">Location</th> -->
                                    <th>Offered Products TYPE</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($supplier as $index => $supplier){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td class="supplier-id-cell"><?= $supplier['Supplier_ID']?></td>
                                        <td><?= $supplier['SupplierName']?></td>
                                        <td><?= $supplier['Location']?></td>
                                        <td><span class="type-tag-purple"><?= $supplier['OfferedProductsType']?></span></td>
                                        <td><a href="#" class="email-link"><?= $supplier['Email']?></a></td>
                                        <td><?= $supplier['PhoneNumber']?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="customer" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">CUSTOMERS</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                             <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>CUSTOMER ID</option></select></div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="customer-count" class="na-count">13</span>
                        <span class="na-icon-btn">📝</span><span class="na-icon-btn">🗑️</span><span class="na-icon-btn">🖨️</span>
                        
                        <button id="customer-add-btn" class="na-btn na-btn-add">ADD</button>
                        
                        <div id="addCustomerModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Customer</h3>
                                <form id="customerForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="customers">

                                    <!-- <label>Customer ID:</label>
                                    <input type="text" name="Customer_ID" required> -->

                                    <label>Customer Name:</label>
                                    <input type="text" name="CustomerName" required>

                                    <label>Location:</label>
                                    <input type="text" name="Location" required>

                                    <label>Email:</label>
                                    <input type="text" name="Email" required>
                                    
                                    <label>Phone Number:</label>
                                    <input type="tel" name="PhoneNumber" required maxlength=11>

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="cus-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_customer.js" defer></script>
                    </div>
                    
                    <div class="table-container customer-table-container">
                        <table class="customer-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>LOCATION</th>
                                    <th class="icon-cell"></th>
                                    <th>Email</th>
                                    <th>Phone Number</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($customers as $index => $customers){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td><?= $customers['Customer_ID'] ?></td>
                                        <td><?= $customers['CustomerName']?></td>
                                        <td><?= $customers['Location']?></td>
                                        <td class="icon-cell"><span>...</span></td>
                                        <td><a href="#" class="email-link"><?= $customers['Email']?></a></td>
                                        <td><?= $customers['PhoneNumber']?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="returns" class="content-section">
                    <div class="custom-header"  style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">RETURNS</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                             <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>DATE</option></select></div>
                                <div class="toggle-buttons">
                                    <button id="customer-returns-btn" class="toggle-btn active">Customer</button>
                                    <button id="supplier-returns-btn" class="toggle-btn ">Supplier</button>
                                </div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="returns-count" class="na-count">07</span>
                        <span class="na-icon-btn">📝</span>
                        <span class="na-icon-btn">🗑️</span>
                        <span class="na-icon-btn">🖨️</span>
                        
                        <button id="cusreturns-add-btn" class="na-btn na-btn-add" style="display: flex;">ADD</button>
                        <div id="addCusReturnsModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Customer Returns</h3>
                                <form id="cusReturnsForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="customersreturns">

                                    <!-- <label>Customer ID:</label>
                                    <input type="text" name="Customer_ID"> -->

                                    <label for="Customer_IDRETURN">Choose Customer ID:</label>
                                    <select id="Customer_IDRETURN" name="Customer_ID" class="id-select">
                                        
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label>ReferenceNo:</label>
                                    <!-- <input type="number" name="ReferenceNo" maxlength="11" required> -->
                                    <input type="text" name="ReferenceNo" inputmode="numeric" pattern="\d{1,11}" maxlength="11" >

                                    <!-- <label>Product ID:</label>
                                    <input type="text" name="Product_ID" required> -->

                                    <label for="Product_IDCRETURN">Choose Product ID:</label>
                                    <select id="Product_IDCRETURN" name="Product_ID" class="id-select"required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label>Quantity:</label>
                                    <input type="number" name="Quantity" required>

                                    <label>Returned Date:</label>
                                    <input type="datetime-local" name="ReturnedDate" required>

                                    <!-- <label>Reason For Return:</label>
                                    <input type="text" name="ReasonForReturn" required> -->

                                    <label>Reason For Return:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                    <select class="creturns-reason-select" name="ReasonForReturn" required>
                                        <option value="">-- Reason --</option>
                                        <option value="Wrong Item">Wrong Item</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="Not Match">Not Match</option>
                                        <option value="Faulty">Faulty</option>
                                        <option value="Missing Parts">Missing Parts</option>
                                        <option value="Not Fit">Not Fit</option>
                                        <option value="Accidental">Accidental</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    
                                    </div>
                                    

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="cus-return-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_cusreturn.js" defer></script>
                        <script src="get/get_customerRETURN.js" defer></script>
                        <script src="get/get_productCRETURN.js" defer></script>
                        
                        <button id="supreturns-add-btn" class="na-btn na-btn-add" style="display: flex;">ADD</button>
                        <div id="addSupReturnsModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Supplier Returns</h3>
                                <form id="supReturnsForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="supplierreturns">

                                    <!-- <label>Supplier ID:</label>
                                    <input type="text" name="Supplier_ID" required> -->

                                    <label for="Supplier_IDSRETURN">Choose Supplier ID:</label>
                                    <select id="Supplier_IDSRETURN" name="Supplier_ID" class="id-select"required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <!-- <label>Product ID:</label>
                                    <input type="text" name="Product_ID" required> -->

                                    <label for="Product_IDSRETURN">Choose Product ID:</label>
                                    <select id="Product_IDSRETURN" name="Product_ID" class="id-select"required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label>Quantity:</label>
                                    <input type="number" name="Quantity" required>

                                    <label>Returned Date:</label>
                                    <input type="date" name="ReturnedDate" required>

                                    <!-- <label>Status:</label>
                                    <input type="text" name="Status" required> -->

                                    <label>Status:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                    <select class="creturns-reason-select" name="Status" required>
                                        <option value="">-- Status --</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Out for Delivery">Out for Delivery</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Delivered">Delivered</option>
                                        
                                    </select>
                                    </div>



                                    

                                    <!-- <label>Reason For Return:</label>
                                    <input type="text" name="Reason" required> -->

                                    <label>Reason For Return:</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                    <select class="creturns-reason-select" name="Reason" required>
                                        <option value="">-- Reason --</option>
                                        <option value="Wrong Item">Wrong Item</option>
                                        <option value="Damaged">Damaged</option>
                                        <option value="Missing Parts">Missing Parts</option>
                                        <option value="Accidental">Accidental</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    
                                    </div>

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="sup-returns-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_supreturn.js" defer></script>
                        <script src="get/get_supplierSRETURN.js" defer></script>
                        <script src="get/get_productSRETURN.js" defer></script>

                        
                    </div>

                    <div id="supplier-returns-table-container" class="table-container returns-table-container">
                        <table class="returns-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SRETURNS ID</th>
                                    <th>Supplier ID</th>
                                    <th>Product ID</th>
                                    <th>Quantity</th>
                                    <th>Returned Date</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($supplierreturns as $index => $supplierreturns){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td><?= $supplierreturns['SReturns_ID']?></td>
                                        <td class="supplier-id-cell"><?= $supplierreturns['Supplier_ID']?></td>
                                        <td class="product-id-cell"><?= $supplierreturns['Product_ID']?></td>
                                        <td><?= $supplierreturns['Quantity']?></td>
                                        <td><?= date('F d,Y', strtotime($supplierreturns['ReturnedDate']))?></td>
                                        <td><span class="status-tag status-pending"><?= $supplierreturns['Status']?></span></td>
                                        <td><span class="reason-tag reason-defective"><?= $supplierreturns['Reason']?></span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="customer-returns-table-container" class="table-container returns-table-container" style="display:none;">
                        <table class="returns-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CRETURNS ID</th>
                                    <th>Customer ID</th>
                                    <th>Reference No.</th>
                                    <th>Product ID</th>
                                    <th>Quantity</th>
                                    <th>Returned Date</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                           <tbody>
                                <?php foreach($customersreturns as $index => $customersreturns){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td><?= $customersreturns['CReturn_ID']?></td>
                                        <td class="customer-id-cell"><?= $customersreturns['Customer_ID']?></td>
                                        <td><?= $customersreturns['ReferenceNo']?></td>
                                        <td class="procduct-id-cell"><?= $customersreturns['Product_ID']?></td>
                                        <td><?= $customersreturns['Quantity']?></td>
                                        <td><?= date('F d, Y h:i A', strtotime($customersreturns['ReturnedDate']))?></td>
                                        <td><span class="reason-tag reason-incompatible"><?= $customersreturns['ReasonForReturn']?></span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="order-restock" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">ORDER / RESTOCK</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                             <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>DATE</option></select></div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="order-restock-count" class="na-count">05</span>
                        <span class="na-icon-btn">📝</span><span class="na-icon-btn">🗑️</span><span class="na-icon-btn">🖨️</span>
                        
                        <button id="order-restock-add-btn" class="na-btn na-btn-add">ADD</button>
                        <button id="update-restock-status-btn" class="na-btn na-btn-update">UPDATE</button>
                        
                        <div id="addOrderRestockModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Order/Restock</h3>
                                <form id="restockForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="restock">

                                    <!-- <label>Select Product ID</label>
                                    <input type="text" name="Product_ID" required> -->

                                    <label for="Product_IDORDRES">Choose Product ID:</label>
                                    <select id="Product_IDORDRES" name="Product_ID" class="id-select"required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <!-- <label>Order Type</label>
                                    <input type="text" name="Type" required> -->

                                    <label>Order Type::</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">                                                       
                                    <select class="status-select" name="Type" required>
                                        <option value="">...</option>
                                        <option value="New">New</option>
                                        <option value="Re-Order">Re-Order</option>

                                    </select>                        
                                    </div>

                                    
                                    <!-- <label>Select Supplier ID</label>
                                    <input type="text" name="Supplier_ID" required> -->

                                    <label for="Supplier_IDORDRES">Choose Supplier ID:</label>
                                    <select id="Supplier_IDORDRES" name="Supplier_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>


                                    <label>Quantity:</label>
                                    <input type="number" name="Quantity" required>

                                    <label>Order Date:</label>
                                    <input type="datetime-local" name="OrderDate" required>

                                    <label>Proof of Transaction:</label>
                                    <input type="file" accept="image/*" name="Image">
                                    
                                    <!-- <label>Status:</label>
                                    <input type="text" name="Status" required> -->


                                    <label>Status::</label>
                                    <!-- <div style="display: flex; justify-content:space-between; align-items:center;"></div>-->
                                    <select class="status-select" name="Status" required>
                                        <option value="">...</option>
                                        <option value="Requested">Requested</option>
                                        <option value="Out for Delivery">Out for Delivery</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Received">Received</option>

                                    </select>                        

                                    
                                    <!-- <label>Delivery Status:</label>
                                    <input type="text" name="DeliveryStatus" required> -->


                                    <!-- <label>Delivery Status::</label>                            
                                    <select class="status-select" name="DeliveryStatus">
                                        <option value="">...</option>
                                        <option value="On Time">On Time</option>
                                        <option value="Delayed">Delayed</option>
                                        <option value="Early">Early</option>
                                    </select>        -->

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="ord-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_order.js" defer></script>
                        <script src="get/get_supplierORDRES.js" defer></script>
                        <script src="get/get_productORDRES.js" defer></script>







                        <div id="updateOrderModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Update Order/Restock</h3>
                                <form action="update.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="table" value="restock">
                                    <input type="hidden" name="id" id="update-order-id">


                                    <label>Proof of Transaction:</label>
                                    <input type="file" accept="image/*" name="Image" id="edit-image">
                                    
                                    

                                    <label>Status::</label>
                                    <!-- <div style="display: flex; justify-content:space-between; align-items:center;"></div>-->
                                    <select class="status-select" name="Status" id="update-status" required>
                                        <option value="">...</option>
                                        <option value="Requested">Requested</option>
                                        <option value="Out-for-Delivery">Out for Delivery</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Received">Received</option>

                                    </select>                        


                                    <label>Delivery Status::</label>                            
                                    <select class="status-select" name="DeliveryStatus" id="update-delivery-status">
                                        <option value="">...</option>
                                        <option value="On-Time">On Time</option>
                                        <option value="Delayed">Delayed</option>
                                        <option value="Early">Early</option>
                                    </select>       

                                    <label>Total Received:</label>
                                    <input type="number"  name="TotalReceived" id="update-received">
                                    
                                    <label>With Issue:</label>
                                    <input type="number"  name="withIssue" id="update-issue">


                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="updateorder-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_order.js" defer></script>
                        <script src="assets/update_restock.js" defer></script>
                        <script src="get/get_productORDRES.js" defer></script>



                        
                    </div>
                        <div class="table-container order-restock-table-container">
                            <table class="order-restock-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ORESTOCK ID</th>
                                        <th>Product ID</th>
                                        <th>Order Type</th>
                                        <th>Supplier ID</th>
                                        <th>Ordered Quantity</th>
                                        <th>Order Date</th>
                                        <th>Proof of Transaction</th>
                                        <th>Status</th>
                                        <th>Delivery Status</th>
                                        
                                        <th>Total Received</th>
                                        <th>with Issue</th>
                                        <th></th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($restock as $index => $restocks){ ?>
                                        <tr>
                                            <td><?= $index + 1?></td>
                                            <td><?= $restocks['Orestock_ID'] ?></td>
                                            <td class="product-id-cell"><?= $restocks['Product_ID'] ?></td>
                                            <td><span class="order-type-tag order-type-new"><?= $restocks['Type'] ?></span></td>
                                            <td class="supplier-id-cell"><?= $restocks['Supplier_ID'] ?></td>
                                            <td><?= $restocks['Quantity'] ?></td>
                                            <td><?= date('F d, Y h:i A', strtotime($restocks['OrderDate']))?></td>
                                            <!-- <td class="proof-icon"><?= $restocks['ProofOfTransaction'] ?></td> -->

                                            <td>
                                                <?php if (!empty($restocks['Image']) && file_exists("uploads/" . $restocks['Image'])): ?>
                                                    <a href="uploads/<?= $restocks['Image']?>" target="_blank">
                                                        <img src="uploads/<?= $restocks['Image']?>" alt="Product Image" style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                                    </a>
                                                <?php else: ?>
                                                    No Image
                                                <?php endif; ?>
                                            </td>

                                            <!-- <td><span class="status-tag status-pending"><?= $restocks['Status']?></span></td> -->

                                            <td><span class="status-tag status-<?= strtolower($restocks['Status']) ?>">
                                                                        <?= htmlspecialchars($restocks['Status']) ?>
                                                </span>
                                            </td>

                                            <td><span class="delivery-status-tag delivery-status-<?= strtolower($restocks['DeliveryStatus']) ?>">
                                                                        <?= htmlspecialchars($restocks['DeliveryStatus']) ?>
                                                </span>
                                            </td>
                                            <!-- <td><span class="delivery-status-tag delivery-status-delayed"><?= $restocks['DeliveryStatus']?></span></td> -->
                                            <td><?= $restocks['TotalReceived'] ?></td>
                                            <td><?= $restocks['withIssue'] ?></td>

                                            <td class="action-cell" >
                                            <!-- edit & delete action -->
                                            <button style="display: none;" class="update-order-btn"
                                                data-id="<?= $restocks['Orestock_ID'] ?>"
                                                data-proof="<?= $restocks['Image'] ?>"
                                                data-status="<?= $restocks['Status'] ?>"
                                                data-deliverystatus="<?= $restocks['DeliveryStatus'] ?>"
                                                data-received="<?= $restocks['TotalReceived'] ?>"
                                                data-issue="<?= $restocks['withIssue'] ?>">
                                                
                                                
                                                📝 Update
                                            </button>

                                            <form style="display: none;" id="display-delete" action="delete.php" class="delete-product-form" method="POST" style="display: inline;">
                                                <input type="hidden" name="table" value="product">
                                                <input type="hidden" name="id" value="<?= $restocks['Orestock_ID']?>">
                                                <button type="submit"  onclick="return confirm('Delete this product?')">🗑️ Delete</button>
                                            </form>
                                        </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                </div>
                

                <div id="transaction-sales" class="content-section" >
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">TRANSACTIONS & SALES</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                            <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>DATE</option></select></div>
                                <div class="toggle-buttons">
                                    <button id="transaction-view-btn" class="toggle-btn active">Transaction</button>
                                    <button id="sales-view-btn" class="toggle-btn">Sales</button>
                                </div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="transaction-sales-count" class="na-count">10</span>
                        <span class="na-icon-btn">📝</span>
                        <span class="na-icon-btn">🗑️</span>
                        <span class="na-icon-btn">🖨️</span>
                        
                        <button id="transaction-add-btn" class="na-btn na-btn-add">ADD</button>
                        <div id="addTransactionModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Transaction</h3>
                                <form id="transactionForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="transactions">

                                    <!-- <label>Customer ID:</label>
                                    <input type="text" name="Customer_ID"> -->

                                    <label for="Customer_IDTRANS">Choose Customer ID:</label>
                                    <select id="Customer_IDTRANS" name="Customer_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label>ReferenceNo:</label>
                                    <input type="number" name="ReferenceNo" required maxlength=11>

                                    <!-- <label>Purchase Type:</label>
                                    <input type="text" name="PurchaseType" required> -->

                                    <label>Purchase Type:</label>
                                    <!-- <div style="display: flex; justify-content:space-between; align-items:center;"></div>-->
                                    <select class="purchase-type-select" name="PurchaseType" required>
                                        <option value="">- - - Choose Purchase Type - - -</option>
                                        <option value="Online">Online</option>
                                        <option value="Over-the-Counter">Over-the-Counter</option>
                                        <!-- <option value="Cancelled">Cancelled</option>
                                        <option value="Received">Received</option> -->

                                    </select>    



                                    <!-- <label>Purchase Scope:</label>
                                    <input type="text" name="PurchaseScope" required> -->

                                    <!-- <label>Product ID:</label>
                                    <input type="text" name="Product_ID" required> -->

                        <!-- <script src="get_supplierPROD.js" defer></script> -->

                                    <!-- <label for="Product_ID">Choose Product ID:</label>
                                    <select id="Product_ID" name="Product_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select> -->

                                    
                                    <!-- <label>Quantity:</label>
                                    <input type="number" name="Quantity" required> -->
                                    
                                    <!-- <label>Total Sales:</label>
                                    <p><span id="total-display" style="font-weight:bold; color:green;"></span>PHP 0.00</p>
                                    <input type="hidden" name="TotalSales" value=""> -->

                                    <!-- <label>Payment Method:</label>
                                    <input type="text" name="PaymentMethod" required> -->


                                    <label>Payment Method:</label>
                                    <!-- <div style="display: flex; justify-content:space-between; align-items:center;"></div>-->
                                    <select class="payment-method-select" name="PaymentMethod" required>
                                        <option value="">- - - Choose Payment Method - - -</option>
                                        <option value="Cash">Cash</option>
                                        <option value="eWallet">eWallet</option>


                                    </select>    
                                    
                                    <!-- <label>Service Type:</label>
                                    <input type="text" name="DeliveryType" required> -->

                                    <label>Service Type:</label>
                                    <!-- <div style="display: flex; justify-content:space-between; align-items:center;"></div>-->
                                    <select class="payment-method-select" name="DeliveryType" required>
                                        <option value="">- - - Choose Service Type - - -</option>
                                        <option value="Pick Up">Pick Up</option>
                                        <option value="Delivery">Delivery</option>


                                    </select>  

                                    <label>Transaction Date:</label>
                                    <input type="datetime-local" name="Transaction_Date" required>
                                    

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="transaction-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_transaction.js" defer></script>
                        <script src="get_productTRANS.js" defer></script>
                        <script src="get/get_customerTRANS.js" defer></script>
                        
                        <button id="sales-add-btn" class="na-btn na-btn-add">ADD</button>
                        <div id="addSalesModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Sales</h3>
                                <form id="salesForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="sales">

                                    <!-- <label>Transaction ID:</label>
                                    <input type="text" name="Transaction_ID" required> -->

                                    <label for="Transaction_IDSALES">Choose Transaction ID:</label>
                                    <select id="Transaction_IDSALES" name="Transaction_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label for="Product_ID">Choose Product ID:</label>
                                    <select id="Product_IDSALES" name="Product_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label>Product Name:</label>
                                    <input type="text" id="ProductName" name="ProductName" readonly>

                                    <label>Barcode:</label>
                                    <input type="text" id="Barcode" name="Barcode" readonly>

                                    <label>Unit Price (PHP):</label>
                                    <input type="number" step="0.01" id="Unit_Price" name="Unit_Price" readonly>

                                    <label>Quantity:</label>
                                    <input type="number" id="Quantity" name="Quantity" required>

                                    <label>Total Price (PHP):</label>
                                    <input type="number" step="0.01" id="TotalPrice" name="TotalPrice" readonly>

                                    

                                    <!-- <label>Quantity:</label>
                                    <input type="number" name="Quantity" required>

                                    <label>Total Price:</label>
                                    <input type="text" name="TotalPrice" readonly>

                                    <label>Barcode:</label>
                                    <input type="number" name="Barcode" required> -->

                                    <label>Sales Date:</label>
                                    <input type="datetime-local" name="SalesDate" required>

                                    <!-- <label>Account ID:</label>
                                    <input type="text" name="Account_ID" required> -->
                                    

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="sales-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_sales.js" defer></script>
                        <script src="get/get_productSALES.js" defer></script>
                        <script src="get_product_details.js" defer></script>
                        <script src="get/get_transactionSALES.js" defer></script>

                    </div>
                    
                    <!-- SALES VIEW CONTAINER -->
                    <div id="sales-view-container" class="table-container">
                        <table class="transaction-sales-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Order ID</th>   
                                    <th>Transaction ID</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Total Quantity</th>
                                    <th>Store Price</th>
                                    <th>Total Price</th>
                                    <th>Barcode</th>
                                    <th>Sales Date</th>
                                    <th>Account ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sales as $index => $sales){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td><?= $sales['Order_ID'] ?></td>
                                        <td><?= $sales['Transaction_ID'] ?></td>
                                        <td class="product-id-cell"><?= $sales['Product_ID'] ?></td>
                                        <td><?= $sales['ProductName'] ?></td>
                                        <td><?= $sales['Quantity'] ?></td>
                                        <td>PHP<?= $sales['Unit_Price'] ?></td>
                                        <td>PHP <?= $sales['TotalPrice'] ?></td>
                                        <td><?= $sales['Barcode'] ?></td>
                                        <!-- <td><?= date('F d,Y', strtotime($sales['SalesDate']))?></td> -->
                                        <td style="color: red;"><?= empty($sales['SalesDate']) ? '- - - N/A - - -' : date('F d, Y h:i A', strtotime($sales['SalesDate'])) ?></td>
                                        <td><?= $sales['Account_ID'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- TRANSACTION VIEW CONTAINER -->
                    <div id="transaction-view-container" class="table-container" style="display:none;">
                        <table class="transaction-sales-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Transaction ID</th>
                                    <th>Customer ID</th>
                                    <th>Reference No.</th>
                                    <th>Purchase Type</th>
                                    <!-- <th>Product Scope</th>
                                    <th>Product ID</th>
                                    <th>Quantity</th>
                                    <th>Total Sales</th> -->
                                    <th>Payment Method</th>
                                    <th>Service Type</th>
                                    <th>Transaction Date</th>
                                </tr>
                            </thead>
                           <tbody>
                                <?php foreach($transaction as $index => $transaction){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td><?= $transaction['Transaction_ID'] ?></td>
                                        <td><?= $transaction['Customer_ID'] ?></td>
                                        <td><?= $transaction['ReferenceNo'] ?></td>
                                        <td><span class="purchase-type-tag purchase-type-online"><?= $transaction['PurchaseType'] ?></span></td>
                                        <!-- <td><?= $transaction['PurchaseScope'] ?></td>
                                        <td class="product-id-cell"><?= $transaction['Product_ID'] ?></td>
                                        <td><?= $transaction['Quantity'] ?></td>
                                        <td>PHP <?= $transaction['TotalSales'] ?></td> -->
                                        <td><?= $transaction['PaymentMethod'] ?></td>
                                        <td><span class="delivery-type-tag delivery-type-delivery"><?= $transaction['ServiceType']?></span></td>
                                        <td style="color: red;"><?= empty($transaction['Transaction_Date']) ? '- - - N/A - - -' : date('F d, Y h:i A', strtotime($transaction['Transaction_Date'])) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>    

                <div id="sales-aggregation" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">SALES AGGREGATION</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>    
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                            <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>TYPE</option></select></div>
                                <div class="toggle-buttons">

                                    <!-- <button id="byproduct-sales-btn" class="toggle-btn active">By Product</button> -->
                                    <!-- <button id="entire-sales-btn" class="toggle-btn">Entire</button> -->
                                    <button id="byproduct-sales-btn" class="toggle-btn active" >By Product</button>
                                    <button id="entire-sales-btn" class="toggle-btn" >Entire</button>

                                    <!-- <button id="daily-sales-btn" class="toggle-btn">Daily</button>
                                    <button id="weekly-sales-btn" class="toggle-btn ">Weekly</button>
                                    <button id="monthly-sales-btn" class="toggle-btn ">Monthly</button> -->
                                </div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="toolbar">
                        
                        <span class="na-icon-btn">📝</span>
                        <span class="na-icon-btn">🗑️</span>
                        <span class="na-icon-btn">🖨️</span>
                        
                        <div class="toggle-buttons" id="product-toggle-buttons">
                            <button id="daily-product-btn" class="toggle-btn active">Daily Product</button>
                            <button id="weekly-product-btn" class="toggle-btn">Weekly Product</button>
                            <button id="monthly-product-btn" class="toggle-btn">Monthly Product</button>
                        </div>
                        
                        <div class="toggle-buttons" id="entire-toggle-buttons" style="display: none;">
                            <button id="daily-entire-btn" class="toggle-btn active">Daily Entire</button>
                            <button id="weekly-entire-btn" class="toggle-btn">Weekly Entire</button>
                            <button id="monthly-entire-btn" class="toggle-btn">Monthly Entire</button>
                        </div>
                    
                    

                                 
                        
                    </div>
                    <!-- <button id="daily-sales-btn" class="toggle-btn active" style="display: flex;">Daily</button>
                    <button id="weekly-sales-btn" class="toggle-btn" style="display: flex;">Weekly</button>
                    <button id="monthly-sales-btn" class="toggle-btn" style="display: flex;">Monthly</button> -->
                    <div id="byproduct-view-container" class="table-container" >
                        <div id="daily-byProduct-view-container" class="table-container">
                            <table class="sales-aggregation-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Daily Sales ID</th>
                                        <th>Product ID</th>
                                        <th>Period Start</th>
                                        <th>Period End</th>
                                        <th>Total Sales</th>
                                        <th>Total Quantity</th>
                                    </tr>


                                </thead>
                                <tbody>


                                    <?php foreach($dailysalesP as $index => $dailysalesP){ ?>
                                        <tr>
                                            <td><?= $index + 1?></td>
                                            <td><?= $dailysalesP['DailySales_ID'] ?></td>
                                            <!-- <td><span class="period-tag period-daily"><?= $salesaggregration['PeriodType'] ?></span></td> -->
                                            <!-- <td><span class="period-tag period-<?= strtolower($monthlysalesP['PeriodType']) ?>">
                                                                                <?= htmlspecialchars($monthlysalesP['PeriodType']) ?>
                                                </span>
                                                
                                            </td> -->
                                            <td class="product-id-cell"><?= $dailysalesP['Product_ID'] ?></td>
                                            <td><?= date('F d,Y', strtotime($dailysalesP['PeriodStart']))?></td>
                                            <td><?= date('F d,Y', strtotime($dailysalesP['PeriodEnd']))?></td>
                                            <td>PHP <?= $dailysalesP['TotalSales'] ?></td>
                                            <td><?= $dailysalesP['TotalQuantity'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="weekly-byProduct-view-container" class="table-container" style="display:none;">
                            <table class="sales-aggregation-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Weekly Sales ID</th>
                                        <th>Product ID</th>
                                        <th>Period Start</th>
                                        <th>Period End</th>
                                        <th>Total Sales</th>
                                        <th>Total Quantity</th>

                                    </tr>
                                </thead>
                            <tbody>

                                    <?php 
                                    // echo '<pre>';
                                    // print_r($monthlysalesP);
                                    // echo '</pre>';
                                    
                                    foreach($weeklysalesP as $index => $weeklysalesP){ ?>
                                        <tr>
                                            <td><?= $index + 1?></td>
                                            <td><?= $weeklysalesP['WeeklySales_ID'] ?></td>
                                            <!-- <td><span class="period-tag period-daily"><?= $salesaggregration['PeriodType'] ?></span></td> -->
                                            <!-- <td><span class="period-tag period-<?= strtolower($monthlysalesP['PeriodType']) ?>">
                                                                                <?= htmlspecialchars($monthlysalesP['PeriodType']) ?>
                                                </span>
                                                
                                            </td> -->
                                            <td class="product-id-cell"><?= $weeklysalesP['Product_ID'] ?></td>
                                            <td><?= date('F d,Y', strtotime($weeklysalesP['PeriodStart']))?></td>
                                            <td><?= date('F d,Y', strtotime($weeklysalesP['PeriodEnd']))?></td>
                                            <td>PHP <?= $weeklysalesP['TotalSales'] ?></td>
                                            <td><?= $weeklysalesP['TotalQuantity'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="monthly-byProduct-view-container" class="table-container" style="display:none;">
                            <table class="sales-aggregation-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Monthly Sales ID</th>
                                        <th>Product ID</th>
                                        <th>Period Start</th>
                                        <th>Period End</th>
                                        <th>Total Sales</th>
                                        <th>Total Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($monthlysalesP as $index => $monthlysalesP){ ?>
                                        <tr>
                                            <td><?= $index + 1?></td>
                                            <td ><?= $monthlysalesP['MonthlySales_ID'] ?></td>
                                            <!-- <td><span class="period-tag period-daily"><?= $salesaggregration['PeriodType'] ?></span></td> -->
                                            <!-- <td><span class="period-tag period-<?= strtolower($monthlysalesP['PeriodType']) ?>">
                                                                                <?= htmlspecialchars($monthlysalesP['PeriodType']) ?>
                                                </span>
                                                
                                            </td> -->
                                            <td class="product-id-cell"><?= $monthlysalesP['Product_ID'] ?></td>
                                            <td><?= date('F d,Y', strtotime($monthlysalesP['PeriodStart']))?></td>
                                            <td><?= date('F d,Y', strtotime($monthlysalesP['PeriodEnd']))?></td>
                                            <td>PHP <?= $monthlysalesP['TotalSales'] ?></td>
                                            <td><?= $monthlysalesP['TotalQuantity'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ENTIRE -->
                    <div id="entire-view-container" class="table-container" >                    
                        <div id="daily-entire-view-container" class="table-container" style="display:none;">
                            <table class="sales-aggregation-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Daily Sales ID</th>
                                        
                                        <th>Period Start</th>
                                        <th>Period End</th>
                                        <th>Total Sales</th>
                                        <th>Total Quantity</th>
                                    </tr>


                                </thead>
                                <tbody>
                                    <?php foreach($dailysalesE as $index => $dailysalesE){ ?>
                                        <tr>
                                            <td><?= $index + 1?></td>
                                            <td class="product-id-cell"><?= $dailysalesE['EntireDailySales_ID'] ?></td>
                                            <td><?= date('F d,Y', strtotime($dailysalesE['PeriodStart']))?></td>
                                            <td><?= date('F d,Y', strtotime($dailysalesE['PeriodEnd']))?></td>
                                            <td>PHP <?= $dailysalesE['TotalSales'] ?></td>
                                            <td><?= $dailysalesE['TotalQuantity'] ?></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>

                        <div id="weekly-entire-view-container" class="table-container" style="display:none;">
                            <table class="sales-aggregation-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Weekly Sales ID</th>
                                        
                                        <th>Period Start</th>
                                        <th>Period End</th>
                                        <th>Total Sales</th>
                                        <th>Total Quantity</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($weeklysalesE as $index => $weeklysalesE){ ?>
                                        <tr>
                                            <td><?= $index + 1?></td>
                                            <td class="product-id-cell"><?= $weeklysalesE['EntireWeeklySales_ID'] ?></td>
                                            <td><?= date('F d,Y', strtotime($weeklysalesE['PeriodStart']))?></td>
                                            <td><?= date('F d,Y', strtotime($weeklysalesE['PeriodEnd']))?></td>
                                            <td>PHP <?= $weeklysalesE['TotalSales'] ?></td>
                                            <td><?= $weeklysalesE['TotalQuantity'] ?></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>

                        <div id="monthly-entire-view-container" class="table-container" style="display: none;">
                            <table class="sales-aggregation-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Monthly Sales ID</th>
                                        
                                        <th>Period Start</th>
                                        <th>Period End</th>
                                        <th>Total Sales</th>
                                        <th>Total Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php foreach($monthlysalesE as $index => $monthlysalesE){ ?>
                                        <tr>
                                            <td><?= $index + 1?></td>
                                            <td class="product-id-cell"><?= $monthlysalesE['EntireMonthlySales_ID'] ?></td>
                                            <td><?= date('F d,Y', strtotime($monthlysalesE['PeriodStart']))?></td>
                                            <td><?= date('F d,Y', strtotime($monthlysalesE['PeriodEnd']))?></td>
                                            <td>PHP <?= $monthlysalesE['TotalSales'] ?></td>
                                            <td><?= $monthlysalesE['TotalQuantity'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                        <!-- <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_analytics.js" defer></script> -->
                
                <link rel="stylesheet" href="assets/add_product.css">
                <div id="forecast-analytics" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">FORECAST & ANALYTICS</div>
                            <div class="user-controls">
                                <div>
                                    <?php echo $row['userName']; ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                            <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>DATE</option></select></div>
                                <div class="toggle-buttons">
                                    <button id="forecast-view-btn" class="toggle-btn active">Forecast</button>
                                    <button id="analytics-view-btn" class="toggle-btn">Analytics</button>
                                </div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>

                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="forecast-analytics-count" class="na-count">10</span>
                        <span class="na-icon-btn">👁️</span>
                        <span class="na-icon-btn">📝</span>
                        <span class="na-icon-btn">🗑️</span>

                        <!-- Forecast ADD buttons -->
                        <button id="daily-forecast-add-btn" class="na-btn na-btn-add">ADD</button>
                        <button id="weekly-forecast-add-btn" class="na-btn na-btn-add" style="display:none;">ADD</button>
                        <button id="monthly-forecast-add-btn" class="na-btn na-btn-add" style="display:none;">ADD</button>

                        <!-- Analytics ADD buttons -->
                        <button id="daily-analytics-add-btn" class="na-btn na-btn-add" style="display:none;">ADD</button>
                        <button id="weekly-analytics-add-btn" class="na-btn na-btn-add" style="display:none;">ADD</button>
                        <button id="monthly-analytics-add-btn" class="na-btn na-btn-add" style="display:none;">ADD</button>

                        <!-- Forecast toggle buttons -->
                        <div class="toggle-buttons" id="forecast-toggle-buttons" style="margin-bottom:10px; display:block;">
                            <button id="daily-forecast-view-btn" class="toggle-btn active">Daily</button>
                            <button id="weekly-forecast-view-btn" class="toggle-btn">Weekly</button>
                            <button id="monthly-forecast-view-btn" class="toggle-btn">Monthly</button>
                        </div>

                        <!-- Analytics toggle buttons -->
                        <div class="toggle-buttons" id="analytics-toggle-buttons" style="margin-bottom:10px; display:none;">
                            <button id="daily-analytics-view-btn" class="toggle-btn active">Daily</button>
                            <button id="weekly-analytics-view-btn" class="toggle-btn">Weekly</button>
                            <button id="monthly-analytics-view-btn" class="toggle-btn">Monthly</button>
                        </div>
                    </div>

                    <!-- Forecast view container -->
                    <div id="forecast-view-container" style="display:flex; flex-direction:column; gap:10px;">
                        <!-- DAILY FORECAST -->
                        <div id="daily-forecast-view-container" class="table-container" style="display:block;">
                            <table class="forecast-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Forecast ID</th>
                                        <th>Forecast Type</th>
                                        <th>Product Scope</th>
                                        <th>Forecast Period</th>
                                        <th>Forecast Start</th>
                                        <th>Forecast End</th>
                                        <th>Projected Sales</th>
                                        <th>Confidence Level</th>
                                        <th>Account ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($dailyforecast as $index => $dailyforecast){ ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="forecast-id-cell"><?= $dailyforecast['DailyForecast_ID'] ?></td>
                                            <td><?= $dailyforecast['ForecastType'] ?></td>
                                            <td><span class="type-tag-purple"><?= $dailyforecast['ProductScope'] ?></span></td>
                                            <td><?= $dailyforecast['ForecastPeriod'] ?></td>
                                            <td><?= date('F d,Y', strtotime($dailyforecast['ForecastStart'])) ?></td>
                                            <td><?= date('F d,Y', strtotime($dailyforecast['ForecastEnd'])) ?></td>
                                            <td><?= $dailyforecast['ProjectedSales'] ?></td>
                                            <td><?= $dailyforecast['ConfidenceLevel'] ?></td>
                                            <td><?= $dailyforecast['Account_ID'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- WEEKLY FORECAST -->
                        <div id="weekly-forecast-view-container" class="table-container" style="display:none;">
                             <table class="forecast-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Forecast ID</th>
                                        <th>Forecast Type</th>
                                        <th>Product Scope</th>
                                        <th>Forecast Period</th>
                                        <th>Forecast Start</th>
                                        <th>Forecast End</th>
                                        <th>Projected Sales</th>
                                        <th>Confidence Level</th>
                                        <th>Account ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($weeklyforecast as $index => $weeklyforecast){ ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="forecast-id-cell"><?= $weeklyforecast['WeeklyForecast_ID'] ?></td>
                                            <td><?= $weeklyforecast['ForecastType'] ?></td>
                                            <td><span class="type-tag-purple"><?= $weeklyforecast['ProductScope'] ?></span></td>
                                            <td><?= $weeklyforecast['ForecastPeriod'] ?></td>
                                            <td><?= date('F d,Y', strtotime($weeklyforecast['ForecastStart'])) ?></td>
                                            <td><?= date('F d,Y', strtotime($weeklyforecast['ForecastEnd'])) ?></td>
                                            <td><?= $weeklyforecast['ProjectedSales'] ?></td>
                                            <td><?= $weeklyforecast['ConfidenceLevel'] ?></td>
                                            <td><?= $weeklyforecast['Account_ID'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- MONTHLY FORECAST -->
                        <div id="monthly-forecast-view-container" class="table-container" style="display:none;">
                             <table class="forecast-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Forecast ID</th>
                                        <th>Forecast Type</th>
                                        <th>Product Scope</th>
                                        <th>Forecast Period</th>
                                        <th>Forecast Start</th>
                                        <th>Forecast End</th>
                                        <th>Projected Sales</th>
                                        <th>Confidence Level</th>
                                        <th>Account ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($monthlyforecast as $index => $monthlyforecast){ ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="forecast-id-cell"><?= $monthlyforecast['MonthlyForecast_ID'] ?></td>
                                            <td><?= $monthlyforecast['ForecastType'] ?></td>
                                            <td><span class="type-tag-purple"><?= $monthlyforecast['ProductScope'] ?></span></td>
                                            <td><?= $monthlyforecast['ForecastPeriod'] ?></td>
                                            <td><?= date('F d,Y', strtotime($monthlyforecast['ForecastStart'])) ?></td>
                                            <td><?= date('F d,Y', strtotime($monthlyforecast['ForecastEnd'])) ?></td>
                                            <td><?= $monthlyforecast['ProjectedSales'] ?></td>
                                            <td><?= $monthlyforecast['ConfidenceLevel'] ?></td>
                                            <td><?= $monthlyforecast['Account_ID'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Analytics view container -->
                    <div id="analytics-view-container" style="display:none; flex-direction:column; gap:10px;">
                        <!-- DAILY ANALYTICS -->
                        <div id="daily-analytics-view-container" class="table-container" style="display:none;">
                            <table class="analytics-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Analytics ID</th>
                                        <th>Forecast ID</th>
                                        <th>Sales Metrics</th>
                                        <th>Stock Metrics</th>
                                        <th>Account ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($analytics as $index => $analytics){ ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="analytics-id-cell"><?= $analytics['AnalyticsID'] ?></td>
                                            <td class="forecast-id-cell"><?= $analytics['Forecast_ID'] ?></td>
                                            <td><?= $analytics['SalesMetrics'] ?></td>
                                            <td class="analytics-id-cell"><?= $analytics['Inventory_ID'] ?></td>
                                            <td class="forecast-id-cell"><?= $analytics['Account_ID'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- WEEKLY ANALYTICS -->
                        <div id="weekly-analytics-view-container" class="table-container" style="display:none;">
                            <table class="analytics-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Analytics ID</th>
                                        <th>Forecast ID</th>
                                        <th>Sales Metrics</th>
                                        <th>Stock Metrics</th>
                                        <th>Account ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($analytics as $index => $analytics){ ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="analytics-id-cell"><?= $analytics['AnalyticsID'] ?></td>
                                            <td class="forecast-id-cell"><?= $analytics['Forecast_ID'] ?></td>
                                            <td><?= $analytics['SalesMetrics'] ?></td>
                                            <td class="analytics-id-cell"><?= $analytics['Inventory_ID'] ?></td>
                                            <td class="forecast-id-cell"><?= $analytics['Account_ID'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- MONTHLY ANALYTICS -->
                        <div id="monthly-analytics-view-container" class="table-container" style="display:none;">
                            <table class="analytics-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Analytics ID</th>
                                        <th>Forecast ID</th>
                                        <th>Sales Metrics</th>
                                        <th>Stock Metrics</th>
                                        <th>Account ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($analytics as $index => $analytics){ ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td class="analytics-id-cell"><?= $analytics['AnalyticsID'] ?></td>
                                            <td class="forecast-id-cell"><?= $analytics['Forecast_ID'] ?></td>
                                            <td><?= $analytics['SalesMetrics'] ?></td>
                                            <td class="analytics-id-cell"><?= $analytics['Inventory_ID'] ?></td>
                                            <td class="forecast-id-cell"><?= $analytics['Account_ID'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Forecast & Analytics Modals -->
                    <div id="daily-addForecastModal" class="modal" aria-hidden="true">
                        <div class="modal-content">
                            <h3>Add Daily New Forecast</h3>
                            <form id="daily-forecastForm" action="add.php" method="POST">
                            <input type="hidden" name="table" value="daily_forecast">
                            <label>Forecast Type:</label><input type="text" name="ForecastType" required>
                            <label>Product Scope:</label><input type="number" name="ProductScope" required>
                            <label>Forecast Period:</label><input type="text" name="ForecastPeriod" required>
                            <label>Forecast Start:</label><input type="date" name="ForecastStart" required>
                            <label>Forecast End:</label><input type="date" name="ForecastEnd" required>
                            <label>Projected Sales:</label><input type="number" name="ProjectedSales" required>
                            <label>ConfidenceLevel:</label><input type="text" name="ConfidenceLevel" required>
                            <label>Account_ID:</label><input type="number" name="Account_ID" required>
                            <div class="modal-buttons">
                                <button type="submit" class="na-btn na-btn-add">Save</button>
                                <button type="button" id="daily-forecast-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                            </div>
                            </form>
                        </div>
                        </div>

                        <div id="weekly-addForecastModal" class="modal" aria-hidden="true">
                        <div class="modal-content">
                            <h3>Add Weekly New Forecast</h3>
                            <form id="weekly-forecastForm" action="add.php" method="POST">
                            <input type="hidden" name="table" value="weekly_forecast">
                            <!-- same fields -->
                            <label>Forecast Type:</label><input type="text" name="ForecastType" required>
                            <label>Product Scope:</label><input type="number" name="ProductScope" required>
                            <label>Forecast Period:</label><input type="text" name="ForecastPeriod" required>
                            <label>Forecast Start:</label><input type="date" name="ForecastStart" required>
                            <label>Forecast End:</label><input type="date" name="ForecastEnd" required>
                            <label>Projected Sales:</label><input type="number" name="ProjectedSales" required>
                            <label>ConfidenceLevel:</label><input type="text" name="ConfidenceLevel" required>
                            <label>Account_ID:</label><input type="number" name="Account_ID" required>
                            <div class="modal-buttons">
                                <button type="submit" class="na-btn na-btn-add">Save</button>
                                <button type="button" id="weekly-forecast-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                            </div>
                            </form>
                        </div>
                        </div>

                        <div id="monthly-addForecastModal" class="modal" aria-hidden="true">
                        <div class="modal-content">
                            <h3>Add Monthly New Forecast</h3>
                            <form id="monthly-forecastForm" action="add.php" method="POST">
                            <input type="hidden" name="table" value="monthly_forecast">
                            <!-- same fields -->
                            <label>Forecast Type:</label><input type="text" name="ForecastType" required>
                            <label>Product Scope:</label><input type="number" name="ProductScope" required>
                            <label>Forecast Period:</label><input type="text" name="ForecastPeriod" required>
                            <label>Forecast Start:</label><input type="date" name="ForecastStart" required>
                            <label>Forecast End:</label><input type="date" name="ForecastEnd" required>
                            <label>Projected Sales:</label><input type="number" name="ProjectedSales" required>
                            <label>ConfidenceLevel:</label><input type="text" name="ConfidenceLevel" required>
                            <label>Account_ID:</label><input type="number" name="Account_ID" required>
                            <div class="modal-buttons">
                                <button type="submit" class="na-btn na-btn-add">Save</button>
                                <button type="button" id="monthly-forecast-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                            </div>
                            </form>
                        </div>
                        </div>

                        <!-- Analytics modals -->
                        <div id="daily-addAnalyticsModal" class="modal" aria-hidden="true">
                        <div class="modal-content">
                            <h3>Add Daily New Analytics</h3>
                            <form id="daily-analyticsForm" action="add.php" method="POST">
                            <input type="hidden" name="table" value="analytics">
                            <label>Forecast ID:</label><input type="number" name="Forecast_ID" required>
                            <label>Sales Metrics:</label><input type="number" name="SalesMetrics" required>
                            <label>Inventory ID:</label><input type="number" name="Inventory_ID" required>
                            <label>Account ID:</label><input type="number" name="Account_ID" required>
                            <div class="modal-buttons">
                                <button type="submit" class="na-btn na-btn-add">Save</button>
                                <button type="button" id="daily-analytics-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                            </div>
                            </form>
                        </div>
                        </div>

                        <div id="weekly-addAnalyticsModal" class="modal" aria-hidden="true">
                        <div class="modal-content">
                            <h3>Add Weekly New Analytics</h3>
                            <form id="weekly-analyticsForm" action="add.php" method="POST">
                            <input type="hidden" name="table" value="analytics">
                            <label>Forecast ID:</label><input type="number" name="Forecast_ID" required>
                            <label>Sales Metrics:</label><input type="number" name="SalesMetrics" required>
                            <label>Inventory ID:</label><input type="number" name="Inventory_ID" required>
                            <label>Account ID:</label><input type="number" name="Account_ID" required>
                            <div class="modal-buttons">
                                <button type="submit" class="na-btn na-btn-add">Save</button>
                                <button type="button" id="weekly-analytics-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                            </div>
                            </form>
                        </div>
                        </div>

                        <div id="monthly-addAnalyticsModal" class="modal" aria-hidden="true">
                        <div class="modal-content">
                            <h3>Add Monthly New Analytics</h3>
                            <form id="monthly-analyticsForm" action="add.php" method="POST">
                            <input type="hidden" name="table" value="analytics">
                            <label>Forecast ID:</label><input type="number" name="Forecast_ID" required>
                            <label>Sales Metrics:</label><input type="number" name="SalesMetrics" required>
                            <label>Inventory ID:</label><input type="number" name="Inventory_ID" required>
                            <label>Account ID:</label><input type="number" name="Account_ID" required>
                            <div class="modal-buttons">
                                <button type="submit" class="na-btn na-btn-add">Save</button>
                                <button type="button" id="monthly-analytics-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script src="assets/add_forecast.js"></script>
                <script src="assets/add_analytics.js"></script>

                        
<!-- END OF FORECAST & ANALYTICS -->

                <div id="stock-adjustments" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar">
                            <div class="tab">STOCK ADJUSTMENTS</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="controls-bar">
                             <div class="controls">
                                <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>DATE</option></select></div>
                            </div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>
                    </div>
                    <div class="toolbar">
                        <input type="checkbox">
                        <span id="stock-adjustments-count" class="na-count">07</span>
                        <span class="na-icon-btn">📝</span><span class="na-icon-btn">🗑️</span><span class="na-icon-btn">🖨️</span>
                        
                        <button id="adjustments-add-btn" class="na-btn na-btn-add">ADD</button>
                        <div id="addAdjustmentModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Stock Adjustment</h3>
                                <form id="adjustmentForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="pulledoutitems">

                                    <label>Product ID:</label>
                                    <input type="number" name="Product_ID" required>

                                    <label>Supplier ID:</label>
                                    <input type="number" name="Supplier_ID" required>

                                    <label>Quantity:</label>
                                    <input type="number" name="Quantity" required>

                                    <label>Reason:</label>
                                    <input type="text" name="Reason" required>

                                    <label>Pulled Date:</label>
                                    <input type="date" name="PulledDate" required>
   

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="adjustment-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <link rel="stylesheet" href="assets/add_product.css">
                        <script src="assets/add_adjustment.js" defer></script>

                    </div>
                    <div class="table-container">
                        <table class="stock-adjustments-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Pulled ID</th>
                                    <th>Product ID</th>
                                    <th>Supplier ID</th>
                                    <th>Quantity</th>
                                    <th>Reason</th>
                                    <th>Pulled Date</th>
                                    <th>Account ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pulledoutitems as $index => $pulledoutitems){ ?>
                                    <tr>
                                        <td><?= $index + 1?></td>
                                        <td class="analytics-id-cell"><?= $pulledoutitems['Pulled_ID']?></td>
                                        <td class="forecast-id-cell"><?= $pulledoutitems['Product_ID']?></td>
                                        <td class="forecast-id-cell"><?= $pulledoutitems['Supplier_ID']?></td>
                                        <td><?= $pulledoutitems['Quantity']?></td>
                                        <td><?= $pulledoutitems['Reason']?></td>
                                        <td><?= date('F d,Y', strtotime($pulledoutitems['PulledDate']))?></td>                              
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="account" class="content-section">
                    <div class="custom-header" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover;">
                        <div class="top-bar"><div class="tab">ACCOUNT</div>
                            <div class="user-controls">
                                <div>      
                                <?php
                                    echo $row['userName'];                        
                                ?>
                                </div>
                                <div class="user-icon">👤</div>
                                <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div>
                            </div>
                            
                        </div>
                        <div style="height: 50px;"></div>
                    </div>
                    <div class="account-page-container">
                        <div class="account-card">
                            <div class="account-info-left">
                                <div class="info-section">
                                    <h3>Basic Info</h3>
                                    <div class="profile-picture-section">
                                        <span class="info-label">Profile Picture</span>
                                        <div class="profile-pic-controls">
                                            <div class="profile-pic-display">
                                                <span>👤</span>
                                            </div>

                                <!-- <form id="customerForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="customers">

                                    <label>Customer Name:</label>
                                    <input type="text" name="CustomerName" required>

                                    <label>Location:</label>
                                    <input type="text" name="Location" required>

                                    <label>Email:</label>
                                    <input type="text" name="Email" required>
                                    
                                    <label>Phone Number:</label>
                                    <input type="tel" name="PhoneNumber" required maxlength=11>

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        
                                        <button type="button" id="cus-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form> -->
                                        
                                            <div class="upload-links">
                                            <form id="userimage" action="userimage.php" method="POST" >
                                                <input type="hidden" name="table" value="users">
                                                <input type="file" name="image" id="profile-upload" accept="image/*" style="display: none;">
                                                <a href="#" id="upload-link">Upload new picture</a>
                                                <a href="#" id="remove-link" class="remove-link">Remove</a>
                                                <button type="submit">SAVE</button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Name</span>
                                        <span class="info-value">Juan Dela Cruz <span>&rsaquo;</span></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Date of Birth</span>
                                        <span class="info-value">December 24, 1990 <span>&rsaquo;</span></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Gender</span>
                                        <span class="info-value">Male <span>&rsaquo;</span></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Email</span>
                                        <span class="info-value">DelaCruzJuan2419@gmail.com <span>&rsaquo;</span></span>
                                    </div>
                                </div>
                                <div class="info-section">
                                    <h3>Account Info</h3>
                                    <div class="info-row">
                                        <span class="info-label">Account ID</span>
                                        <span class="info-value">1001 <span>&rsaquo;</span></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Username</span>
                                        <span class="info-value">Manager 1 <span>&rsaquo;</span></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Password</span>
                                        <span class="info-value">****************** <span>&rsaquo;</span></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">User Type</span>
                                        <span class="info-value">Administrator <span>&rsaquo;</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="account-info-right">
                                <div class="recent-sales-card">
                                    <h3>Recent Sales</h3>
                                    <div class="recent-sales-table-container">
                                        <table class="recent-sales-table">
                                            <thead>
                                                <tr><th>Sales ID</th><th>Date</th><th>Product</th><th>Quantity</th></tr>
                                            </thead>
                                            <tbody>
                                                <tr><td>1030</td><td>2025-05-05</td><td>1001</td><td>3</td></tr>
                                                <tr><td>1029</td><td>2025-05-05</td><td>1002</td><td>3</td></tr>
                                                <tr><td>1028</td><td>2025-05-05</td><td>1003</td><td>3</td></tr>
                                                <tr><td>1027</td><td>2025-05-05</td><td>1001</td><td>3</td></tr>
                                                <tr><td>1026</td><td>2025-05-05</td><td>1001</td><td>3</td></tr>
                                                <tr><td>1025</td><td>2025-05-05</td><td>1004</td><td>3</td></tr>
                                                <tr><td>1024</td><td>2025-05-05</td><td>1005</td><td>3</td></tr>
                                                <tr><td>1023</td><td>2025-05-05</td><td>1001</td><td>3</td></tr>
                                                <tr><td>1022</td><td>2025-05-05</td><td>1007</td><td>3</td></tr>
                                                <tr><td>1021</td><td>2025-05-05</td><td>1002</td><td>3</td></tr>
                                                <tr><td>1020</td><td>2025-05-05</td><td>1001</td><td>3</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="account-footer">
                                        <span>Deleted Files 🗑️</span>
                                    </div>
                                </div>
                                <div class="bottom-links">
                                    <a href="#">Privacy</a>
                                    <a href="#">Languages</a>
                                    <a href="#">Help</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="settings" class="content-section"><h2>Settings</h2><!-- Settings content goes here --></div>
            </div>
        </div>
    </div>
    
    <!-- MODALS -->
    <div id="calendarModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="calendar-header"><button onclick="prevMonth()">&#9664;</button><h2 id="monthYear"></h2><button onclick="nextMonth()">&#9654;</button></div>
                <button class="close-button" onclick="closeCalendarModal()">&times;</button>
            </div>
            <div class="calendar-grid-container">
                <div class="calendar-grid">
                    <div class="calendar-weekday">Sun</div><div class="calendar-weekday">Mon</div><div class="calendar-weekday">Tue</div><div class="calendar-weekday">Wed</div><div class="calendar-weekday">Thu</div><div class="calendar-weekday">Fri</div><div class="calendar-weekday">Sat</div>
                </div>
                <div id="calendar-body" class="calendar-grid"></div>
            </div>
        </div>
    </div>
    
    <div id="addScheduleModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header"><h2>Add Schedule for <span id="scheduleDate"></span></h2><button class="close-button" onclick="closeAddScheduleModal()">&times;</button></div>
            <form id="addScheduleForm" onsubmit="event.preventDefault(); saveSchedule();">
                <div class="modal-body">
                    <input type="hidden" id="eventDate">
                    <div class="form-group"><label for="eventSupplierId">Supplier ID</label><input type="number" id="eventSupplierId" placeholder="e.g., 1002" required></div>
                    <div class="form-group"><label for="eventType">Event Type</label><select id="eventType" required><option value="incoming">Incoming Delivery</option><option value="outgoing">Outgoing Shipment</option></select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeAddScheduleModal()">Cancel</button><button type="submit" class="btn-save">Add Event</button></div>
            </form>
        </div>
    </div>

    <!-- PRODUCT TOOLTIP ELEMENT -->
    <div id="productTooltip">
        <div class="tooltip-header">
            <img id="tooltipImg" src="" alt="Product Image">
            <div class="tooltip-details">
                <div class="detail-item"><label>Price:</label><span id="tooltipPrice"></span></div>
                <div class="detail-item"><label>Quantity:</label><span id="tooltipQuantity"></span></div>
                <div class="detail-item"><label>Type:</label><span id="tooltipType" class="type-tag"></span></div>
                <div class="detail-item"><label>Color:</label><span id="tooltipColor"></span></div>
            </div>
        </div>
        <div class="tooltip-full-width">
            <p><b>Product ID:</b> <span id="tooltipProductId"></span></p>
            <p><b>Product Name:</b> <span id="tooltipProductName"></span></p>
            <p><b>Supplier ID:</b> <span id="tooltipSupplierId"></span></p>
            <p><b>Supplier Name:</b> <span id="tooltipSupplierName"></span></p>
            <p><b>Expiration Date:</b> <span id="tooltipExpiration" class="expiration-red"></span></p>
        </div>
    </div>

    <!-- SUPPLIER TOOLTIP ELEMENT -->
    <div id="supplierTooltip">
        <img id="supplierTooltipImg" src="" alt="Supplier Image">
        <div class="tooltip-full-width">
            <p><b>Supplier ID:</b> <span id="supplierTooltipId"></span></p>
            <p><b>Supplier Name:</b> <span id="supplierTooltipName"></span></p>
            <p><b>Location:</b> <span id="supplierTooltipLocation"></span></p>
            <p><b>Email:</b> <span id="supplierTooltipEmail"></span></p>
            <p><b>Phone #:</b> <span id="supplierTooltipPhone"></span></p>
        </div>
    </div>

    <!-- Link to the external JavaScript file -->
    <script src="homepagescript.js"></script>
    <!-- <script src="get_product.js" defer></script>
    <script src="get_supplier.js" defer></script> -->
</body>
</html>