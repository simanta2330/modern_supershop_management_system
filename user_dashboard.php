<?php
session_start();
include('db.php');

$id=$_SESSION['user_id'] ?? 1;

$res=$conn->query("SELECT * FROM sales WHERE customer_id=$id");

echo "<h2>My Orders</h2>";

while($row=$res->fetch_assoc()){
    echo "Order ID: ".$row['id']."<br>";
}
<br><br>
<a href="/supershop/index.php"> Back to Home</a>