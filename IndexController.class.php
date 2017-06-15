<?php
namespace Api\Controller;
use Think\Controller;
//发送短信
class IndexController extends Controller{
    public function sendmsg()
    {
        $phone = I('post.phone');
        $url = "http://v.juhe.cn/sms/send?mobile=$phone&tpl_id=34764&key=d36c1d9829185050b5ac21d5beafa4a8&tpl_value=";
        //短信模板中变量  #act#  #code# #rand#
        $act = '注册';
        $code = rand(100000, 999999);
        $rand = rand(1, 99);
        //拼接tpl_value参数
        $tpl_value = "#act#=$act&#code#=$code&#rand#=$rand";
        $tpl_value = urlencode($tpl_value);
        $url .= $tpl_value;
        $res = curl_request($url,false);
        if($res['error_code'] == 0){
            //发送成功后保存到session  用于后续的验证
            session('sms_code',$phone.$code);
            $return = array(
              'code' => 10000,
                'msg' => '发送成功',
            );
        }else{
            $return = array(
                'code' => $res['error_code'],
                'msg' => $res['reason'],
            );
        }
        $this->ajaxReturn($return);
    }
}