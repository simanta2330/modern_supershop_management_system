<?php
session_start();
include('db.php');

if(!isset($_SESSION['user_id'])){

    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT * FROM sales
    WHERE user_id = '$user_id'
    ORDER BY sale_id DESC
");

?>

<!DOCTYPE html>
<html>
<head>

<title>My Orders</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:url('img/bg.jpg') no-repeat center center fixed;
    background-size:cover;
    font-family:Arial, sans-serif;
}

.overlay{
    background:rgba(0,0,0,0.75);
    min-height:100vh;
    padding:20px;
}

.order-box{
    background:rgba(255,255,255,0.97);
    border-radius:20px;
    padding:25px;
}

.card-order{
    background:white;
    border-radius:15px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.order-title{
    font-weight:bold;
    margin-bottom:15px;
}

.item{
    padding:8px 0;
    border-bottom:1px solid #eee;
}

.badge-status{
    font-size:14px;
    padding:8px 12px;
    border-radius:20px;
}

.btn{
    border-radius:25px;
}

.info-box{
    background:#f8f9fa;
    padding:15px;
    border-radius:10px;
}

</style>

</head>

<body>

<div class="overlay">

<div class="container">

<div class="order-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>
     My Orders
</h2>

<a href="index.php" class="btn btn-dark">
     Back To Shop
</a>

</div>


<?php

if($orders->num_rows == 0){

    echo "
    <div class='alert alert-warning'>
        No orders found!
    </div>
    ";
}

while($order = $orders->fetch_assoc()){

?>

<div class="card-order">

<div class="row">

<div class="col-md-6">

<h4 class="order-title">

    Invoice ID:
    INV-<?= $order['sale_id'] ?>

</h4>

<div class="info-box">

<p>
<b>Subtotal:</b>
<?= $order['subtotal'] ?? 0 ?> ৳
</p>

<p>
<b>Discount:</b>
<?= $order['discount'] ?? 0 ?> ৳
</p>

<p>
<b>Tax:</b>
<?= $order['tax'] ?? 0 ?> ৳
</p>

<p>
<b>Grand Total:</b>
<?= $order['grand_total'] ?? 0 ?> ৳
</p>

<p>
<b>Paid:</b>
<?= $order['amount_paid'] ?? 0 ?> ৳
</p>

<p>
<b>Change:</b>
<?= $order['change_due'] ?? 0 ?> ৳
</p>

<p>
<b>Payment:</b>
<?= $order['payment_method'] ?? 'Cash' ?>
</p>

<p>
<b>Invoice Date:</b>

<?php 
if(!empty($order['sale_date'])){

    echo date("d M Y - h:i A", strtotime($order['sale_date']));

}else{

    echo "N/A";
}
?>

</p>

</div>

</div>


<div class="col-md-6 text-md-end">

<span class="badge bg-success badge-status">
    Order Confirmed
</span>

</div>

</div>


<hr>


<h5 class="mb-3">
     Ordered Items
</h5>


<?php

$sale_id = $order['sale_id'];

$items = $conn->query("
    SELECT 
        sale_items.qty,
        sale_items.price,
        products.product_name
    FROM sale_items
    JOIN products
    ON sale_items.product_id = products.product_id
    WHERE sale_items.sale_id = '$sale_id'
");

while($item = $items->fetch_assoc()){

?>

<div class="item">

 <?= $item['product_name'] ?>

(
<?= $item['qty'] ?> ×
<?= $item['price'] ?> ৳
)

</div>

<?php } ?>

</div>

<?php } ?>


</div>

</div>

</div>

</body>
</html>