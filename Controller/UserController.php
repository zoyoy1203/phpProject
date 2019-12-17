<?php
//require 'config/db_connect.php';

class UserController {
    function __construct(){
        session_start();
    }

    //登录页面
    public function login() {
        $errinfo = '';
        require 'View/login.html';
    }
    //登录操作
    public function loginPost() {
        $link = mysqli_connect("localhost","root","","php_project");
        mysqli_set_charset($link,"utf8");

        if(!empty($_POST)){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM user WHERE username='$username'";
            $res = mysqli_query($link,$sql);
            $r = mysqli_fetch_assoc($res);
            if(empty($r)){
                $errinfo = "用户不存在！";
                require 'View/login.html';
                die();
            }
            if($r['password'] != $password){
                $errinfo = "密码错误！";
                require 'View/login.html';
                die();
            }
//    setcookie('username',$username,time()+600);
            $_SESSION['username'] = $r['username'];

            require 'View/index.html';
//    exit();
        }
    }

    // 注册页面
    public function regis() {
        $errinfo = '';
        require 'View/regis.html';
    }

    // 注册操作
    public function regisPost() {
        $link = mysqli_connect("localhost","root","","php_project");
        mysqli_set_charset($link,"utf8");

        if(!empty($_POST)){

            $username = $_POST['username'];
            $nickname = $_POST['nickname'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            if(empty($password)|| empty($password2)){
                $errinfo = "密码为空,重新输入";
                require 'View/regis.html';
                die();
            }elseif(empty($username) || empty($nickname)){
                $errinfo = "用户名或昵称为空，请重新输入";
                require 'View/regis.html';
                die();
            }elseif($password != $password2){
                $errinfo = "两次密码不一致,请重新输入!";
                require 'View/regis.html';
                die();
            }elseif(strlen($password) < 6 || strlen($password) >12) {
                $errinfo = '密码长度不符合';
                require 'View/regis.html';
                die();
            }

            $sql = "SELECT * FROM user WHERE username='$username'";
            $res = mysqli_query($link,$sql);
            $r = mysqli_fetch_assoc($res);
            // 该用户名没有被注册
            if(empty($r)){
                $regsql = "INSERT INTO user VALUES(NULL,'$username','$password','$nickname')";
                $result = mysqli_query($link,$regsql);
                if($result){
                    $errinfo = "注册成功！";
                    require 'View/login.html';
                    die();
                }else{
                    $errinfo =  '数据库插入错误！';
                    require 'View/regis.html';
                    die();
                }
            }else{
                $errinfo = "用户已存在！";
                require 'View/regis.html';
                die();
            }

            setcookie('username',$username,time()+600);
            $_SESSION['username'] = $r['username'];

        }else{
            $errinfo = "未填写表单！";
            require 'View/regis.html';
            die();
        }

    }
}