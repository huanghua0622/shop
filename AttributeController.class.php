<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/9
 * Time: 13:20
 */

namespace Admin\Controller;


class AttributeController extends CommonController
{
    public function attr_add()
    {
        if(IS_POST){
            $data = I('post.');
            dump($data);die;
            $model= M('Attribute');
            $res = $model->add($data);
            if($res){
                $this->success('添加成功',U('Admin/Attribute/attr_list'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $type = M('Type')->select();
            $this->assign('type',$type);
            $this->display();
        }
    }
    public function attr_list()
    {
        $data = M('Attribute')->join('left join tpshop_type on (tpshop_Attribute.type_id=tpshop_type.type_id)')->select();
        $this->assign('data',$data);
        $this->display();
    }
    public function attr_delete()
    {
        $id = I('get.attr_id');
        $model = M('Attribute');
        $res = $model->delete($id);
        if($res){
            $this->success('删除成功',U('Admin/Attribute/attr_list'));
        }else{
            $this->error('删除失败');
        }
    }
    public function  attr_edit()
    {
        if(IS_POST){
            $data = I('post.');
            dump($data);die;
            $model= M('Attribute');
            $res = $model->save($data);
            dump($res);
            if($res){
                $this->success('修改成功',U('Admin/Attribute/attr_list'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id = I('get.attr_id');
            $model = M('Attribute');
            $type = M('Type')->select();
            $data = $model->select($id);
//            dump($data);die;
            $this->assign('type',$type);
            $this->assign('data',$data);
            $this->display();
        }
    }
}