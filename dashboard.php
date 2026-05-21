<?php
include('db.php');

$res=$conn->query("SELECT COUNT(*) as t FROM sales");
$data=$res->fetch_assoc();

echo "<h2>Admin Dashboard</h2>";
echo "Total Orders: ".$data['t'];

echo "<br><a href='add_product.php'>Add Product</a>";
echo "<br><a href='add_category.php'>Add Category</a>";
<br><br>
<a href="/supershop/index.php"> Back to Home</a>