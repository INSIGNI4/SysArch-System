<?php
include('pos_sale.php');

$receipt_id = $_GET['receipt_id'] ?? null;
if (!$receipt_id) {
    die("Error: No receipt ID provided.");
}

$sale_data = getSale($receipt_id);

if (!$sale_data || !$sale_data['sales']) {
    die("Error: Sale record not found for Receipt ID: " . htmlspecialchars($receipt_id));
}

$sale = $sale_data['sales'];
$customer_data = $sale_data['customer'];
$items = $sale_data['items'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #<?php echo htmlspecialchars($sale['Sales_ID']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 15px;
            color: #000;
        }
        .receipt-box {
            max-width: 300px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 4px 0; }
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; }

        @media print {
            body { padding: 0; }
            .receipt-box { border: none; width: 100%; }
        }
    </style>
</head>
<body>

<div class="receipt-box">
    <div class="text-center">
        <h2>STORE RECEIPT</h2>
        <p>Receipt #: <?php echo htmlspecialchars($sale['Sales_ID']); ?></p>
        <p>Date: <?php echo htmlspecialchars($sale['date_created']); ?></p>
    </div>

    <div class="border-top border-bottom" style="margin: 8px 0; padding: 4px 0;">
        <strong>Customer:</strong> 
        <?php 
            $c_name = trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''));
            echo htmlspecialchars($c_name !== '' ? $c_name : 'Walk-in Customer');
        ?>
    </div>

    <table>
        <thead>
            <tr class="border-bottom">
                <th style="text-align: left;">Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Price</th>
                <!-- <th class="text-right">Total</th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['ProductName']); ?></td>                    
                    <td class="text-center"><?php echo (int)$item['Quantity']; ?></td>
                    <td class="text-right"><?php echo number_format($item['Unit_Price'], 2); ?></td>
                    <!-- <td class="text-right"><?php echo number_format($item['TotalPrice'], 2); ?></td> -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="border-top" style="margin-top: 10px; padding-top: 5px;">
        <table>
            <tr>
                <td><strong>Total Amount:</strong></td>
                <td class="text-right"><strong>₱<?php echo number_format($sale['total_amount'], 2); ?></strong></td>
            </tr>
            <tr>
                <td>Tendered:</td>
                <td class="text-right">₱<?php echo number_format($sale['amount_tendered'], 2); ?></td>
            </tr>
            <tr>
                <td>Change:</td>
                <td class="text-right">₱<?php echo number_format($sale['change_amt'], 2); ?></td>
            </tr>
        </table>
    </div>

    <div class="text-center" style="margin-top: 15px;">
        <p>Thank you for your purchase!</p>
    </div>
</div>

<script>
    // Delay print dialog slightly to ensure the layout is fully rendered
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 200);
    });
</script>

