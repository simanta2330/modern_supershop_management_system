<?php
session_start();
include('db.php');

if(!isset($_SESSION['role']) || $_SESSION['role']!='admin'){
    header("Location: admin.php");
    exit();
}


$total_orders = $conn->query("
    SELECT COUNT(*) AS total 
    FROM sales
")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html>
<head>

<title>Sales Report</title>

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

.card-box{
    background:white;
    border-radius:15px;
    padding:20px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
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

<h2 class="mb-4">Sales Report</h2>


<div class="row mb-4">

<div class="col-md-4">

<div class="card-box">

<h4>Total Orders</h4>

<h2 class="text-success">
    <?= $total_orders ?>
</h2>

</div>

</div>

</div>


<h4 class="mb-3"> Sales Records</h4>

<table class="table table-bordered bg-white text-center">

<tr class="table-dark">

<th>Invoice ID</th>
<th>Customer Name</th>
<th>User ID</th>

</tr>

<?php

$res = $conn->query("
    SELECT 
        sales.sale_id,
        sales.user_id,
        users.full_name
    FROM sales
    JOIN users
    ON sales.user_id = users.user_id
    ORDER BY sales.sale_id DESC
");

while($r = $res->fetch_assoc()){

?>

<tr>

<td>
    INV-<?= $r['sale_id'] ?>
</td>

<td>
    <?= $r['full_name'] ?>
</td>

<td>
    <?= $r['user_id'] ?>
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