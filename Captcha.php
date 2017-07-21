<?php

/**
 * Created by PhpStorm.
 * Date: 2017/7/20
 * Author：wkp
 * Time: 17:53
 */
namespace kunpeng\Captcha;

class Captcha
{
    private $code_arr;//验证码数组
    //默认配置
    protected $config = [
        'charset' => 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ123456789',//随机字符串
        'zh_charset' => '你好我问万融佛乐土忽然两个热为人父俄突破们以我到他会作时要动国产',//中文随机字符串
        'code' => 0,//验证码
        'codelen' => 4,//验证码长度
        'width' => 130,//宽度
        'height' => 50,//高度
        'img' => '',//图形资源句柄
        'font' => '',//指定的字体
        'fontsize' => 20,//指定字体大小
        'fontcolor' => '',//指定字体颜色
        'key' => 'github.com/kunpeng/captcha',
        'type' => 0//验证码类型
    ];

    /**
     * 构造方法初始化
     * Captcha constructor.
     * @param int $type 自定义验证码类型
     * @param array $config 自定义配置
     */
    public function __construct($type = 0, $config = [])
    {
        $this->type = $type;
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 初始化字体
     */
    private function init()
    {
        if ($this->type == 0) {
            $this->font = dirname(__FILE__) . '/font/Elephant.ttf';//注意字体路径要写对，否则显示不了图片
        }
        $this->font = dirname(__FILE__) . '/font/minijkt.ttf';
    }

    /**
     * 使用 $this->name 获取配置
     * @param  string $name 配置名称
     * @return multitype    配置值
     */
    public function __get($name)
    {
        return $this->config[$name];
    }

    /**
     * 设置验证码配置
     * @param  string $name 配置名称
     * @param  string $value 配置值
     * @return void
     */
    public function __set($name, $value)
    {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 检查配置
     * @param  string $name 配置名称
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * 生成随机码
     */
    private function createCode()
    {
        if ($this->type == 0) {
            $_len = strlen($this->charset) - 1;
            for ($i = 0; $i < $this->codelen; $i++) {
                $this->code_arr [] = $this->charset[mt_rand(0, $_len)];
            }
        } else {
            $_len = mb_strlen($this->zh_charset) - 1;
            for ($i = 0; $i < $this->codelen; $i++) {
                $this->code_arr [] = mb_substr($this->zh_charset, mt_rand(0, $_len), 1, 'utf-8');
            }
        }
        $this->code = implode("", $this->code_arr);
    }

    /**
     * 生成背景
     */
    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    /**
     * 生成文字
     */
    private function createFont()
    {
        $_x = $this->width / $this->codelen;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->fontcolor, $this->font, $this->code_arr[$i]);
        }
    }

    /**
     * 生成线条、雪花
     */
    private function createLine()
    {
        //线条
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    /**
     * 输出图片
     */
    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    /**
     * 对外生成,验证码最终方法
     */
    public function createImg()
    {
        $this->init();
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    /**
     * 对外获取验证码
     * @param string $code
     * @return string
     */
    public function getCode($code = '')
    {
        if (empty($code)) {
            $code = $this->code;
        }
        if ($this->type == 0) {
            return $this->encryptCode(strtolower($code));
        }
        return $this->encryptCode($code);
    }

    /**
     * 加密验证码
     * @param $str
     * @return string
     */
    private function encryptCode($str)
    {
        $key = substr(md5($this->key), 11, 15);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }
}