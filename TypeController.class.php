<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/9
 * Time: 13:00
 */

namespace Admin\Controller;


class TypeController extends CommonController
{
    public function type_add()
    {
        if(IS_POST){
            $data = I('post.');
            $model = M('Type');
            $res = $model->add($data);
            if($res){
                $this->success('添加成功',U('Admin/Type/type_list'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->display();
        }
    }
    public function type_list()
    {
        $model = M('Type');
        $data = $model->select();
        $this->assign('data',$data);
        $this->display();
    }
    public function type_delete()
    {
        $id=I('get.type_id');
        $model = M('Type');
        $res = $model->delete($id);
        if($res){
            $this->success('删除成功',U('Admin/Type/type_list'));
        }else{
            $this->error('删除失败');
        }
    }
    public function type_edit()
    {
        if(IS_POST){
            $data = I('post.');
//            dump($data);die;
            $model = M('Type');
            $res = $model->save($data);
            if($res){
                $this->success('修改成功',U('Admin/Type/type_list'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id = I('get.type_id');
            $model = M('Type');
            $data = $model->select($id);
//            dump($data);die;
            $this->assign('data',$data);
            $this->display();
        }
    }
}

