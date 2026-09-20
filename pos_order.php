<?php

/* =========================================================
   DATABASE CONNECTION
   ========================================================= */

$host = "localhost";
$username = "root";
$password = "";
$database = "YOUR_DATABASE_NAME";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


/* =========================================================
   INSERT ORDER
   ========================================================= */

$message = "";

if (isset($_POST['confirm_order'])) {

    $current_stock = $_POST['current_stock'];
    $predicted_demand = $_POST['predicted_demand'];
    $order_amount = $_POST['order_amount'];
    $supplier = $_POST['supplier'];

    $sql = "INSERT INTO ToOrder
            (Current_Stock, Predicted_Demand, Order_Amount, Supplier)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iiii",
        $current_stock,
        $predicted_demand,
        $order_amount,
        $supplier
    );

    if ($stmt->execute()) {

        $message = "Order successfully added!";

    } else {

        $message = "Error: " . $conn->error;

    }

    $stmt->close();
}


/* =========================================================
   GET EXISTING ORDERS
   ========================================================= */

$result = $conn->query("SELECT * FROM ToOrder ORDER BY ToOrder DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>To Order</title>


    <style>

        /* ================================================
           PAGE
           ================================================ */

        body {
            margin: 0;

            font-family: Arial, sans-serif;

            background-color: #f5f5f5;
        }


        /* ================================================
           OPEN MODAL BUTTON
           ================================================ */

        .open-button {

            margin: 30px;

            padding: 12px 25px;

            background-color: white;

            border: 2px solid #f26b2b;

            font-size: 18px;

            cursor: pointer;
        }

        .open-button:hover {

            background-color: #f26b2b;

            color: white;
        }


        /* ================================================
           MODAL BACKGROUND
           ================================================ */

        .modal {

            display: none;

            position: fixed;

            z-index: 1000;

            left: 0;

            top: 0;

            width: 100%;

            height: 100%;

            background-color: rgba(0, 0, 0, 0.35);

            justify-content: center;

            align-items: center;
        }


        /* ================================================
           MODAL BOX
           ================================================ */

        .modal-content {

            width: 1080px;

            min-height: 560px;

            background-color: white;

            border: 2px solid #f26b2b;

            padding: 15px;

            position: relative;

            box-sizing: border-box;
        }


        /* ================================================
           CLOSE BUTTON
           ================================================ */

        .close {

            position: absolute;

            right: 15px;

            top: 8px;

            font-size: 28px;

            cursor: pointer;

            color: #f26b2b;
        }


        /* ================================================
           SQL QUERY BOX
           ================================================ */

        .sql-query {

            width: 305px;

            border: 2px solid #f26b2b;

            padding: 5px;

            margin-bottom: 15px;

            font-size: 21px;

            line-height: 30px;

            text-align: center;

            box-sizing: border-box;
        }


        /* ================================================
           TABLE CONTAINER
           ================================================ */

        .order-container {

            width: 100%;

            min-height: 485px;

            border: 2px solid #f26b2b;

            padding: 15px;

            box-sizing: border-box;

            position: relative;
        }


        /* ================================================
           TABLE
           ================================================ */

        .order-table {

            width: 100%;

            border-collapse: collapse;

            font-size: 21px;

            text-align: center;
        }


        .order-table th,
        .order-table td {

            border: 2px solid #f26b2b;

            padding: 8px;

            height: 27px;
        }


        .order-table th {

            font-weight: normal;
        }


        /* ================================================
           INPUT BOXES
           ================================================ */

        .order-table input,
        .order-table select {

            width: 90px;

            height: 32px;

            border: 2px solid #f26b2b;

            font-size: 18px;

            text-align: center;

            box-sizing: border-box;
        }


        /* ================================================
           CONFIRM BUTTON
           ================================================ */

        .confirm-container {

            position: absolute;

            right: 23px;

            bottom: 20px;
        }


        .confirm-button {

            width: 220px;

            height: 48px;

            background-color: white;

            border: 2px solid #f26b2b;

            font-size: 21px;

            cursor: pointer;
        }


        .confirm-button:hover {

            background-color: #f26b2b;

            color: white;
        }


        /* ================================================
           MESSAGE
           ================================================ */

        .message {

            margin: 20px 30px;

            padding: 12px;

            border: 2px solid #f26b2b;

            background-color: white;

            font-size: 18px;
        }

    </style>

</head>


<body>


<?php

if ($message != "") {

    echo '<div class="message">' . htmlspecialchars($message) . '</div>';

}

?>


<!-- =====================================================
     OPEN MODAL BUTTON
     ===================================================== -->

<button class="open-button" onclick="openModal()">

    Create Order

</button>



<!-- =====================================================
     MODAL
     ===================================================== -->

<div id="orderModal" class="modal">


    <div class="modal-content">


        <!-- CLOSE BUTTON -->

        <span class="close" onclick="closeModal()">

            &times;

        </span>



        <!-- SQL QUERY DISPLAY -->

        <div class="sql-query">

            Select * from sales where<br>

            transaction id = ?

        </div>



        <!-- TABLE -->

        <div class="order-container">


            <form method="POST">


                <table class="order-table">


                    <thead>

                        <tr>

                            <th>Product id</th>

                            <th>Current stock</th>

                            <th>Predicted demand</th>

                            <th>Order amount</th>

                            <th>Supplier id</th>

                        </tr>

                    </thead>


                    <tbody>


                        <tr>

                            <!-- Product ID -->

                            <td>

                                1

                            </td>


                            <!-- Current Stock -->

                            <td>

                                <input
                                    type="number"
                                    name="current_stock"
                                    value="12"
                                    required
                                >

                            </td>


                            <!-- Predicted Demand -->

                            <td>

                                <input
                                    type="number"
                                    name="predicted_demand"
                                    value="12"
                                    required
                                >

                            </td>


                            <!-- Order Amount -->

                            <td>

                                <input
                                    type="number"
                                    name="order_amount"
                                    value="12"
                                    required
                                >

                            </td>


                            <!-- Supplier -->

                            <td>

                                <select
                                    name="supplier"
                                    required
                                >

                                    <option value="1">1</option>

                                    <option value="2">2</option>

                                    <option value="3">3</option>

                                    <option value="4">4</option>

                                </select>

                            </td>

                        </tr>


                    </tbody>


                </table>



                <!-- CONFIRM BUTTON -->

                <div class="confirm-container">

                    <button
                        type="submit"
                        name="confirm_order"
                        class="confirm-button"
                    >

                        Confirm Order

                    </button>

                </div>


            </form>


        </div>


    </div>


</div>



<script>


/* ========================================================
   OPEN MODAL
   ======================================================== */

function openModal() {

    document.getElementById("orderModal").style.display = "flex";

}


/* ========================================================
   CLOSE MODAL
   ======================================================== */

function closeModal() {

    document.getElementById("orderModal").style.display = "none";

}


/* ========================================================
   CLOSE WHEN CLICKING OUTSIDE
   ======================================================== */

window.onclick = function(event) {

    let modal = document.getElementById("orderModal");

    if (event.target === modal) {

        modal.style.display = "none";

    }

};


</script>


</body>

</html>


<?php

$conn->close();

?>