<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('db.php');

$secret_admin_key = "SUPERADMIN123";


if(isset($_POST['create_admin'])){

    $name  = trim($_POST['admin_name']);
    $email = trim($_POST['admin_email']);
    $pass  = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
    $key   = trim($_POST['admin_key']);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if($key != $secret_admin_key){

        $error = " Invalid Secret Admin Key!";

    }
    else if($check->num_rows > 0){

        $error = " Email already exists!";

    } 
    else {

        $username = explode('@', $email)[0];

        $conn->query("
            INSERT INTO users(full_name,username,email,password_hash,role,is_active)
            VALUES('$name','$username','$email','$pass','admin',1)
        ");

        $success = " Admin account created successfully!";
    }
}


if(isset($_POST['admin_login'])){

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $res->fetch_assoc();

    if($user && password_verify($pass, $user['password_hash']) && $user['role']=='admin'){

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role']    = 'admin';

        header("Location: admin.php");
        exit();

    } 
    else {

        $error = "Invalid Admin Credentials!";
    }
}


if(isset($_GET['logout'])){

    session_destroy();

    header("Location: index.php");
    exit();
}


if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Panel</title>

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
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.box{
    width:430px;
    background:rgba(255,255,255,0.97);
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

.title{
    text-align:center;
    font-weight:bold;
    margin-bottom:20px;
}

input{
    border-radius:10px !important;
}

.btn{
    border-radius:25px;
}

.small-text{
    text-align:center;
    font-size:14px;
    color:gray;
}

</style>

</head>

<body>

<div class="overlay">

<div class="box">

<h3 class="title"> Admin Panel Access</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?= $success ?></div>
<?php } ?>


<h5 class="mb-3"> Admin Login</h5>

<form method="post">

<input type="email"
       name="email"
       class="form-control mb-3"
       placeholder="Enter Admin Email"
       required>

<input type="password"
       name="password"
       class="form-control mb-3"
       placeholder="Enter Password"
       required>

<button name="admin_login" class="btn btn-dark w-100">
    Login
</button>

</form>


<hr class="my-4">

<h5 class="mb-3"> Create New Admin</h5>

<form method="post">

<input type="text"
       name="admin_name"
       class="form-control mb-2"
       placeholder="Full Name"
       required>

<input type="email"
       name="admin_email"
       class="form-control mb-2"
       placeholder="Admin Email"
       required>

<input type="password"
       name="admin_password"
       class="form-control mb-2"
       placeholder="Password"
       required>

<input type="password"
       name="admin_key"
       class="form-control mb-3"
       placeholder="Secret Admin Key"
       required>

<button name="create_admin" class="btn btn-success w-100">
    Create Admin Account
</button>

</form>

<p class="small-text mt-3">
 Only authorized users can create admin accounts
</p>

<div class="text-center mt-4">

<a href="index.php" class="btn btn-primary btn-sm">
     Back To Shop
</a>

</div>

</div>

</div>

</body>
</html>

<?php
exit();
}


$page = $_GET['page'] ?? 'dashboard';

