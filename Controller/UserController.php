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
            $_SESSION['avatar']= $r['avatar'];


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

        $this->index();

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
        $userid = $_SESSION['userid'];
        $sql = "SELECT * FROM user WHERE username='$username'";
        $res = mysqli_query($this->link,$sql);
        $user = mysqli_fetch_assoc($res);

        // 查询所有动态的点赞信息
        $sql1 = "SELECT * from `news` WHERE user_id=".$userid;
        $res1 = mysqli_query($this->link,$sql1);
        $news = [];
        while($result1 = mysqli_fetch_assoc($res1)){
            $result1['img'] = explode( ',',$result1['img']);
            array_push( $news,$result1);
        }

        require 'View/user.html';
    }
    public function user1($info) {
        $errinfo = $info;
        $username = $_SESSION['username'];
        $userid = $_SESSION['userid'];
        $sql = "SELECT * FROM user WHERE username='$username'";
        $res = mysqli_query($this->link,$sql);
        $user = mysqli_fetch_assoc($res);

        // 查询所有动态的点赞信息
        $sql1 = "SELECT * from `news` WHERE user_id=".$userid;
        $res1 = mysqli_query($this->link,$sql1);
        $news = [];
        while($result1 = mysqli_fetch_assoc($res1)){
            $result1['img'] = explode( ',',$result1['img']);
            array_push( $news,$result1);
        }

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
        // 标志是哪个方法处理的。
        $flag = 'news';
        $errinfo = '';
        $news = [];
        // 查询所有动态
        $sql = "SELECT news.*,`user`.avatar,`user`.nickname FROM `user`,news WHERE `user`.id=news.user_id ORDER BY news.createtime DESC,news.id DESC";
        $res = mysqli_query($this->link,$sql);

        while($result = mysqli_fetch_assoc($res)){
            $result['img'] = explode( ',',$result['img']);

            // 查询所有动态的点赞信息
            $sql1 = "SELECT like_news.id,`user`.id 'uid',`user`.avatar,`user`.nickname FROM `user`,like_news WHERE `user`.id=like_news.user_id AND like_news.news_id=".$result['id'];
            $res1 = mysqli_query($this->link,$sql1);
            $result['like_users'] = [];
            $result['like'] = 0;
            while($result1 = mysqli_fetch_assoc($res1)){
                if( $result1['uid'] === $_SESSION['userid']){
                    $result['like'] = 1;
                }
                array_push( $result['like_users'],$result1);
            }

            // 查询所有动态的相应评论
            $sql2 = "SELECT `comment`.*,`user`.nickname FROM `comment`,`user` WHERE new_id=".$result['id']." AND `comment`.user_id=`user`.id";
            $res2 = mysqli_query($this->link,$sql2);
            $result['comments'] = [];
            while($result2 = mysqli_fetch_assoc($res2)){
                array_push( $result['comments'],$result2);
            }
            array_push($news,$result);
        }




//        var_dump($news[0]['img']);
        require 'View/news.html';
    }

    public function news1($info,$info1) {
        // 标志是哪个方法处理的。
        $flag = 'news1';
        $errinfo = $info;
        $errinfo1 = $info1;
        $news = [];
        // 查询所有动态
        $sql = "SELECT news.*,`user`.avatar,`user`.nickname FROM `user`,news WHERE `user`.id=news.user_id ORDER BY news.createtime DESC,news.id DESC";
        $res = mysqli_query($this->link,$sql);

        while($result = mysqli_fetch_assoc($res)){
            $result['img'] = explode( ',',$result['img']);

            // 查询所有动态的点赞信息
            $sql1 = "SELECT like_news.id,`user`.id 'uid',`user`.avatar,`user`.nickname FROM `user`,like_news WHERE `user`.id=like_news.user_id AND like_news.news_id=".$result['id'];
            $res1 = mysqli_query($this->link,$sql1);
            $result['like_users'] = [];
            $result['like'] = 0;
            while($result1 = mysqli_fetch_assoc($res1)){
                if( $result1['uid'] === $_SESSION['userid']){
                    $result['like'] = 1;
                }
                array_push( $result['like_users'],$result1);
            }

            // 查询所有动态的相应评论
            $sql2 = "SELECT `comment`.*,`user`.nickname FROM `comment`,`user` WHERE new_id=".$result['id']." AND `comment`.user_id=`user`.id";
            $res2 = mysqli_query($this->link,$sql2);
            $result['comments'] = [];
            while($result2 = mysqli_fetch_assoc($res2)){
                array_push( $result['comments'],$result2);
            }
            array_push($news,$result);
        }

//        var_dump($news[0]['like']);
        require 'View/news.html';
    }

    // 搜索动态
    public function searchNew($sUsername='',$sContent='') {
        // 标志是哪个方法处理的。
        $flag = 'search';
        $errinfo = '';
        $errinfo1 =  '';
        $news = [];

        if(!empty($_POST)) {
            $s_username = $_POST['s_username'];
            $s_content = $_POST['s_content'];
        }else{
            $s_username = $sUsername;
            $s_content = $sContent;
        }

        if( $s_username){
            $sql = "SELECT * FROM `user` WHERE username = '$s_username'";
            $res = mysqli_query($this->link,$sql);
            $r = mysqli_fetch_assoc($res);
            if(empty($r)){
                $errinfo1 = '用户名不存在！';
            }else{
                $s_userid = $r['id'];
                $sql3 = "SELECT news.*,`user`.avatar,`user`.nickname FROM `user`,news WHERE `user`.id=news.user_id AND news.user_id = '$s_userid' ORDER BY news.createtime DESC,news.id DESC";
                $res3 = mysqli_query($this->link,$sql3);

                while($result = mysqli_fetch_assoc($res3)){

                    $result['img'] = explode( ',',$result['img']);

                    // 动态内容关键字标红
                    $result['content'] = str_replace($s_content,"<span style='color:red;'><b>$s_content</b></span>",$result['content']);

                    // 查询相应动态的点赞信息
                    $sql1 = "SELECT like_news.id,`user`.id 'uid',`user`.avatar,`user`.nickname FROM `user`,like_news WHERE `user`.id=like_news.user_id AND like_news.news_id=".$result['id'];
                    $res1 = mysqli_query($this->link,$sql1);
                    $result['like_users'] = [];
                    $result['like'] = 0;
                    while($result1 = mysqli_fetch_assoc($res1)){
                        if( $result1['uid'] === $_SESSION['userid']){
                            $result['like'] = 1;
                        }
                        array_push( $result['like_users'],$result1);
                    }

                    // 查询所有动态的相应评论
                    $sql2 = "SELECT `comment`.*,`user`.nickname FROM `comment`,`user` WHERE new_id=".$result['id']." AND `comment`.user_id=`user`.id";
                    $res2 = mysqli_query($this->link,$sql2);
                    $result['comments'] = [];
                    while($result2 = mysqli_fetch_assoc($res2)){
                        array_push( $result['comments'],$result2);
                    }
                    array_push($news,$result);
                }
            }
        }else{
            // 查询所有动态
            $sql = "SELECT news.*,`user`.avatar,`user`.nickname FROM `user`,news WHERE `user`.id=news.user_id ORDER BY news.createtime DESC,news.id DESC";
            $res = mysqli_query($this->link,$sql);

            while($result = mysqli_fetch_assoc($res)){
                if(strpos($result['content'],$s_content)!== false){
                    $result['img'] = explode( ',',$result['img']);

                    // 动态内容关键字标红
                    $result['content'] = str_replace($s_content,"<span style='color:red;'><b>$s_content</b></span>",$result['content']);

                    // 查询所有动态的点赞信息
                    $sql1 = "SELECT like_news.id,`user`.id 'uid',`user`.avatar,`user`.nickname FROM `user`,like_news WHERE `user`.id=like_news.user_id AND like_news.news_id=".$result['id'];
                    $res1 = mysqli_query($this->link,$sql1);
                    $result['like_users'] = [];
                    $result['like'] = 0;
                    while($result1 = mysqli_fetch_assoc($res1)){
                        if( $result1['uid'] === $_SESSION['userid']){
                            $result['like'] = 1;
                        }
                        array_push( $result['like_users'],$result1);
                    }

                    // 查询所有动态的相应评论
                    $sql2 = "SELECT `comment`.*,`user`.nickname FROM `comment`,`user` WHERE new_id=".$result['id']." AND `comment`.user_id=`user`.id";
                    $res2 = mysqli_query($this->link,$sql2);
                    $result['comments'] = [];
                    while($result2 = mysqli_fetch_assoc($res2)){
                        array_push( $result['comments'],$result2);
                    }
                    array_push($news,$result);
                }

            }


        }


        require 'View/news.html';
    }

    // 发布动态
    public function uploadNew() {
        $errinfo =  '';
        $errinfo1 = '';
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
                    $this -> news1($errinfo,$errinfo1);
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
                        $this -> news1($errinfo,$errinfo1);
                        die;
                    }
                }
                // 文件大小限制
                if($_FILES['img']['size'][$i]>6291456){
                    $errinfo =  '禁止上传6MB以上的文件！';
                    $this -> news1($errinfo,$errinfo1);
                    die;
                }
                if($_FILES['img']['error'][$i] !=0 ){
                    $errinfo =  '上传有错误';
                    $this -> news1($errinfo,$errinfo1);
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
                        $this -> news1($errinfo,$errinfo1);
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
                        $this -> news1($errinfo,$errinfo1);
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
                        $this -> news1($errinfo,$errinfo1);
                        die;
                    }

                }

            }


            $img = implode(',', $imgs);


            if(!empty( $_SESSION['username'])){
                $username = $_SESSION['username'];
                $userid =  $_SESSION['userid'];
                $sql = "INSERT INTO news(user_id,content,img) VALUES('$userid','$content','$img')";
                $result = mysqli_query($this->link,$sql);
                if($result){
                    $errinfo =  '动态发布成功！';
                    $this -> news1($errinfo,$errinfo1);
                    die;
                }else{
                    $errinfo = '数据库修改错误！';
                    $this -> news1($errinfo,$errinfo1);
                    die;
                }

            }else{
                $errinfo = '未登录，请先登录！';
                $this -> news1($errinfo,$errinfo1);
                die;
            }
            require 'View/user.html';

        }
        $this -> news1($errinfo,$errinfo1);

    }

   // 点赞
   public function addLike() {
       $id = $_GET['id'];
       $like = $_GET['like'];
       // 标志是哪个方法处理的。
//       $flag = $_GET['flag'];
//       $sUsername = $_GET['sUsername'];
//       $sContnet = $_GET['sContent'];

        // 根据like值判断是执行点赞语句还是取消点赞语句
        if($like==1){
            $sql = "DELETE FROM like_news WHERE news_id= ".$id."  AND user_id=".$_SESSION['userid'];
        }else if($like==0){
            $sql = "INSERT INTO like_news(news_id,user_id) VALUES(".$id.",".$_SESSION['userid'].")";
        }

       $res = mysqli_query($this->link,$sql);

//       if($flag === 'search'){
//           $this->searchNew($sUsername,$sContnet);
//       }else{
//           $this -> news();
//       }
//       $this -> news();
   }

    // 添加评论
    public function addcomments() {
        if(!empty($_POST)){

            $newid = $_POST['newid'];
            $userid = $_SESSION['userid'];
            $content = $_POST['content'];


            $sql =  "INSERT INTO `comment`(new_id,user_id,content) VALUES(".$newid.",".$userid.",'".$content."')"; // 向评论表插入用户评论信息
//            echo $sql;
            $res = mysqli_query($this->link,$sql);
        }

        $this -> news();
    }

    // 好友列表
    public function friends() {
        $userid = $_SESSION['userid'];
        $friends = [];
        // 查询当前用户的好友
        $sql = "SELECT * FROM `user` WHERE id IN (SELECT fid FROM friend_connection WHERE uid = '$userid')";
        $res = mysqli_query($this->link,$sql);
        while($result = mysqli_fetch_assoc($res)){
            array_push( $friends,$result);
        }

        require 'View/friends.html';
    }

    // 更多好友
    public function moreFriends() {
        // 查询当前用户的除了自己的未添加过的用户信息
        $userid = $_SESSION['userid'];
        $friends = [];
        $sql = "SELECT * FROM `user` WHERE id NOT IN (SELECT fid FROM friend_connection WHERE uid = '$userid' ) AND id <> '$userid'";
        $res = mysqli_query($this->link,$sql);
        while($result = mysqli_fetch_assoc($res)){
            array_push( $friends,$result);
        }

        require 'View/moreFriends.html';
    }

    //添加好友
    public function addFriend() {
        $id = $_GET['id'];
        $uid = $_SESSION['userid'];

        $sql = "INSERT INTO friend_connection(uid,fid) VALUES('$uid','$id')";

        $res = mysqli_query($this->link,$sql);
    }

    // 删除好友
    public function removeFriend() {
        $id = $_GET['id'];
        $uid = $_SESSION['userid'];

        $sql = "DELETE FROM friend_connection WHERE uid='$uid' AND fid='$id'";

        $res = mysqli_query($this->link,$sql);
    }

}