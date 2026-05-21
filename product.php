<?php
session_start();
include('db.php');

$res = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Products</title>
</head>
<body>

<h2> All Products</h2>

<?php while($row = $res->fetch_assoc()){ ?>

<div style="border:1px solid black; padding:10px; margin:10px; width:250px;">
    
    <h3><?= $row['product_name'] ?></h3>
    
    <p>Price: <?= $row['selling_price'] ?> ৳</p>
    
    <p>Stock: <?= $row['stock_qty'] ?></p>

    <a href="add_to_cart.php?id=<?= $row['product_id'] ?>">Add to Cart</a>

</div>

<?php } ?>

<br>
<a href="cart.php"> Go to Cart</a> |
<a href="index.php"> Home</a>

</body>
</html>
<br><br>
<a href="/supershop/index.php"> Back to Home</a>