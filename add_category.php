<?php
include('db.php');

if($_POST){
$name=$_POST['name'];
$conn->query("INSERT INTO categories(name) VALUES('$name')");
echo "Added!";
}
?>

<form method="post">
Category: <input name="name">
<button>Add</button>
</form>
<br><br>
<a href="/supershop/index.php"> Back to Home</a>