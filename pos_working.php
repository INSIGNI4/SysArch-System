                <div id="order-restock" class="content-section">
                    <div class="custom-header sticky-div-1" style="background-image: url(topbarlogo.png);background-repeat: no-repeat;background-size: cover; 
                    height: 80px; ">
                        <div class="top-bar">
                            <div class="tab">RESTOCK</div>
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
                                <!-- <div class="control-group"><label>📊 Group by:</label><select></select></div>
                                <div class="control-group"><label>⇅ Sort by:</label><select><option>DATE</option></select></div> -->
                            </div>
                            <!-- <div class="na-quick-search">🔍 Quick Search</div> -->
                            <div class="header-icons">
                                <span class="icon-wrapper"><i class="fas fa-print"></i></span>
                                <span class="icon-wrapper" onclick="openNotifications()"><i class="fas fa-envelope"></i><span class="badge">0</span></span>
                                <span class="icon-wrapper" onclick="openCalendarModal()"><i class="fa-regular fa-calendar-days"></i><span class="badge">2</span></span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="toolbar">
                        <?php 
                        $totalRows = count($restock);
                        ?>
                    
                        

                        <span id="products-count" class="na-count"><?= $totalRows ?></span> 
                        <span class="na-icon-btn">📝</span><span class="na-icon-btn">🗑️</span><span class="na-icon-btn">🖨️</span>
                        
                        <button id="order-restock-add-btn" class="na-btn na-btn-add">ADD</button>
                        <button id="update-restock-status-btn" class="na-btn na-btn-update">UPDATE</button>
                         -->

                    <div class="toolbar sticky-div-2" style="display: flex; justify-content:space-between;">
                        <div>
                            <?php 
                            $totalRows = count($restock);
                            ?>

                            <span id="products-count" class="na-count"><?= $totalRows ?></span>
                            <button id="edit-restock-btn" class="na-btn na-btn-add1" type="button"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button id="delete-restock-btn" class="na-btn na-btn-add1" type="button"><i class="fa-solid fa-trash"></i></button>
                            <!-- <span class="na-icon-btn">🖨️</span> -->
                             
                            <button id="order-restock-add-btn" class="na-btn na-btn-add">ADD</button>
                            <button id="update-restock-status-btn" class="na-btn na-btn-update">UPDATE</button>
                        </div>

                        <div>
                            <div class="na-quick-search">🔍 Quick Search</div>
                        </div>  


                                <!-- //ADD RESTOCK -->

                        <div id="addOrderRestockModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Order/Restock</h3>
                                <form id="restockForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="restock">

                                    <!-- <label>Select Product ID</label>
                                    <input type="text" name="Product_ID" required> -->

                                    <label for="Product_IDORDRES">Choose Product ID:</label>
                                    <select id="Product_IDORDRES" name="Product_ID" class="id-select" style="width: 60%;" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <!-- <label>Order Type</label>
                                    <input type="text" name="Type" required> -->

                                    <!-- <label>Order Type::</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">                                                       
                                    <select class="status-select" name="Type" required>
                                        <option value="">...</option>
                                        <option value="New">New</option>
                                        <option value="Re-Order">Re-Order</option>

                                    </select>                        
                                    </div> -->

                                    
                                    <!-- <label>Select Supplier ID</label>
                                    <input type="text" name="Supplier_ID" required> -->

                                    <label for="Supplier_IDORDRES">Choose Supplier ID:</label>
                                    <select id="Supplier_IDORDRES" name="Supplier_ID" class="id-select" style="width: 60%;" required>
                                        <option disabled selected>Loading...</option>
                                    </select>


                                    <label>Quantity:</label>
                                    <input type="number" name="Quantity" required>

                                    <!-- <label>Order Date:</label>
                                    <input type="datetime-local" name="OrderDate" required> -->

                                    <!-- <label>Proof of Transaction:</label>
                                    <input type="file" accept="image/*" name="Image"> -->
                                    
                                    <!-- <label>Status:</label>
                                    <input type="text" name="Status" required> -->


                                    <!-- <div style="display: flex; justify-content:space-between; align-items:center;"></div>-->
                                    <!-- <label>Status::</label>
                                    <select class="status-select" name="Status" required>
                                        <option value="">...</option>
                                        <option value="Requested">Requested</option>
                                        <option value="Out for Delivery">Out for Delivery</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Received">Received</option>
                                        
                                    </select>            -->
                                    <input type="hidden"  name="Status" value="Requested">
                                    
                                    <!-- <label>Total Received:</label> -->
                                    <!-- <input type="hidden"  name="TotalReceived" value="0"> -->
                                    
                                    <!-- <label>With Issue:</label> -->
                                    <!-- <input type="hidden"  name="withIssue"  value="0"> -->

                                    
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


                                <!-- //EDIT RESTOCK -->

                        <div id="editRestockModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Edit Restock</h3>
                                <form id="restockForm" action="update.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="table" value="restock">
                                    <input type="number" name="id" id="edit-order-id" readonly>

                                    <!-- <label>Select Product ID</label>
                                    <input type="text" name="Product_ID" required> -->

                                    <label for="Product_IDORDRES">Choose Product ID:</label>
                                    <select id="edit-productidRESTOCK" name="Product_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>

                                    <label for="Supplier_IDORDRES">Choose Supplier ID:</label>
                                    <select id="edit-supplieridRESTOCK" name="Supplier_ID" class="id-select" required>
                                        <option disabled selected>Loading...</option>
                                    </select>


                                    <label>Quantity:</label>
                                    <input type="number" name="Quantity" id="edit-quantity" required>


                                    <input type="hidden"  name="Status" value="Requested">
                                    

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <!-- <button type="button" id="cancel-btn" class="na-btn na-btn-cancel close-modal-btn">Cancel</button> -->
                                        <button type="button" id="editorder-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                            <link rel="stylesheet" href="assets/add_product.css">
                            <script src="assets/edit_restock.js"></script>
                            <script src="get/edit_supplierRESTOCK.js"></script>
                            <script src="get/edit_productRESTOCK.js"></script>
                        </div>
                        
                        <!-- <script src="assets/add_order.js" defer></script>
                        
                        <script src="get/get_supplierORDRES.js" defer></script>
                        <script src="get/get_productORDRES.js" defer></script> -->








                        <div id="updateOrderModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Update Order/Restock</h3>
                                <form action="update.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="table" value="restock">
                                    <input type="number" name="id" id="update-order-id">


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

                                    <label style="color: red;">With Expiration? :</label>
                                    <div style="display: flex; justify-content:space-between; align-items:center;">
                                    <!-- <label>With Expiration?:</label>
                                    <select class="status-select" name="LocationS" id="Expiration_Status" required>
                                        <option value="yes">Yes</option>
                                        <option value="no ">No</option>
                                    </select> -->

                                    <label>Expiration Date:
                                        <button type="button" id="toggleNullBtnOrder_restock" style="color: red;">NO ?</button>
                                        <button type="button" id="toggleNotNullBtnOrder_restock" style="display: none;">YES ?</button>
                                    </label>
                                    <input type="date" name="ExpirationDate" id="update-datereceived" style="width: 50%;">
                                    </div>

                                    
                                    <!-- <label>Expiration Date:</label>
                                    <input type="datetime-local" name="Date_Received" id="update-datereceived"> -->

                                    <label>Ordered Quantity:</label>
                                    <input type="number" name="Quantity" id="update-orderedQuantity" readonly>

                                    <label>Without Issue:</label>
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
                        <script src="compare_quantity.js" defer></script>
                        



                        
                    </div>
                        <div class="table-container order-restock-table-container">
                            <table class="order-restock-table">
                                <thead class="sticky-div">
                                    <tr>
                                        <!-- <th><?= $totalRows ?></th> -->
                                        <th>ORESTOCK ID</th>
                                        <th>Product ID</th>
                                        <th>Supplier ID</th>
                                        <!-- <th>Order Type</th> -->
                                        <th>Ordered Quantity</th>
                                        <th>Order Date</th>
                                        <th>Proof of Transaction</th>
                                        <th>Status</th>
                                        <th>Delivery Status</th>
                                        <th>Date Received</th>
                                        <th>Without Issue</th>
                                        <th>with Issue</th>
                                        <th></th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        usort($restock, function ($a, $b) {
                                        return $b['Orestock_ID'] <=> $a['Orestock_ID'];
                                    });


                                    foreach($restock as $index => $restocks){ ?>
                                        <tr>
                                            <!-- <td>
                                                <?= $index + 1?>
                                            </td> -->
                                            <td><?= $restocks['Orestock_ID'] ?></td>
                                            <td class="product-id-cell"><?= $restocks['Product_ID'] ?></td>
                                            <td class="supplier-id-cell"><?= $restocks['Supplier_ID'] ?></td>
                                            <!-- <td><span class="order-type-tag order-type-new"><?= $restocks['Type'] ?></span></td> -->
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
                                            <!-- <td><?= date('F d, Y h:i A', strtotime($restocks['Date_Received']))?></td> -->
                                            <td style="color: red;"><?= empty($restocks['Date_Received']) ? '- - - N/A - - -' : date('F d, Y h:i A', strtotime($restocks['Date_Received'])) ?></td>
                                            <td><?= $restocks['TotalReceived'] ?></td>
                                            <td><?= $restocks['withIssue'] ?></td>

                                            <td class="action-cell" >
                                            <!-- edit & delete action -->
                                                <button style="display: none;" class="edit-order-btn"
                                                    data-id="<?= $restocks['Orestock_ID'] ?>"
                                                    data-quantity="<?= $restocks['Quantity'] ?>"                                                                                                        
                                                    data-productid="<?= $restocks['Product_ID'] ?>"
                                                    data-supplierid="<?= $restocks['Supplier_ID'] ?>">
                                                    📝 Edit
                                                </button>

                                                <button style="display: none;" class="update-order-btn"
                                                    data-id="<?= $restocks['Orestock_ID'] ?>"
                                                    data-proof="<?= $restocks['Image'] ?>"
                                                    data-status="<?= $restocks['Status'] ?>"
                                                    data-deliverystatus="<?= $restocks['DeliveryStatus'] ?>"
                                                    data-datereceived="<?= $restocks['ExpirationDate'] ?>"
                                                    data-quantity="<?= $restocks['Quantity'] ?>"
                                                    data-received="<?= $restocks['TotalReceived'] ?>"
                                                    data-issue="<?= $restocks['withIssue'] ?>">                                                                                    
                                                    📝 Update
                                                </button>

                                                <form style="display: none;" id="display-delete" action="delete.php" class="delete-product-form" method="POST" style="display: inline;">
                                                    <input type="hidden" name="table" value="restock">
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