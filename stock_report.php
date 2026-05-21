<?php
session_start();
include('db.php');

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: admin.php");
    exit();
}

$res = $conn->query("SELECT * FROM products");

?>

<!DOCTYPE html>
<html>
<head>

<title>Stock Report</title>

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

<h2 class="mb-4"> Stock Report</h2>

<table class="table table-bordered text-center bg-white">

<tr class="table-dark">

<th>Product</th>
<th>Stock Quantity</th>
<th>Status</th>

</tr>

<?php while($r = $res->fetch_assoc()){ ?>

<tr>

<td>
    <?= $r['product_name'] ?>
</td>

<td>
    <?= $r['stock_qty'] ?>
</td>

<td>

<?php if($r['stock_qty'] < 5){ ?>

<span class="badge bg-danger">
    Low Stock
</span>

<?php } else { ?>

<span class="badge bg-success">
    Available
</span>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>

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