</body>
</html>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #<?= htmlspecialchars($sale['Sales_ID']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px 0;
            margin: 0;
        }

        /* Styles specifically applied when printing */
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            #receiptContainer {
                border: none !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

    <div id="receiptContainer" style="width: 620px;padding-bottom: 50px;padding-top: 20px;border: 1px solid #d0d0d0;margin: 0 auto;border-radius: 5px;padding-left: 10px;padding-right: 10px;background: #fff;">
        <h3 style="font-size: 16px;color: #828282;text-align: right;border-bottom: 1px solid #cccccc;padding-bottom: 10px;margin-top: 10px;">Original Receipt</h3>
        <div>
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td><h3 style="font-size: 23px;text-transform: uppercase;color:#ff5c85;margin:0 0 10px 0;">IMS-POS Project</h3></td>
                    </tr>
                    <tr>
                      <td><span style="font-weight: bold;font-size: 13px;">Address: </span> <span>Philippines</span></td>
                    </tr>
                    <tr>
                       <td><span style="font-weight: bold;font-size: 13px;">City: </span> <span>Manila</span></td>
                    </tr>
                    <tr>
                      <td><span style="font-weight: bold;font-size: 13px;">Postal: </span> <span>9600</span></td>
                    </tr>
                    <tr>
                        <td style="height: 20px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 100%;">
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td width="50%" style="vertical-align: top;">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h3 style="font-size: 15px;text-transform: uppercase;margin: 0 0 5px 0;">Customer Details:</h3>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-weight: bold;font-size: 13px;">Name: </span> 
                                                            <span>
                                                                <?php 
                                                                    $c_name = trim(($customer_data['first_name'] ?? '') . ' ' . ($customer_data['last_name'] ?? ''));
                                                                      echo htmlspecialchars($c_name !== '' ? $c_name : 'Walk-in Customer');
                                                                ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr><td><span style="font-weight: bold;font-size: 13px;">Address: </span> <span><?= htmlspecialchars($customer_data['address'] ?? 'N/A') ?></span></td></tr>
                                                    <tr><td><span style="font-weight: bold;font-size: 13px;">Contact: </span> <span><?= htmlspecialchars($customer_data['contact'] ?? 'N/A') ?></span></td></tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td width="50%" style="vertical-align: top;">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                      <td><h3 style="font-size: 15px;text-transform: uppercase;margin: 0 0 5px 0;">Order Details:</h3></td>
                                                    </tr>
                                                    <tr>
                                                       <td><span style="font-weight: bold;font-size: 13px;">Receipt:</span> #<span><?= htmlspecialchars($sale['Sales_ID']) ?></span></td>
                                                    </tr>
                                                    <tr>
                                                      <td><span style="font-weight: bold;font-size: 13px;">Receipt Date: </span> <span><?= date('M d, Y h:i:s A', strtotime($sale['date_created'])) ?></span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 25px;"></td>
                    </tr>
                </tbody>
            </table>

            <div>
                <h3 style="font-size: 15px;text-transform: uppercase;margin-bottom: 5px;">Items</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>                 
                    <tr>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse; width: 10%;font-size: 12px;font-weight: bold;text-align: center;padding: 5px;">#</td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse; width: 30%;font-size: 12px;font-weight: bold;text-align: center;padding: 5px;">Name</td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse; width: 15%;font-size: 12px;font-weight: bold;text-align: center;padding: 5px;">Qty</td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse; width: 22.5%;font-size: 12px;font-weight: bold;text-align: center;padding: 5px;">Price</td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse; width: 22.5%;font-size: 12px;font-weight: bold;text-align: center;padding: 5px;">Amount</td>
                    </tr>

                    <?php
                        $counter = 1;
                        foreach($items as $item){
                    ?>                  
                    <tr>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 13px;text-align: center;padding: 5px;"><?= $counter ?></td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 13px;text-align: center;padding: 5px;"><?= htmlspecialchars($item['ProductName'] ?? $item['product_name'] ?? 'Unknown Item') ?></td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 13px;text-align: center;padding: 5px;"><?= number_format($item['Quantity']) ?></td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 13px;text-align: center;padding: 5px;">₱ <?= number_format($item['Unit_Price'], 2, '.' , ',') ?></td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 13px;text-align: center;padding: 5px;">₱ <?= number_format($item['TotalPrice'], 2, '.' , ',') ?></td>
                    </tr>
                    <?php $counter++; } ?>
                    <tr>
                        <td style="height: 15px;" colspan="5"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 14px;text-align: center;font-weight: bold; background: #ededed;padding: 5px;">TOTAL</td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 14px;text-align: center;font-weight: bold;padding: 5px;">₱ <?= number_format($sale['total_amount'], 2, '.', ',') ?></td>
                    </tr>
            
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 14px;text-align: center;font-weight: bold; text-transform: uppercase; background: #ededed;padding: 5px;">TENDERED</td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 14px;text-align: center;font-weight: bold; text-transform: uppercase;padding: 5px;">₱ <?= number_format($sale['amount_tendered'], 2, '.', ',') ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>   
                        <td></td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 14px;text-align: center;font-weight: bold; background: #ededed;padding: 5px;">CHANGE</td>
                        <td style="border: 1px solid #bbbbbb; border-collapse: collapse;font-size: 14px;text-align: center;font-weight: bold;padding: 5px;">₱ <?= number_format($sale['change_amt'], 2, '.', ',') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Automatic print trigger on load
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
 -->

