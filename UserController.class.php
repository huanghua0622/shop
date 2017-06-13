<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller{

	public function register(){
		//关闭模板布局
		layout(false);
		$this->display();
	}

	public function ajaxregister(){
		//接收数据
		$data = I('post.');
		//判断把注册的帐号放到username字段
		if($data['email']){
			$data['username'] = $data['email'];
		}else{
			$data['username'] = $data['phone'];
		}
		//为了在模型中使用自动验证和自动完成
		$model = D("User");
		if(!$model -> create($data)){
			$error = $model -> getError();
			//数据验证失败
			$return = array(
				'code' => 10001,
				'msg' => $error
			);
			$this -> ajaxReturn($return);
		}
		$res = $model -> add();
		if($res){
			//注册成功
			$return = array(
				'code' => 10000,
				'msg' => 'success'
			);
			$this -> ajaxReturn($return);
		}else{
			//注册失败
			$return = array(
				'code' => 10002,
				'msg' => '注册失败'
			);
			$this -> ajaxReturn($return);
		}
	}

	public function login(){
		//关闭模板布局
		layout(false);
		//如果已经登录，跳转到首页
		if(session('?user_info')){
			$this -> redirect('Home/Index/index');
		}
		$this->display();
	}

	public function ajaxlogin(){
		//接收数据
		$data = I('post.');
		$username = $data['username'];
		//通过用户输入的用户查询数据表
		$user = M('User') -> where("username = '$username' or phone='$username' or email='$username'") -> find();
		//用户存在则判断密码
		if($user && $user['password'] == encrypt_password($data['password'])){
			//登录成功
			//设置登录标识
			session('user_info', $user);
			D('cart')->cookieTodb();
			$return = array('code' => 10000, 'msg'=> 'success');
			$this -> ajaxReturn($return);
		}else{
			//登录失败
			$return = array('code' => 10001, 'msg'=> '用户名或密码错误');
			$this -> ajaxReturn($return);
		}
	}

	public function logout(){
		//清除session
		session(null);
		$this -> success('退出成功',U('Home/User/login'));
	}
}