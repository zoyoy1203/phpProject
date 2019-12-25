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
            $_SESSION['userid']= $r['id'];


//            echo $_SESSION['username'];
            $this -> index();
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
        $ch = curl_init();
        $url = 'http://zyuanyuan.com/foodsApi/home';
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output=curl_exec($ch);
        $data = json_decode($output,true);
        $foods = $data["result"]["features_list"]["list"];
//        var_dump($foods);
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
        $errinfo = '';
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM user WHERE username='$username'";
        $res = mysqli_query($this->link,$sql);
        $user = mysqli_fetch_assoc($res);
//        var_dump($user);

        require 'View/user.html';
    }
    public function user1($info) {
        $errinfo = $info;
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM user WHERE username='$username'";
        $res = mysqli_query($this->link,$sql);
        $user = mysqli_fetch_assoc($res);
//        var_dump($user);

        require 'View/user.html';
    }

    // 修改头像
    public function updateAvatar() {
        $errinfo =  '';
        if(!empty($_POST)){
            $errinfo =  '提交了表单';
            // 文件类型限制
            $fileMimes = array('image/gif','image/png', 'image/jpeg');
            if(is_array($fileMimes)) {
                if(!in_array($_FILES['avatar']['type'],$fileMimes)){
                    $errinfo =  '文件类型不允许为：gif, png, jpg之外的类型！';
                    $this -> user1($errinfo);
                    die;
                }
            }
            // 文件大小限制
            if($_FILES['avatar']['size']>6291456){
                $errinfo =  '禁止上传6MB以上的文件！';
                $this -> user1($errinfo);
                die;
            }
            if($_FILES['avatar']['error'] !=0){
                $errinfo =  '上传有错误';
                $this -> user1($errinfo);
                die;
            }
            // 避免文件重名
            $filename = $_FILES['avatar']['name'];
            $pos = strrpos($filename, '.',0);
            $t = substr($filename,$pos);
            $new_filename = time().'_'.rand(10000,99999).$t;

            if(!empty( $_SESSION['username'])){
                $username = $_SESSION['username'];

                $fileName = 'upload/avatars/'.$new_filename;
                $errinfo =  '头像存储位置';
                $res = move_uploaded_file($_FILES['avatar']['tmp_name'],$fileName);
                if($res){
//                    $date = date('Y-m-d H:i:s');
                    $addsql = "UPDATE user SET avatar='$fileName' WHERE username = '$username';";
                    $result = mysqli_query($this->link,$addsql);
                    if($result){
                        $errinfo =  '头像修改成功！';
                        $this -> user1($errinfo);
                        die;
                    }else{
                        $errinfo = '数据库修改错误！';
                    }

                }else{
                    $errinfo = '头像上传错误！';
                    $this -> user1($errinfo);
                    die;
                }

            }else{
                $errinfo = '未登录，请先登录！';
                $this -> user1($errinfo);
                die;
            }
            require 'View/user.html';

        }
        $this -> user1($errinfo);

    }

    // 动态
    public function news() {
        $errinfo = '';
        $sql = "SELECT * FROM news ";
        $res = mysqli_query($this->link,$sql);
        $news = mysqli_fetch_assoc($res);

        require 'View/news.html';
    }
    public function news1($info) {
        $errinfo = $info;

        $sql = "SELECT * FROM news ";
        $res = mysqli_query($this->link,$sql);
        $news = mysqli_fetch_assoc($res);

        require 'View/news.html';
    }
    // 发布动态
    public function uploadNew() {
        $errinfo =  '';
        if(!empty($_POST)){
//            var_dump($_FILES['img']['name']);
//            echo "<br />";
//            var_dump($_FILES['img']['tmp_name']);
//
//
//            return;

            $errinfo =  '提交了表单';

            if($_POST['text']){
                if(strlen($_POST['text'])<=400){
                    $content = $_POST['text'];
                }else{
                    $errinfo = '评论内容超过200中文字！';
                    $this -> news1($errinfo);
                    die;
                }
            }

            // 文件类型限制
            $fileMimes = array('image/gif','image/png', 'image/jpeg','');
            $fileName1 = null;
            $fileName2 = null;
            $fileName3 = null;

            $imgs = array();
            for($i=0;$i<count($_FILES['img']['name']);$i++){

                if(is_array($fileMimes)) {
                    if(!in_array($_FILES['img']['type'][$i],$fileMimes)){
                        $errinfo =  '文件类型不允许为：gif, png, jpg之外的类型！';
                        $this -> news1($errinfo);
                        die;
                    }
                }
                // 文件大小限制
                if($_FILES['img']['size'][$i]>6291456){
                    $errinfo =  '禁止上传6MB以上的文件！';
                    $this -> news1($errinfo);
                    die;
                }
                if($_FILES['img']['error'][$i] !=0 ){
                    $errinfo =  '上传有错误';
                    $this -> news1($errinfo);
                    die;
                }


                // 避免文件重名
//                $filename = $_FILES['img']['name'][$i];

//                $pos = strrpos($filename, '.',0);
//                $t = substr($filename,$pos);

                if($i==0){
                    $filename1 = $_FILES['img']['name'][$i];
                    $new_filename1 = time().'_'.rand(10000,99999).$filename1;
                    $fileName1 = 'upload/newsImgs/'.$new_filename1;
                    $res1 = move_uploaded_file($_FILES['img']['tmp_name'][0],$fileName1);
                    if($res1){
                        array_push($imgs,$fileName1);
                    }else {
                        $errinfo = '动态上传错误！1';
                        $this -> news1($errinfo);
                        die;
                    }
                }elseif($i==1){
                    $filename2 = $_FILES['img']['name'][$i];
                    $new_filename2 = time().'_'.rand(10000,99999).$filename2;
                    $fileName2 = 'upload/newsImgs/'.$new_filename2;
                    $res2 = move_uploaded_file($_FILES['img']['tmp_name'][1],$fileName2);
                    if($res2){
                        array_push($imgs,$fileName2);
                    }else {
                        $errinfo = '动态上传错误！2';
                        $this -> news1($errinfo);
                        die;
                    }

                }elseif($i==2){
                    $filename3 = $_FILES['img']['name'][$i];
                    $new_filename3 = time().'_'.rand(10000,99999).$filename3;
                    $fileName3 = 'upload/newsImgs/'.$new_filename3;
                    $res3 = move_uploaded_file($_FILES['img']['tmp_name'][2],$fileName3);
                    if($res3){
                        array_push($imgs,$fileName3);
                    }else {
                        $errinfo = '动态上传错误！3';
                        $this -> news1($errinfo);
                        die;
                    }

                }

            }


            $img = implode('**', $imgs);


            if(!empty( $_SESSION['username'])){
                $username = $_SESSION['username'];
                $userid =  $_SESSION['userid'];
                $sql = "INSERT INTO news(user_id,content,img) VALUES('$userid','$content','$img')";
                $result = mysqli_query($this->link,$sql);
                if($result){
                    $errinfo =  '动态发布成功！';
                    $this -> news1($errinfo);
                    die;
                }else{
                    $errinfo = '数据库修改错误！';
                    $this -> news1($errinfo);
                    die;
                }

            }else{
                $errinfo = '未登录，请先登录！';
                $this -> news1($errinfo);
                die;
            }
            require 'View/user.html';

        }
        $this -> news1($errinfo);

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