<?php 
namespace Admin\Controller;
//use Think\Controller;
class ManagerController extends CommonController{
	public function manager_list(){
	    $model = M('Manager');
	    $data = $model->select();
//        dump($data);die();
	    $this->assign('data',$data);
		$this->display('manager_list');
	}
	public function manager_add(){
        if(IS_POST){
            $data = I('post.');
            $model = M('Manager');
            $res = $model->add($data);
            if($res){
                $this->success('添加成功',U('Admin/Manager/manager_list'));
            }else{
                $this->error('添加失败，请重试');
            }
        }else{
            $this->display('manager_add');
        }

	}
	public function manager_edit(){
        $model = M('Manager');
	    if(IS_POST){
	        $data = I('post.');
//            dump($data);die();
	        $res = $model->save($data);
//	        dump($res);die();
	        if($res !== false){
	            $this->success('修改成功',U('Admin/Manager/manager_list'));
            }else{
	            $this->error('修改失败，请重新修改');
            }
        }else{
            $id = I('get.id');

            $model = M('Manager');
            $data = $model->find($id);
//        dump($data);die();
            $this->assign('data',$data);
            $this->display('manager_edit');
        }

	}
	public function manager_delete(){
        $id = I('get.id');
        $model = M("Manager");
        $res = $model->delete($id);
        if($res !== false){
            $this->success('删除成功',U('Admin/Manager/manager_list'));
        }else{
            $this->error('删除失败，请重试');
        }
    }
	
}



 ?>