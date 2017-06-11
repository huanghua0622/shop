<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/7
 * Time: 21:17
 */

namespace Admin\Controller;

use Think\Controller;


class AuthController extends CommonController
{
    public function auth_list(){
        $auths = M('Auth')->select();
        $auths = getTree($auths);
//        dump($auths);die;
        $this->assign('auths',$auths);
        $this->display();
    }
    public function auth_add(){
       if(IS_POST){
           $data = I('post.');

           $res = M('Auth')->add($data);
           if($res){
              $this->success('添加成功',U('Admin/Auth/auth_list'));
           }else{
               $this->error('添加失败');
           }
       }else{
           $top = M('Auth')->where("pid = 0")->select();
           $this->assign('top',$top);
           $this->display();
       }
    }
    public function auth_edit(){
        if(IS_POST){
            $data = I('post.');
//            dump($data);die;
            $res = M('Auth')->save($data);
            if($res){
                $this->success('修改成功',U('Admin/Auth/auth_list'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id = I('get.id');
            $data = M('Auth')->find($id);
            $name = M('Auth')->find($data['pid']);
            $top = M('Auth')->where("pid = 0")->select();
//           $name = $name['auth_name'];
//           $allData = M('Auth')->select();
//
////          dump($allData);die;
//           $this->assign('allData',$allData);
            $this->assign('top',$top);
            $this->assign('name',$name);
            $this->assign('data',$data);
            $this->display();
        }

    }
    public function auth_delete(){
        $id = I('get.id');
        $res = M('Auth')->delete($id);
        if($res){
            $this->success('删除成功',U('Admin/Auth/auth_list'));
        }else{
            $this->error('删除失败');
        }
//        $this->display();
    }
}