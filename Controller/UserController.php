<?php
//require 'config/db_connect.php';

class UserController {
    function __construct(){
     //  数据库连接
        $this->link = mysqli_connect("localhost","root","","php_project");
        mysqli_set_charset($this->link,"utf8");
    }

    //登录页面
    public function login() {
        $errinfo = '';
        require 'View/login.html';
    }
    //登录操作
    public function loginPost() {
        if(!empty($_POST)){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $code = $_POST['code'];

            $sql = "SELECT * FROM user WHERE username='$username'";
            $res = mysqli_query($this->link,$sql);
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
            if($_SESSION['code']!=$code){
                $errinfo = "验证码错误！";
                require 'View/login.html';
                die();
            }
//    setcookie('username',$username,time()+600);
            $_SESSION['username'] = $r['username'];

//            echo $_SESSION['username'];
            require 'View/index.html';
//    exit();

        }
    }
    // 退出登录
    public function logout() {
        $_SESSION = [];
        setcookie(session_name(),'',time()-3000);
        session_destroy();
        require 'View/index.html';

    }


    // 注册页面
    public function regis() {
        $errinfo = '';
        require 'View/regis.html';
    }
    // 注册操作
    public function regisPost() {
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
            $res = mysqli_query($this->link,$sql);
            $r = mysqli_fetch_assoc($res);
            // 该用户名没有被注册
            if(empty($r)){
                $regsql = "INSERT INTO user VALUES(NULL,'$username','$password','$nickname')";
                $result = mysqli_query($this->link,$regsql);
                if($result){
                    $errinfo = "注册成功！请登录！";
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

    // 首页
    public function index() {

//        var_dump($banners[0]["d"]["i"]);
        require 'View/index.html';
    }

    // 菜谱食材分类
    public function foodclass() {
        $ch = curl_init();
        $url = 'http://zyuanyuan.com/foodsApi/recipe/catalogs';
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output=curl_exec($ch);
        $data = json_decode($output,true);
        $foods = $data["result"]["cs"];
//        var_dump($foods[0]["name"]);
        require 'View/foodclass.html';
    }

    // 菜谱食材分类下的菜谱集合
    public function foods() {
        if(isset($_GET['name'])){
            $name = $_GET['name'];
        }
        $ch = curl_init();
        $url = 'http://zyuanyuan.com/foodsApi/recipe/list?keyword='.$name;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output=curl_exec($ch);
        $data = json_decode($output,true);
//        var_dump($data);
        $foods = $data["result"]["list"];
        require('View/foods.html');
    }

    // 菜谱详情
    public function foodDetail() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $ch = curl_init();
        $url = 'http://zyuanyuan.com/foodsApi/recipe/detail?id='.$id;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output=curl_exec($ch);
        $data = json_decode($output,true);
        $food = $data["result"]["recipe"];
        require("View/foodDetail.html");
    }

    // 个人中心
    public function user() {
        require 'View/user.html';
    }

    // 动态
    public function news() {

        require 'View/news.html';
    }

    // 好友列表
    public function friends() {
        require 'View/friends.html';
    }

    // 更多好友
    public function moreFriends() {
        require 'View/moreFriends.html';
    }
}