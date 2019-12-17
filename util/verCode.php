<?php
session_start();
$font = "D:/xampp/htdocs/phpProject/public/font/segoepr.ttf";  // 路径问题
$s ="0123456789abcdefjhijklmnopqrstuvwxyz";
$a = '';
$code = '';
$im = imagecreatetruecolor(100,50);
$white = imagecolorallocate($im,255,255,255);
$black = imagecolorallocate($im,0,0,0);
imagefill($im,22,10,$white);
imagerectangle($im,0,0,100,50,$black);


for($i=0; $i<4; $i++){
    $a = substr($s,rand(0,strlen($s)),1);
    $range = rand(-45,45);
    $x = 13+ $i*20;
    $r = rand(0,120);
    $g = rand(0,120);
    $b = rand(0,120);
    $code .=$a;
    $color = imagecolorallocate($im,$r,$g,$b);
//    imagestring($im,5,$x,40,$a,$color);
    imagettftext($im,20,$range,$x,35,$color,$font,$a);
}
$_SESSION["code"]=$code;
// 设置干扰元素
for ($i = 0; $i < 200; $i++) {
    $pointcolor = imagecolorallocate($im, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
    imagesetpixel($im, mt_rand(0,100), mt_rand(0, 50), $pointcolor);
}
// 设置干扰线
for ($i = 0; $i < 3; $i++) {
    $linecolor = imagecolorallocate($im, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
    imageline($im, mt_rand(0, 100), mt_rand(0, 50), mt_rand(0, 100), mt_rand(0, 50), $pointcolor);
}
ob_clean();//原来的程序没有这一栏
header('Content-Type:image/png');
imagepng($im);

imagedestroy($im);


