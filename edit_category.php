<?php
include('db.php');

$id=$_GET['id'];

if($_POST){
$name=$_POST['name'];
$conn->query("UPDATE categories SET name='$name' WHERE id=$id");
echo "Updated!";
}
?>

<form method="post">
Name: <input name="name">
<button>Update</button>
</form>
<br><br>
<a href="/supershop/index.php"> Back to Home</a>