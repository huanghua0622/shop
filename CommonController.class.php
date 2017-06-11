<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/3
 * Time: 19:04
 */
//命名空间
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller
{

    public function __construct()
    {
        //先调用父类的构造函数
        parent::__construct();
        //判断是否登录
        if(!session('?manager_user')){
            $this->error('请先登录',U('Admin/Login/login'));
        }
        $this->getNav();
        $this->checkAuth();
        //dump($this->getNav());die;
    }
    public function getNav(){
        if(session('?top_nav') && session('second_nav')){
            return;
        }
        $role_id = session('manager_user.role_id');
        if($role_id == 1){
            $top = M('Auth') -> where("pid = 0 and is_nav=1") -> select();
            $second = M('Auth') -> where("pid > 0 and is_nav=1") -> select();
//            dump($second);die;
        }else{
            $role = M('Role')->find($role_id);
            $role_auth_ids = $role['role_auth_ids'];
            //获取顶级权限
            $top = M('Auth')->where("pid = 0 and id in($role_auth_ids) and is_nav=1")->select();
//            dump($top);die;
            $second = M('Auth')->where("pid > 0 and id in($role_auth_ids) and is_nav=1")->select();
        }
//        $this->assign('top',$top);
//        $this->assign('second',$second);
        session('top_nav',$top);
        session('second_nav',$second);

    }
    public function checkAuth(){
        //获取当前用户得 拥有得权限
        $role_id = session('manager_user.role_id');
        if($role_id ==1){
            return;
        }
        $role = M('Role')->find($role_id);
        $role_auth_ac = $role['role_auth_ac'];
        //把字符串转化为数组
        $role_auth_ac_arr = explode(',',$role_auth_ac);

        $c = CONTROLLER_NAME;
        $a = ACTION_NAME;

        if(strtolower($c) == 'index' && strtolower($a) == 'index'){
            return;
        }
        $ac = $c.'-'.$a;
        if(!in_array($ac,$role_auth_ac_arr)){
            $this->error('没有访问权限',U('Admin/Index/index'));
        }
    }
}