<?php
/**
 * Created by PhpStorm.
 * Date: 2017/7/20
 * Author：wkp
 * Time: 17:57
 */
header("Content-type:text/html;charset=utf-8");
include(__DIR__.'/../Captcha.php');
use kunpeng\Captcha\Captcha;
$captcha = new Captcha();
var_dump($captcha->createImg());
session_start();
$_SESSION['captcha'] = $captcha->getCode();//验证码保存到SESSION中