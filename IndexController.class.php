<?php
namespace Api\Controller;
use Think\Controller;
//发送短信
class IndexController extends Controller{
    public function sendmsg(){
        $phone = I('post.phone');
        //调用短信接口发送短信
        $url = "http://v.juhe.cn/sms/send?mobile=$phone&tpl_id=34764&key=d36c1d9829185050b5ac21d5beafa4a8&tpl_value=";
        //短信模板中变量  #act#  #code# #rand#
        $act = '注册';
        $code = rand(100000, 999999);
        $rand = rand(1, 99);
        //拼接tpl_value参数
        $tpl_value = "#act#=$act&#code#=$code&#rand#=$rand";
        //进行urlencode编码
        $tpl_value = urlencode($tpl_value);
        $url .= $tpl_value;
        //发送get请求
        $res = curl_request($url, false);
        // dump($res);die;
        if($res['error_code'] == 0){
            //发送成功，保存验证码到session，用于后续的验证
            session('sms_code' . $phone, $code);
            $return = array(
                'code' => 10000,
                'msg' => '发送成功'
            );
        }else{
            $return = array(
                'code' => $res['error_code'],
                'msg' => $res['reason']
            );
        }
        $this -> ajaxReturn($return);
    }
}