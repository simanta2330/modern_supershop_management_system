<?php
include('db.php');

$id=$_GET['id'];

if($_POST){
$name=$_POST['name'];
$price=$_POST['price'];

$conn->query("UPDATE products SET name='$name',price='$price' WHERE id=$id");

echo "Updated!";
}
?>

<form method="post">
Name: <input name="name"><br>
Price: <input name="price"><br>
<button>Update</button>
</form>