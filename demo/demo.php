<?php
/**
 * Created by PhpStorm.
 * Date: 2017/7/20
 * Author：wkp
 * Time: 17:57
 */
include(__DIR__.'/../Captcha.php');
use kunpeng\Captcha\Captcha;
$captcha = new Captcha();
$captcha->createImg();
session_start();
$_SESSION['captcha'] = $captcha->getCode();//验证码保存到SESSION中