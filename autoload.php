<?php
/**
 * Created by PhpStorm.
 * Date: 2017/7/20
 * Author：wkp
 * Time: 17:50
 */

/**
 * 自动加载
 */
spl_autoload_register(function ($className) {
    $namespace = 'kunpeng\\Captcha';
    if (strpos($className, $namespace) === 0) {
        $className = str_replace($namespace, '', $className);
        $fileName = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
        if (file_exists($fileName)) {
            require($fileName);
        }
    }
});