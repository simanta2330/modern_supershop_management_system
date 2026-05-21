<?php
session_start();
include('db.php');

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: admin.php");
    exit();
}

$res = $conn->query("SELECT * FROM products");

$total_profit = 0;

?>

<!DOCTYPE html>
<html>
<head>

<title>Profit Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background: url('img/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}

.overlay{
    background: rgba(0,0,0,0.75);
    min-height:100vh;
    padding:20px;
}

.report-box{
    background: rgba(255,255,255,0.97);
    border-radius:20px;
    padding:30px;
}

.table{
    border-radius:10px;
    overflow:hidden;
}

.btn{
    border-radius:25px;
}

</style>

</head>

<body>

<div class="overlay">

<div class="container">

<div class="report-box">

<h2 class="mb-4"> Profit Report</h2>

<table class="table table-bordered text-center bg-white">

<tr class="table-dark">

<th>Product</th>
<th>Buying Price</th>
<th>Selling Price</th>
<th>Profit Per Product (Per KG/Pics)</th>

</tr>

<?php while($r = $res->fetch_assoc()){ 

$profit = $r['selling_price'] - $r['buying_price'];

$total_profit += $profit;

?>

<tr>

<td>
    <?= $r['product_name'] ?>
</td>

<td>
    <?= $r['buying_price'] ?> ৳
</td>

<td>
    <?= $r['selling_price'] ?> ৳
</td>

<td class="text-success fw-bold">
    <?= $profit ?> ৳
</td>

</tr>

<?php } ?>

</table>


<div class="alert alert-success mt-4">

<h4>
    Total Estimated Profit: <?= $total_profit ?> ৳
</h4>

</div>


<div class="mt-4">

<a href="admin.php" class="btn btn-dark">
     Back To Admin
</a>

<button onclick="window.print()" class="btn btn-success">
    Print Report
</button>

</div>

</div>

</div>

</div>

</body>
</html>