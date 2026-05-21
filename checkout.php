<?php
session_start();
include('db.php');

if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect'] = "checkout.php";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    echo "<h3> Cart is empty!</h3>";
    echo "<a href='index.php'> Go Home</a>";
    exit();
}

$user = $conn->query("SELECT * FROM users WHERE user_id=$user_id")->fetch_assoc();

$total = 0;
$cart_items = [];

foreach($cart as $pid => $qty){
    $res = $conn->query("SELECT * FROM products WHERE product_id=$pid");
    $p = $res->fetch_assoc();
    if($p){
        $total += $p['selling_price'] * $qty;
        $cart_items[$pid] = [
            'product' => $p,
            'qty' => $qty,
            'price' => $p['selling_price']
        ];
    }
}

$error = '';
$success = false;

if(isset($_POST['place_order'])){
    $payment_method = $_POST['payment_method'];
    $transaction_id = $_POST['transaction_id'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $amount_paid = $_POST['amount_paid'] ?? $total;
    
    
    if($payment_method == 'bKash' || $payment_method == 'Nagad'){
        if(empty($transaction_id)){
            $error = " Transaction ID is required for " . $payment_method;
        }
        if(empty($mobile_number)){
            $error = " Mobile number is required for " . $payment_method;
        }
    }
    
    if(empty($error)){
        $change_due = $amount_paid - $total;
        
        $conn->begin_transaction();
        
        try {
            $stmt = $conn->prepare("INSERT INTO sales(user_id, subtotal, grand_total, amount_paid, change_due, payment_method, note, sale_date) 
                                    VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
            $note = $payment_method == 'bKash' ? "TXID: $transaction_id | Mobile: $mobile_number" : ($payment_method == 'Nagad' ? "TXID: $transaction_id | Mobile: $mobile_number" : "");
            $stmt->bind_param("iddddss", $user_id, $total, $total, $amount_paid, $change_due, $payment_method, $note);
            $stmt->execute();
            $sale_id = $conn->insert_id;
            
            foreach($cart_items as $pid => $item){
                $stmt2 = $conn->prepare("INSERT INTO sale_items(sale_id, product_id, qty, price) VALUES(?, ?, ?, ?)");
                $stmt2->bind_param("iiid", $sale_id, $pid, $item['qty'], $item['price']);
                $stmt2->execute();
                
                
                $stmt3 = $conn->prepare("UPDATE products SET stock_qty = stock_qty - ? WHERE product_id = ?");
                $stmt3->bind_param("ii", $item['qty'], $pid);
                $stmt3->execute();
            }
            
            $conn->commit();
            unset($_SESSION['cart']);
            
            header("Location: receipt.php?sale_id=" . $sale_id);
            exit();
            
        } catch(Exception $e){
            $conn->rollback();
            $error = " Order failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .overlay {
            background: rgba(0, 0, 0, 0.6);
            min-height: 100vh;
            padding: 40px 0;
        }
        .checkout-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .checkout-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            border-left: 4px solid #667eea;
            padding-left: 12px;
            margin: 20px 0 15px 0;
        }
        .payment-option {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        .payment-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
            transform: translateY(-2px);
        }
        .payment-option.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            box-shadow: 0 5px 15px rgba(102,126,234,0.2);
        }
        .payment-icon {
            font-size: 40px;
            margin-bottom: 8px;
        }
        .payment-name {
            font-weight: bold;
            margin: 5px 0;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 50px;
            width: 100%;
            margin-top: 20px;
        }
        .btn-checkout:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 20px rgba(102,126,234,0.4);
        }
        .product-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .total-amount {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 12px;
            font-size: 20px;
            font-weight: bold;
        }
        .form-control-custom {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px;
        }
    </style>
</head>
<body>
<div class="overlay">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">

<div class="checkout-card">
    <div class="checkout-header">
        <h2><i class="fas fa-shopping-bag"></i> Checkout</h2>
        <p class="mb-0">Complete your purchase</p>
    </div>
    
    <div class="p-4">
        <?php if($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
        <?php endif; ?>
        
        
        <div class="section-title"><i class="fas fa-receipt"></i> Order Summary</div>
        <div class="mb-3">
            <?php foreach($cart_items as $item): ?>
            <div class="product-item d-flex justify-content-between">
                <span><strong><?= htmlspecialchars($item['product']['product_name']) ?></strong> x <?= $item['qty'] ?></span>
                <span><?= $item['price'] * $item['qty'] ?> ৳</span>
            </div>
            <?php endforeach; ?>
            <div class="total-amount d-flex justify-content-between mt-3">
                <span><i class="fas fa-money-bill-wave"></i> Total Amount:</span>
                <span><?= $total ?> ৳</span>
            </div>
        </div>
        
        <form method="post" id="checkoutForm">
           
            <div class="section-title"><i class="fas fa-credit-card"></i> Payment Method</div>
            <div class="row">
                <div class="col-md-4">
                    <div class="payment-option" data-method="cash">
                        <div class="payment-icon"><i class="fas fa-money-bill-wave" style="color:#2ecc71;"></i></div>
                        <div class="payment-name">Cash</div>
                        <small>Pay with cash</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="payment-option" data-method="bKash">
                        <div class="payment-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8b/BKash_Logo.svg/200px-BKash_Logo.svg.png" width="50" alt="bKash"></div>
                        <div class="payment-name">bKash</div>
                        <small>Mobile banking</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="payment-option" data-method="Nagad">
                        <div class="payment-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Nagad_Logo.svg/200px-Nagad_Logo.svg.png" width="50" alt="Nagad"></div>
                        <div class="payment-name">Nagad</div>
                        <small>Mobile banking</small>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="payment_method" id="payment_method" value="cash">
            
            
            <div id="mobilePaymentDetails" style="display: none;">
                <div class="section-title"><i class="fas fa-mobile-alt"></i> Payment Details</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label><i class="fas fa-hashtag"></i> Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control form-control-custom" placeholder="e.g., TRX123456789">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label><i class="fas fa-phone"></i> Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control form-control-custom" placeholder="01XXXXXXXXX">
                    </div>
                </div>
            </div>
            
           
            <div class="section-title"><i class="fas fa-coins"></i> Payment Amount</div>
            <div class="mb-3">
                <input type="number" name="amount_paid" class="form-control form-control-custom" value="<?= $total ?>" required>
                <small class="text-muted">Enter the amount you are paying</small>
            </div>
            
            <button type="submit" name="place_order" class="btn btn-checkout">
                <i class="fas fa-check-circle"></i> Place Order
            </button>
            
            <div class="text-center mt-3">
                <a href="cart.php" class="text-decoration-none"><i class="fas fa-arrow-left"></i> Back to Cart</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</div>
</div>

<script>
   
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            let method = this.getAttribute('data-method');
            document.getElementById('payment_method').value = method;
            
            if(method === 'bKash' || method === 'Nagad') {
                document.getElementById('mobilePaymentDetails').style.display = 'block';
            } else {
                document.getElementById('mobilePaymentDetails').style.display = 'none';
            }
        });
    });
    
    document.querySelector('.payment-option[data-method="cash"]').classList.add('selected');
</script>

</body>
</html>