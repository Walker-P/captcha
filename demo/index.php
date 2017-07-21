<?php
/**
 * Created by PhpStorm.
 * Date: 2017/7/21
 * Author：wkp
 * Time: 11:08
 */
session_start();
session_destroy();//将session去掉，以每次都能取新的session值;

?>
    <html>
    <head>
        <title>session 图片验证实例</title>
        <style type="text/css">
            #login p {
                margin-top: 15px;
                line-height: 20px;
                font-size: 14px;
                font-weight: bold;
            }

            #login img {
                cursor: pointer;
            }

            form {
                margin-left: 20px;
            }
        </style>
    </head>
    <body>

    <form id="login" action="" method="post">
        <p>验证码实例</p>
        <p>
            <span>验证码：</span>
            <input type="text" name="validate" value="" size=10>
            <img title="点击刷新" src="./demo.php" align="absbottom" onclick="this.src='demo.php?'+Math.random();" />
        </p>
        <p>
            <input type="submit">
        </p>
    </form>
<?php
include(__DIR__.'/../Captcha.php');
use kunpeng\Captcha\Captcha;
$captcha = new Captcha();
$validate = "";
if (isset($_POST["validate"])) {
    $validate = $_POST["validate"];
    $validate = $captcha->getCode($validate);//验证码处理

    if ($validate != $_SESSION["captcha"]) {
        echo "<font color=red>输入有误</font>";
    } else {
        echo "<font color=green>通过验证</font>";
    }
}
?>
