<?php
session_start();
include('db.php');

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
<title>Cart</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: url('img/bg.jpg') no-repeat center center fixed;
    background-size: cover;
}
.overlay {
    background: rgba(255,255,255,0.8);
    min-height: 100vh;
    padding: 20px;
}
</style>
</head>
<body>

<div class="overlay">
<div class="container">

<h2 class="text-center mb-4"> Your Cart</h2>

<table class="table table-bordered bg-white text-center">
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Action</th>
</tr>

<?php
$grand_total = 0;

foreach($cart as $id => $qty){

    $res = $conn->query("SELECT * FROM products WHERE product_id=$id");
    $p = $res->fetch_assoc();

    if($p){
        $total = $p['selling_price'] * $qty;
        $grand_total += $total;
?>

<tr>
    <td><?= $p['product_name'] ?></td>
    <td><?= $p['selling_price'] ?> ৳</td>
    <td><?= $qty ?></td>
    <td><?= $total ?> ৳</td>
    <td>
        <a href="update_cart.php?id=<?= $id ?>&action=inc" class="btn btn-sm btn-success">+</a>
        <a href="update_cart.php?id=<?= $id ?>&action=dec" class="btn btn-sm btn-warning">-</a>
        <a href="remove.php?id=<?= $id ?>" class="btn btn-sm btn-danger">x</a>
    </td>
</tr>

<?php }} ?>

<tr>
    <td colspan="3"><b>Grand Total</b></td>
    <td colspan="2"><b><?= $grand_total ?> ৳</b></td>
</tr>

</table>

<div class="text-center">
<a href="checkout.php" class="btn btn-primary">Checkout</a>
<a href="index.php" class="btn btn-dark">Buy More Products</a>
</div>

</div>
</div>

</body>
</html>