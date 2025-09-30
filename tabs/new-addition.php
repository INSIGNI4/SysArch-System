
<?php
session_start();

if (isset($_SESSION['response'])) {
    echo "<script>alert('" . $_SESSION['response']['message'] . "');</script>";
    unset($_SESSION['response']);

}



include('show_data.php');

$newaddition = fetchTableData($conn, 'newaddition');


if (!is_array($newaddition)) {
    $newaddition = [];  
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
    <title>Document</title>
    <link rel="stylesheet" href="Login/homepagestyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>


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
                <!-- Each section now contains its own header -->
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
                                <!-- <div class="header-icons">
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper">🖨️</span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div> -->
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
                                <!-- <div class="header-icons">
                                    <span>🖨️</span>
                                    <span class="icon-wrapper">🔔<span class="badge">3</span></span>
                                    <span class="icon-wrapper" onclick="openCalendarModal()">📅<span class="badge">2</span></span>
                                </div> -->
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
                                    <select id="Product_ID" name="Product_ID" class="shelf-row-select" required>
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

                                    <label>Supplier ID:</label>
                                    <input type="text" name="Supplier_ID" required>

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
                        <script src="get_product.js" defer></script>
                        
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
                

    
</body>
</html>