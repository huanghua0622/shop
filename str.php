<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/3
 * Time: 18:24
 */
//密码加密函数
function encrypt_password($password){
    //加盐
    $salt = 'asdfdddasdf';
    return md5($salt.md5($password));
}