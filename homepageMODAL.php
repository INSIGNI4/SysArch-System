

                                <!-- //ADD RESTOCK -->
                                 

                        <div id="addOrderRestockModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <h3>Add New Order/Restock</h3>
                                

                                <!-- <form id="restockForm" action="add.php" method="POST"> -->
                                <form id="restockForm" action="add_restock.php" method="POST">

                                    <input type="hidden" name="table" value="restock">

                                    <!-- MULTIPLE RESTOCK ITEMS -->
                                    <div id="restock-items-container">

                                        <!-- Rows will be created by JavaScript -->

                                    </div>



                                    <!-- SUPPLIER ORDER SUMMARY -->
                                    <div id="restock-supplier-summary">

                                        <h4>Order Summary by Supplier</h4>

                                        <div id="supplier-summary-content">

                                            <p class="supplier-summary-empty">
                                                No products added yet.
                                            </p>

                                        </div>

                                    </div>



                                    <!-- ADD ANOTHER PRODUCT -->
                                    <button type="button" id="add-restock-item-btn" class="na-btn na-btn-add">
                                        + Add Item
                                    </button>

                                    <input type="hidden" name="Status" value="Requested">

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">
                                            Confirm Order
                                        </button>

                                        <button type="button" id="ord-cancel-btn" class="na-btn na-btn-cancel">
                                            Cancel
                                        </button>
                                    </div>

                                </form>
                                <!-- PRODUCT SEARCH POPUP -->
                                <div id="productSearchModal" class="product-search-overlay" style="display: none;">

                                    <div class="product-search-box">

                                        <div class="product-search-header">

                                            <h3>Select Product</h3>

                                            <button
                                                type="button"
                                                id="closeProductSearch"
                                                class="product-search-close">
                                                ×
                                            </button>

                                        </div>

                                        <input
                                            type="text"
                                            id="productSearchInput"
                                            class="product-search-input"
                                            placeholder="Search product name..."
                                            autocomplete="off">

                                        <div id="productSearchResults" class="product-search-results">

                                            <!-- Products will appear here -->

                                        </div>

                                    </div>

                                </div>     
                                
                                <!-- Order Confirmation Modal -->
                                <div id="orderConfirmationModal" class="product-search-overlay" style="display: none;">

                                    <div class="order-confirmation-box">

                                        <div class="order-confirmation-header">

                                            <h3>Review Order</h3>

                                            <button
                                                type="button"
                                                id="closeOrderConfirmation"
                                                class="product-search-close">
                                                ×
                                            </button>

                                        </div>

                                        <p class="order-confirmation-message">
                                            Please review the order details below before placing the order.
                                        </p>

                                        <div id="order-confirmation-summary">

                                            <!-- Summary will be generated by JavaScript -->

                                        </div>

                                        <div class="order-confirmation-warning">

                                            Please make sure the product, supplier, and quantity
                                            are correct before confirming.

                                        </div>

                                        <div class="modal-buttons">

                                            <button
                                                type="button"
                                                id="backToOrderBtn"
                                                class="na-btn na-btn-cancel">

                                                Back

                                            </button>

                                            <button
                                                type="button"
                                                id="confirmFinalOrderBtn"
                                                class="na-btn na-btn-add">

                                                Confirm & Place Order

                                            </button>

                                        </div>

                                    </div>

                                </div>                                

                            </div>
                        </div>
                        
                        <link rel="stylesheet" href="assets/add_product.css">
                        <!-- <script src="assets/add_order.js" defer></script> -->
                        <!-- <script src="get_product.php" defer></script> -->
                        <!-- <script src="get/get_supplierORDRES.js" defer></script>
                        <script src="get/get_productORDRES.js" defer></script> -->

                        <script src="assets/add_order.js" defer></script>
                        <script src="assets/restock_items.js" defer></script>
                        <script src="order_confirmation.js"></script>

                        


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

                                <form id="restockForm" action="add.php" method="POST">
                                    <input type="hidden" name="table" value="restock">

                                    <label for="Product_IDORDRES">Choose Product ID:</label>
                                    <select id="Product_IDORDRES" name="Product_ID" class="id-select" style="width: 60%;" required>
                                        <option value="" disabled selected>Loading...</option>
                                    </select>

                                    <label for="restock-pack-size">Pack Size:</label>
                                    <input type="text" id="restock-pack-size" readonly placeholder="Select a product first" style="width: 60%;">

                                    <label for="Supplier_IDORDRES">Choose Supplier ID:</label>
                                    <select id="Supplier_IDORDRES" name="Supplier_ID" class="id-select" style="width: 60%;" required>
                                        <option value="" disabled selected>Loading...</option>
                                    </select>

                                    <label for="restock-quantity">Quantity:</label>
                                    <input type="number" id="restock-quantity" name="Quantity" min="1" required>

                                    <input type="hidden" name="Status" value="Requested">

                                    <div class="modal-buttons">
                                        <button type="submit" class="na-btn na-btn-add">Save</button>
                                        <button type="button" id="ord-cancel-btn" class="na-btn na-btn-cancel">Cancel</button>
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
                                    <!-- <input type="number" name="id" id="update-order-id"> -->
                                    <input type="hidden" name="id" id="update-order-id">

                                    <label>Proof of Transaction:</label>
                                    <input type="file" accept="image/*" name="Image" id="edit-image">

                                    <label>Status::</label>
                                    <!-- <div style="display: flex; justify-content:space-between; align-items:center;"></div>-->
                                    <!-- <select class="status-select" name="Status" id="update-status" required>
                                        <option value="">...</option>
                                        <option value="Requested">Requested</option>
                                        <option value="Out-for-Delivery">Out for Delivery</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Received">Received</option>

                                    </select> -->

                                    <select class="status-select" name="Status" id="update-status" required>

                                        <option value="">...</option>

                                        <option value="Requested">
                                            Requested
                                        </option>

                                        <option value="Out for Delivery">
                                            Out for Delivery
                                        </option>

                                        <option value="Partially-Received">
                                            Partially-Received
                                        </option>

                                        <option value="Backorder-Fulfillment">
                                            Backorder-Fulfillment
                                        </option>

                                        <option value="Cancelled">
                                            Cancelled
                                        </option>

                                        <option value="Received">
                                            Received
                                        </option>

                                    </select>


                                    <!-- <label>Delivery Status::</label>
                                    <select class="status-select" name="DeliveryStatus" id="update-delivery-status">
                                        <option value="">...</option>
                                        <option value="On-Time">On Time</option>
                                        <option value="Delayed">Delayed</option>
                                        <option value="Early">Early</option>
                                    </select> -->

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
                                    <!-- <input type="date" name="ExpirationDate" id="update-datereceived" style="width: 50%;" required> -->
                                    <input type="date" name="ExpirationDate" id="update-expiration-date" style="width: 50%;">
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
                        <script src="order_confirmation.js"></script>
                        
