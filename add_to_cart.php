<?php
session_start();

if(!isset($_SESSION['user_id'])){
  
    $_SESSION['redirect'] = "add_to_cart.php?id=" . $_GET['id'];

    header("Location: login.php");
    exit();
}


$id = $_GET['id'] ?? 0;

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_SESSION['cart'][$id])){
    $_SESSION['cart'][$id]++;
} else {
    $_SESSION['cart'][$id] = 1;
}

header("Location: cart.php");
exit();