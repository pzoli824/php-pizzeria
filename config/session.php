<?php 
session_start();

if (!isset($_SESSION['user_id'])) $_SESSION['user_id'] = null;
if (!isset($_SESSION['name'])) $_SESSION['name'] = null;
if (!isset($_SESSION['email'])) $_SESSION['email'] = null;
if (!isset($_SESSION['account_type'])) $_SESSION['account_type'] = null;
if (!isset($_SESSION['balance'])) $_SESSION['balance'] = null;
if (!isset($_SESSION['customer_id'])) $_SESSION['customer_id'] = null;
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = serialize(new CartService());

?>