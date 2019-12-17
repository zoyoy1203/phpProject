<?php
header("Content-Type: text/html; charset=utf-8");
session_start();


$c_str = 'User';
if(isset($_GET['c'])){
    $c_str = $_GET['c'];
}
$a_name='login';
if(isset($_GET['a'])){
    $a_name = $_GET['a'];
}
$c_name = $c_str.'Controller';
require("controller/{$c_name}.php");
$controller = new $c_name;
$controller->$a_name();