if(isset($_POST['add_product'])){

    $name    = $_POST['product_name'];
    $price   = $_POST['selling_price'];
    $buying  = $_POST['buying_price'];
    $cat     = $_POST['category_id'];
    $img     = $_POST['image'];

    $conn->query("
        INSERT INTO products(
            product_name,
            selling_price,
            buying_price,
            category_id,
            image
        )
        VALUES(
            '$name',
            '$price',
            '$buying',
            '$cat',
            '$img'
        )
    ");
}


if(isset($_POST['add_category'])){

    $name = $_POST['category_name'];

    $conn->query("
        INSERT INTO categories(category_name)
        VALUES('$name')
    ");
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:url('img/bg.jpg') no-repeat center center fixed;
    background-size:cover;
    font-family:Arial, sans-serif;
}

.overlay{
    background:rgba(0,0,0,0.75);
    min-height:100vh;
    padding:20px;
}

.panel-box{
    background:rgba(255,255,255,0.97);
    border-radius:20px;
    padding:25px;
}

.navbar-custom a{
    color:white;
    text-decoration:none;
    margin-right:10px;
    margin-bottom:10px;
    padding:10px 15px;
    background:#222;
    border-radius:8px;
    display:inline-block;
}

.navbar-custom a:hover{
    background:#444;
}

.card-box{
    background:white;
    border-radius:15px;
    padding:25px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    height:100%;
}

.table{
    border-radius:10px;
    overflow:hidden;
}

</style>

</head>

<body>

<div class="overlay">

<div class="container">

<div class="panel-box">

<h2 class="mb-4"> Admin Dashboard</h2>

<div class="navbar-custom mb-4">

    <a href="admin.php"> Dashboard</a>

    <a href="admin.php?page=products"> Products</a>

    <a href="admin.php?page=categories"> Categories</a>

    <a href="admin.php?page=sales"> Sales</a>

    <a href="sales_tracking.php">Sales Tracking</a>

    <a href="supplier_management.php"> Suppliers</a>

    <a href="sales_report.php"> Sales Report</a>

    <a href="profit_report.php"> Profit Report</a>

    <a href="stock_report.php"> Stock Report</a>

    <a href="index.php"> Shop</a>

    <a href="admin.php?logout=1" style="background:red;">
         Logout
    </a>

</div>


<?php if($page=='dashboard'){ ?>

<div class="row g-4">

<div class="col-md-3">
<div class="card-box">

<h4> Products</h4>

<a href="admin.php?page=products" class="btn btn-primary mt-2">
    Manage
</a>

</div>
</div>


<div class="col-md-3">
<div class="card-box">

<h4> Categories</h4>

<a href="admin.php?page=categories" class="btn btn-success mt-2">
    Manage
</a>

</div>
</div>


<div class="col-md-3">
<div class="card-box">

<h4> Sales</h4>

<a href="admin.php?page=sales" class="btn btn-warning mt-2">
    View
</a>

</div>
</div>


<div class="col-md-3">
<div class="card-box">

<h4> Tracking</h4>

<a href="sales_tracking.php" class="btn btn-info mt-2">
    Open
</a>

</div>
</div>


<div class="col-md-3 mt-4">
<div class="card-box">

<h4> Suppliers</h4>

<a href="supplier_management.php" class="btn btn-secondary mt-2">
    Manage
</a>

</div>
</div>


<div class="col-md-3 mt-4">
<div class="card-box">

<h4> Reports</h4>

<a href="sales_report.php" class="btn btn-success mt-2 mb-2 w-100">
    Sales Report
</a>

<a href="profit_report.php" class="btn btn-warning mt-2 mb-2 w-100">
    Profit Report
</a>

<a href="stock_report.php" class="btn btn-dark mt-2 w-100">
    Stock Report
</a>

</div>
</div>


<div class="col-md-3 mt-4">
<div class="card-box">

<h4> Website</h4>

<a href="index.php" class="btn btn-dark mt-2">
    Visit
</a>

</div>
</div>

</div>

<?php } ?>


<?php if($page=='products'){ 

$cats = $conn->query("SELECT * FROM categories");

$search = $_GET['search'] ?? '';

$res = $conn->query("
    SELECT * FROM products
    WHERE product_name LIKE '%$search%'
");

?>

<h4 class="mb-3"> Add Product</h4>


<form method="GET" class="mb-3">

<input type="hidden" name="page" value="products">

<input type="text"
       name="search"
       class="form-control"
       placeholder="🔍 Search Product..."
       value="<?= $search ?>">

</form>


<form method="post" class="mb-4">

<input name="product_name"
       class="form-control mb-2"
       placeholder="Product Name"
       required>

<input name="selling_price"
       class="form-control mb-2"
       placeholder="Selling Price"
       required>

<input name="buying_price"
       class="form-control mb-2"
       placeholder="Buying Price"
       required>

<select name="category_id" class="form-control mb-2">

<?php while($c=$cats->fetch_assoc()){ ?>

<option value="<?= $c['category_id'] ?>">
    <?= $c['category_name'] ?>
</option>

<?php } ?>

</select>

<input name="image"
       class="form-control mb-3"
       placeholder="image.jpg">

<button name="add_product" class="btn btn-success w-100">
    Add Product
</button>

</form>


<h4> All Products</h4>

<table class="table table-bordered text-center bg-white">

<tr class="table-dark">

<th>Product</th>
<th>Selling Price</th>
<th>Buying Price</th>

</tr>

<?php while($r=$res->fetch_assoc()){ ?>

<tr>

<td><?= $r['product_name'] ?></td>

<td><?= $r['selling_price'] ?> ৳</td>

<td><?= $r['buying_price'] ?> ৳</td>

</tr>

<?php } ?>

</table>

<?php } ?>


<?php if($page=='categories'){ 

$res = $conn->query("SELECT * FROM categories");

?>

<h4> Add Category</h4>

<form method="post" class="mb-3">

<input name="category_name"
       class="form-control mb-2"
       placeholder="Category Name"
       required>

<button name="add_category" class="btn btn-primary">
    Add Category
</button>

</form>

<table class="table table-bordered bg-white">

<tr>
<th>Category Name</th>
</tr>

<?php while($r=$res->fetch_assoc()){ ?>

<tr>

<td><?= $r['category_name'] ?></td>

</tr>

<?php } ?>

</table>

<?php } ?>


<?php if($page=='sales'){ 

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

?>

<h4> Sales Records</h4>

<table class="table table-bordered bg-white text-center">

<tr class="table-dark">

<th>Invoice ID</th>
<th>Customer Name</th>
<th>User ID</th>

</tr>

<?php while($r=$res->fetch_assoc()){ ?>

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

<?php } ?>

</div>

</div>

</div>

</body>
</html>