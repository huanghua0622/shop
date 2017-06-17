<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/3
 * Time: 13:42
 */
function encrypt_phone($phone){
    //15210618793 ->152 **** 8793
    return substr($phone,0,3).'****'.substr($phone,7,4);
}
#递归方法实现无限极分类
function getTree($list,$pid=0,$level=0) {
    static $tree = array();
    foreach($list as $row) {
        if($row['pid']==$pid) {
            $row['level'] = $level;
            $tree[] = $row;
            getTree($list, $row['id'], $level + 1);
        }
    }
    return $tree;
}
function curl_request($url, $post=true, $data=array(), $https=false){
    //使用curl_init初始化会话连接
    $ch = curl_init($url);
    //使用curl_setopt设置会话选项
    //获取返回结果，并不是输出结果到页面
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //设置post方式及参数
    if($post){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //默认使用http协议
    if($https){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//关闭https证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//关闭主机证书验证
    }
    //使用curl_exec()来发送请求
    $result = curl_exec($ch);
    //发送完成之后使用curl_close关闭会话连接
    curl_close($ch);
    //把请求的结果返回都给调用方
    return $result;
}
function sendmail($email,$subject,$body)
{
    require 'Application/Tools/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.qq.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '2275013918@qq.com';                 // SMTP username
    $mail->Password = 'gvarahslyoeaebhg';                           // SMTP password
//    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('2275013918@qq.com', '穆勒');
//    $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress($email);               // Name is optional
//    $mail->addReplyTo('info@example.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');
//
//    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $body;
//    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
//        echo 'Message could not be sent.';
//        echo 'Mailer Error: ' . $mail->ErrorInfo;
        $error = $mail->ErrorInfo;
        return $error;
    } else {
//        echo 'Message has been sent';
        return true;
    }
}
