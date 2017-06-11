<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/7
 * Time: 19:13
 */

namespace Admin\Controller;
use Think\Controller;

class RoleController extends CommonController
{
    public function role_list(){
        $roles = M('Role')->select();
        $this->assign('roles',$roles);
        $this->display();
    }
    public function role_add(){
        if(IS_POST){
            $data = I('post.');
//            dump($data);die;
            $model = M('Auth');
//            $row = $model->where("id in ({$data['id']})")->select();
//            dump($row);die;
            $row = $model->select();
            $role_auth_ac = array();
            foreach ($row as $k=>$v){

                foreach ($data['id'] as $key=>$value){

                    if($v['id'] == $value && $v['pid'] != 0){

                        $role_auth_ac[] = $v['auth_c'].'-'.$v['auth_a'];
                    }
                }
            }
            $role_auth_ac = implode(',',$role_auth_ac);
//            dump($role_auth_ac);die;
            $role_auth_ids = implode(',',$data['id']);
            unset($data['id']);
            $data['role_auth_ids'] = $role_auth_ids;
            $data['role_auth_ac'] = $role_auth_ac;
//            dump($data);die;
            $res = M('Role')->add($data);
            if($res){
                $this->success('添加成功',U('Admin/Role/role_list'));
            }else{
                $this->error('添加失败');
            }
        }else{
            //获取role_id
            //$role_id=session('manamger_user.role_id')
            $role_id = I('get.id');
            //获取角色名称
            $role = M('Role')->find($role_id);

            //获取所有的权限   和获取次啊但雷氏  分为顶级和二级权限两部分
            $top = M('Auth')->where('pid = 0')->select();
            $second = M('Auth')->where('pid>0')->select();



            $role_auth_ids = $role['role_auth_ids'];

            $this->assign('top',$top);
            $this->assign('second',$second);

            $this->assign('role_auth_ids',$role_auth_ids);
            $this->assign('role_id',$role_id);

            $this->assign('role',$role);
            $this->display();
        }


    }
    public function role_edit(){
       if(IS_POST){
           $data = I('post.');
//
           $model = M('Auth');
           $row = $model->select();
           $role_auth_ac = array();
           foreach ($row as $k=>$v){

                    foreach ($data['id'] as $key=>$value){

                        if($v['id'] == $value && $v['pid'] != 0){

                                $role_auth_ac[] = $v['auth_c']."-".$v['auth_a'];
                        }
                    }
           }
           //$role_auth_ac = implode(',',$role_auth_ac);
//            dump($role_auth_ac);die;
           $role_auth_ids = implode(',',$data['id']);
           unset($data['id']);
           $data['role_auth_ids'] = $role_auth_ids;
           $data['role_auth_ac'] = $role_auth_ac;
//           dump($data);die;
           $res = M('Role')->save($data);
           if($res){
                $this->success('修改成功',U('Admin/Role/role_list'));
           }else{
               $this->error('修改失败');
           }

       }else{
           $role_id = I('get.id');
           //获取角色名称
           $role = M('Role')->find($role_id);
           //获取所有的权限   和获取次啊但雷氏  分为顶级和二级权限两部分
           $top = M('Auth')->where('pid = 0')->select();
           $second = M('Auth')->where('pid>0')->select();



           $role_auth_ids = $role['role_auth_ids'];

           $this->assign('top',$top);
           $this->assign('second',$second);

           $this->assign('role_auth_ids',$role_auth_ids);
           $this->assign('role_id',$role_id);
           $this->assign('role',$role);
           $this->display();
       }
    }
    public function role_delete(){
        $role_id = I('get.role_id');
//        dump($role_id);die;
        $model = M('Role');
        $res = $model->delete($role_id);
        if($res){
            $this->success('删除成功',U('Admin/Role/role_list'));
        }else{
            $this->error('删除失败');
        }
    }
    public function setauth(){
        //一个逻辑两个方法
        if(IS_POST){
            $data = I('post.');
            $data['role_auth_ids'] = implode(',',$data['id']);
            $auth = M('Auth')->where("id in ({$data['role_auth_ids']})")->select();
            $role_auth_ac = '';
            foreach($auth as $k => $v){
                //遍历选中得权限  去除auth_c和auth_a字段拼接在一起  组装成role_auth_ac字段
                if($v['auth_c'] && $v['auth_a']){
                    $role_auth_ac .= $v['auth_c'] . '-' .$v['auth_a'] . ',';
                }
            }
            //去除￥role_auth_ac里卖弄得最后英文逗号
            $role_auth_ac =trim($role_auth_ac,',');
            $data['role_auth_ac'] = $role_auth_ac;
            $res = M('role')->save($data);
            if($res !== false){
                $this->success('保存成功',U("Admin/Role/setauth/id/{$data['role_id']}"));
            }else{
                $this->error('保存失败');
            }
        }else{
            //获取role_id
            //$role_id=session('manamger_user.role_id')
            $role_id = I('get.id');
            //获取角色名称
            $role = M('Role')->find($role_id);
            //获取所有的权限   和获取次啊但雷氏  分为顶级和二级权限两部分
            $top = M('Auth')->where('pid = 0')->select();
            $second = M('Auth')->where('pid>0')->select();



            $role_auth_ids = $role['role_auth_ids'];

            $this->assign('top',$top);
            $this->assign('second',$second);

            $this->assign('role_auth_ids',$role_auth_ids);
            $this->assign('role_id',$role_id);
            $this->assign('role',$role);
            $this->display();
        }

    }
}