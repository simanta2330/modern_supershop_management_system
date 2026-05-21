<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

$cats = $conn->query("SELECT * FROM categories");

if($_POST){
    $name = $_POST['product_name'];
    $price = $_POST['selling_price'];
    $cat = $_POST['category_id'];

    $conn->query("INSERT INTO products(product_name, selling_price, category_id)
    VALUES('$name', '$price', '$cat')");

    echo " Product Added!";
}
?>

<h2>Add Product</h2>

<form method="post">
    
Product Name: <input name="product_name"><br><br>

Price: <input name="selling_price"><br><br>

Category:
<select name="category_id">

<?php while($c = $cats->fetch_assoc()){ ?>
    <option value="<?= $c['category_id'] ?>">
        <?= $c['category_name'] ?>
    </option>
<?php } ?>

</select>

<br><br>
<button>Add</button>

</form>
<br><br>
<a href="/supershop/index.php"> Back to Home</a>