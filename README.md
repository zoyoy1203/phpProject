# phpProject
php期末大作业

> 这个项目是我大三上的PHP课期末项目大作业。 作业已经交了，现在放上来给大家参考学习！

#### 一：技术栈介绍
主题：美食博客
前端：`html`,`js`,`css`, `bootstrap`,`jq`
后端：`php` `mvc`
数据库：`mysql`
本项目美食部分接口调用地址：[美食接口文档](https://blog.csdn.net/weixin_40693643/article/details/102362672)
本项目实时更新github  地址：[github地址](https://github.com/zoyoy1203/phpProject)

#### 二：实现功能总结
1. 登录，注册，退出登录，验证码。
2. API接口调用：菜谱推荐，菜谱分类，菜谱分类详情，菜谱详情。
3. 个人信息展示：头像，座右铭修改。
4. 个人动态发布展示。
5. 动态点赞评论功能。
6. 动态展示，搜索，关键字标红功能。
7. 用户列表显示，添加删除好友功能。
8. 错误信息提示功能。


#### 三：总体结构
该项目采用简易版`mvc`的结构。
由于后来我都是直接在控制层里声明使用数据表结构数据，所以后面我把`Model`层去掉了。只留下`Controller`控制层和`View`视图层。
其他目录结构如下图：`public`（静态样式文件）`upload `(存放上传的图片)  `util` (里面只有一个`verCode.php `用来绘制验证码图片)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106111925632.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
根目录下的`index.php`文件用来对不同`url`的请求进行`Controller`控制层下不同类和方法的调用。
本项目`Controller`文件下只有一个`UserController`类，里面包含了项目所有的处理方法。只需按照`/phpProject/?a=regis`  (a=后接相应的调用方法)  这个格式进行请求则可。

#### 四：作品展示
##### 1. 登录注册功能
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106112053641.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106112107323.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
详细介绍：
登录注册页面都是通过form表单提交数据到`action="/phpProject/?a=loginPost"`
`action="/phpProject/?a=regisPost"`
然后在`Controller`文件下的`UserController.php`里对应的方法中进行处理。

登录注册处理过程中，如果发生错误，则会在页面上提示相应的错误原因：
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106114822655.jpg?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106114832402.jpg?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106114850487.jpg?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106114841228.jpg?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)



功能要求：1.用户注册：判断重名，验证码验证，至少要有用户名和密码字段。（完成！）

项目移动过程中，需要注意，`util`文件夹下的`verCode.php`中
`$font = "D:/xampp/htdocs/phpProject/public/font/segoepr.ttf";  // 路径问题`
需要修改为当前电脑对应的路径，不然验证码无字体显示。

验证码生成的四个字符存储在 `$_SESSION["code"]`中，以便于后续的判断处理。

另外，为了解决随机颜色导致验证码个别字符融入背景色的问题，登录页面的验证码设置了点击验证码图片切换字符的功能。
主要代码如下：

```javascript
<img src="util/verCode.php" alt="看不清楚，换一张" onclick="javascript:newgdcode(this,this.src);" style="width: 100px;height:50px;"  alt=""/>

<script language="javascript">
    function newgdcode(obj,url) {
        obj.src = url+ '?nowtime=' + new Date().getTime();
        //后面传递一个随机参数，否则在IE7和火狐下，不刷新图片
    }
</script>
```

功能要求：2.用户登录：以SESSION方式。（完成！）
登录成功后，会将登录用户名，用户id，用户头像地址分别存入SESSION：
$_SESSION['username'] $_SESSION['userid'] $_SESSION['avatar']
在后续页面菜单栏右侧会显示登录的用户名。
首页和菜谱页面下的子页面不需要用户登录也可以显示。
其他页面如：个人中心，动态，好友列表等页面，需要用户登录才能显示。如果没有登录则跳转到登录页面。

功能要求：6.使用API接口制作一项功能，如天气、菜谱、影视、火车票查询等（完成！）

##### 2. 首页
（调用豆果美食数据接口：[接口文档](https://blog.csdn.net/weixin_40693643/article/details/102362672)）
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106112559555.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106114622869.jpg?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
##### 3. 菜谱分类页面，菜谱页面，菜谱详情页面 （调用豆果美食数据接口：[接口文档](https://blog.csdn.net/weixin_40693643/article/details/102362672)）
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106112915471.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106112933657.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106112938785.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)

##### 4. 个人中心页面
功能要求： 5.查看自己的分享：已登录用户要能查看自己发布的分享。（完成！）
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113027580.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113034388.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
个人中心页面左侧显示个人信息：可以修改个人头像，座右铭；右侧显示个人发布的动态，按时间先后显示。

##### 5. 动态页面
功能要求： 4.首页：显示所有用户发布的分享，每一条分享显示发布人、时间。（完成）
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113132961.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113149858.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
功能要求：3.内容分享：已登录用户可以发布自己的图文分享，文字不超过200中文字，支持最多3张图片上传（6分）（完成）
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113224712.png)
![在这里插入图片描述](https://img-blog.csdnimg.cn/2020010611324174.png)
字体图片检测主要代码：

```php
if($_POST['text']){
    if(strlen($_POST['text'])<=400){
        $content = $_POST['text'];
    }else{
        $errinfo = '评论内容超过200中文字！';
        $this -> news1($errinfo,$errinfo1);
        die;
    }
}

if(count($_FILES['img']['name'])>2){
    $errinfo = '上传图片超过3张！';
    $this -> news1($errinfo,$errinfo1);
    die;
}
```

功能要求： 7.所有用户可以搜索分享内容：用户可以搜索分享内容，并列表显示，搜索的关键字加粗或红色。（4分）（完成）
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113403434.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113410450.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113415698.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
搜索动态并关键字标红主要思路：
`Input`框输入搜索内容（搜索用户可填可不填，也可只查询相应用户动态）。点击搜索按钮，提交`form`表单`action="/phpProject/?a=searchNew"`
在 `searchNew`方法里连接数据库查询相应动态:

```php
// 查询动态
$sql = "SELECT news.*,`user`.avatar,`user`.nickname FROM `user`,news WHERE `user`.id=news.user_id ORDER BY news.createtime DESC,news.id DESC";
```

如果有搜索内容关键字，则在查询到的每条动态内容数据上对关键字进行替换即可：

```php
// 动态内容关键字标红
$result['content'] = str_replace($s_content,"<span style='color:red;'><b>$s_content</b></span>",$result['content']);
```
动态点赞评论功能的主要代码如下：

```php
// 点赞
public function addLike() {
    $id = $_GET['id'];
    $like = $_GET['like'];
     // 根据like值判断是执行点赞语句还是取消点赞语句
     if($like==1){
         $sql = "DELETE FROM like_news WHERE news_id= ".$id."  AND user_id=".$_SESSION['userid'];
     }else if($like==0){
         $sql = "INSERT INTO like_news(news_id,user_id) VALUES(".$id.",".$_SESSION['userid'].")";
     }
    $res = mysqli_query($this->link,$sql);

}
// 添加评论
public function addcomments() {
    if(!empty($_POST)){
        $newid = $_POST['newid'];
        $userid = $_SESSION['userid'];
        $content = $_POST['content'];

        $sql =  "INSERT INTO `comment`(new_id,user_id,content) VALUES(".$newid.",".$userid.",'".$content."')"; // 向评论表插入用户评论信息
        $res = mysqli_query($this->link,$sql);
    }

}
其中，为了点赞评论后，页面不刷新，用户体验效果好，这里使用了`AJAX`异步请求数据。（修改座右铭，添加删除好友等功能都使用了该方法）
点赞JS代码示例如下：
$(".addlike").on("click",function(){
            let uid = $(this).children(".id").text();
            let avatar = $(this).children(".avatar").text();
            let newsid =  $(this).children(".newsid").text();
            let like = $(this).children(".like").text();

            var that = $(this);
            $.get("/phpProject/?a=addLike",{id:newsid,like:like},function(data){

                if(like ==1){
//                    $(".uid:contains(id)").parent("avatar_img").remove();
                    console.log($(that.next()).find(".avatar_img>.uid:contains(uid)").text());
                    var dom = $(that.next()).find(".avatar_img .uid");
                    console.log(dom);
                    $.each(dom, function(key, val) {
                        console.log(val.innerHTML);
                        if(val.innerHTML == uid){
                            $(val).parent().remove();
                        }
                    });

                    $(dom).parent("avatar_img").remove();

//                    uidDom.parent("avatar_img").remove();
                    that.children(".like").text("0");
                    that.children(".like_text").text("点赞");
                }
                if(like == 0){
                    var html = "";
                    html += "<div class='avatar_img'><div class='uid'style='display: none;' >";
                    html += uid;
                    html += "</div><img style='width:30px;height:30px;' src='";
                    html += avatar;
                    html += "' ></div>";
                    that.next().append(html)
                    that.children(".like").text("1");
                    that.children(".like_text").text("取消点赞");
                }
            });
        });

```

##### 6. 好友列表页面，更多好友页面（具有查看所有用户，添加删除好友功能）
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113631793.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)
![在这里插入图片描述](https://img-blog.csdnimg.cn/20200106113641836.png?x-oss-process=image/watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80MDY5MzY0Mw==,size_16,color_FFFFFF,t_70)