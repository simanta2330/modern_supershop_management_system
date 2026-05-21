<?php
session_start();
include('db.php');

$sale_id = $_GET['sale_id'] ?? 0;

if(!$sale_id){
    header("Location: index.php");
    exit();
}

$sale = $conn->query("SELECT s.*, u.full_name as cashier_name, u.email as user_email, u.username
                       FROM sales s 
                       JOIN users u ON s.user_id = u.user_id 
                       WHERE s.sale_id = $sale_id")->fetch_assoc();

if(!$sale){
    die("Receipt not found!");
}

$items = $conn->query("SELECT si.*, p.product_name 
                        FROM sale_items si 
                        JOIN products p ON si.product_id = p.product_id 
                        WHERE si.sale_id = $sale_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt #<?= $sale_id ?> 🧾</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none; }
            .receipt-card { box-shadow: none; margin: 0; padding: 0; }
            body { background: white; }
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 40px 0;
        }
        .receipt-card {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .receipt-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        .receipt-header h2 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .shop-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .receipt-body {
            padding: 25px;
        }
        .info-row {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-item i {
            width: 25px;
            color: #667eea;
        }
        .items-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #f0f0f0;
            padding: 10px;
            text-align: center;
        }
        .items-table td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .total-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .thankyou {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            margin-top: 20px;
        }
        .thankyou i {
            font-size: 40px;
            color: #2ecc71;
            margin-bottom: 10px;
        }
        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 50px;
            color: white;
            margin: 10px;
        }
        .btn-print:hover {
            transform: scale(1.05);
            color: white;
        }
        .btn-home {
            background: #2c3e50;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 50px;
            color: white;
            margin: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="receipt-card">
        
        <div class="receipt-header">
            <div class="shop-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2> Saudia Super Shop</h2>
            <p class="mb-0">Uttara, Sector 10, Dhaka</p>
            <small><i class="fas fa-phone"></i> 0123456789</small>
        </div>
        
        <div class="receipt-body">
            

            <div class="info-row">
                <div class="info-item"><i class="fas fa-receipt"></i> <strong>Invoice No:</strong> #<?= str_pad($sale_id, 6, '0', STR_PAD_LEFT) ?></div>
                <div class="info-item"><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> <?= date('d/m/Y h:i A', strtotime($sale['sale_date'])) ?></div>
                <div class="info-item"><i class="fas fa-user"></i> <strong>Customer:</strong> <?= htmlspecialchars($sale['cashier_name']) ?></div>
                <div class="info-item"><i class="fas fa-envelope"></i> <strong>Email:</strong> <?= htmlspecialchars($sale['user_email']) ?></div>
                <div class="info-item"><i class="fas fa-user-tag"></i> <strong>Username:</strong> <?= htmlspecialchars($sale['username']) ?></div>
            </div>
            
           
            <table class="items-table">
                <thead>
                    <tr><th>SL</th><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $sl = 1;
                    while($item = $items->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?= $sl++ ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= number_format($item['price'], 2) ?> ৳</td>
                        <td><?= number_format($item['qty'] * $item['price'], 2) ?> ৳</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <!-- Total Section -->
            <div class="total-section">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span><?= number_format($sale['subtotal'], 2) ?> ৳</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount:</span>
                    <span><?= number_format($sale['discount'], 2) ?> ৳</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span><strong>Grand Total:</strong></span>
                    <span><strong><?= number_format($sale['grand_total'], 2) ?> ৳</strong></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Amount Paid:</span>
                    <span><?= number_format($sale['amount_paid'], 2) ?> ৳</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Change Due:</span>
                    <span><?= number_format($sale['change_due'], 2) ?> ৳</span>
                </div>
            </div>
            
          
            <div class="info-row">
                <div class="info-item"><i class="fas fa-credit-card"></i> <strong>Payment Method:</strong> <?= ucfirst($sale['payment_method']) ?></div>
                <?php if($sale['note'] && ($sale['payment_method'] == 'bKash' || $sale['payment_method'] == 'Nagad')): ?>
                <div class="info-item"><i class="fas fa-info-circle"></i> <strong>Transaction Info:</strong> <?= htmlspecialchars($sale['note']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="thankyou">
                <i class="fas fa-heart"></i>
                <h5>Thank You for Shopping!</h5>
                <p>We hope to see you again soon.<br>Please keep this receipt for future reference.</p>
                <small><i class="fas fa-clock"></i> No return after 7 days</small>
            </div>
            
        </div>
    </div>
    
    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Print Receipt
        </button>
        <a href="index.php" class="btn btn-home">
            <i class="fas fa-shopping-bag"></i> Continue Shopping
        </a>
    </div>
</div>

</body>
</html>