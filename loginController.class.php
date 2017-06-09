<?php 
namespace Admin\Controller;
use Think\Controller;
use Think\Verify;

class LoginController extends Controller{

	public function login(){
	    //修改数据库中的密码
        //一个方法处理两个逻辑
        if(IS_POST){

            //处理数据表
            //提交表单数据
            $data = I('post.');

            //校验验证码
            $verify = new Verify();
            $check = $verify->check($data['verify']);
            if(!$check){
                //验证失败
                $this->error('验证码错误');
            }
            //实例化模型
            $model = M('Manager');
            //根据用户名在数据库中查找
            $user = $model->where(array('username'=>$data['username']))->find();
//            static $num;
//            if($data['password'] != $user['password']){
//                $num++;
//                if($num === 3){
//                    $this->error('账户已锁，请明天再试');
//                }
//            }
            if($user && encrypt_password($data['password']) == $user['password']){
                //登录成攻
                //设置登录标识
                session('manager_user',$user);
                $this->success('登录成功',U('Admin/Index/index'));
            }else{
                //登录失败
                $this->error('登录失败');
            }

        }
		$this->display('login');
	}
	public function captcha()
    {
        //生成验证码并显示
        //$verify = new\Think\Verify();

        //自定义配置验证码
        $config = array(
            'useCurve' =>true,   //是否画混淆曲线
            'useNoise' => false, //是否添加杂点
            'length' => 4,  //验证码的长度
            'fontSize' => 70,   //验证码字体的大小
        );
        $verify = new \Think\Verify($config);
        $verify->entry();
    }
}



 ?>
