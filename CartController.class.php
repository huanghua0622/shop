<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/12
 * Time: 13:27
 */
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller{
    public function addcart()
    {
        $data = I('post.');
//        dump($data);die;
        $model = D('Cart');
        $model->addCart($data['goods_id'],$data['goods_attr_ids'],$data['number']);
        $goods = M('Goods')->find($data['goods_id']);
        $this->assign('goods',$goods);
        $this->display();

    }
    public function cart()
    {
        //先获取购物车里面的数据
        $data = D('Cart')->getAllCart();
//        dump($data);die;
        foreach($data as $k=>$v){
            $goods = M('Goods')->find($v['goods_id']);

            $v['goods_name'] = $goods['goods_name'];
            $v['goods_small_img'] = $goods['goods_small_img'];
            $v['goods_price'] = $goods['goods_price'];
            $goods_attr = M('GoodsAttr')->alias('t1')->field('t1.*,t2.attr_name')->join("left join tpshop_attribute t2 on t1.attr_id=t2.attr_id")->where("t1.id in ({$v['goods_attr_ids']})") -> select();
            $v['goods_attr'] = $goods_attr;

        }


        $this->assign('data',$data);
        $this->display();
    }
    public function ajaxgetnum()
    {
        $total_number = D('Cart')->getNumber();
        $return = array(
            'code'=>10000,
            'msg' =>'success',
            'total_number' => $total_number
        );
        $this->ajaxReturn($return);
    }
    public function ajaxchangenum()
    {
        $data = I('post.');
//        dump($data);die;
        D('Cart') -> changeNumber($data['goods_id'], $data['goods_attr_ids'], $data['number']);
        $total_number = D('Cart') -> getNumber();

        $return = array(
            'code' => 10000,
            'msg' => 'success',
            'total_number' => $total_number

        );
        $this->ajaxReturn($return);
    }
    public function ajaxdelcart()
    {
        $data = I('post.');
        D('Cart')->delCart($data['goods_id'],$data['goods_attr_ids']);
        $total_number = D('Cart')->getNumber();
        $return = array(
            'code'=>10000,
            'msg'=>'success',
            'total_number' => $total_number,
        );
        $this->ajaxReturn($return);
    }
    public function flow2()
    {
        if(session('?user_info')){
            $user_id = session('user_info.id');
            //收获地址
            $address = M('Address')-> where("user_id = $user_id") -> select();
//            dump($address);die;
            $this->assign('address',$address);
            //获取购物车中指定的数据
            $cart_ids = I('get.cart_ids');
            $cart = M('Cart')->alias('t1')->field('t1.*,t2.goods_name,t2.goods_small_img,t2.goods_price')
                ->join('left join tpshop_goods t2 on t1.goods_id = t2.id')
                    ->where("t1.id in ($cart_ids)")
                        ->select();
//            dump($cart);die;
            foreach($cart as $k=>&$v){
                $v["goods_attr"] = M('GoodsAttr')->alias('t3')->field('t3.*,t4.attr_name')
                        ->join("left join tpshop_attribute t4 on t3.attr_id = t4.attr_id")
                            ->where("t3.id in ({$v['goods_attr_ids']})")
                                ->select();
                //计算总金额
//                dump($v);die;
                $total_price += $v['goods_price'] * $v['number'];
//                dump($total_price);die;
            }
            $this->assign('cart',$cart);
            $this->assign('total_price',$total_price);
            $this->display();

        }else{
            //这里写这个是为了在登录之后调回到购物车界面
            session('back_url',U('Home/Cart/cart'));
            $this->redirect('Home/User/login');
        }

    }
    public function createorder()
    {
        if(IS_POST){
            $data = I('post.');
//        dump($data);die;
            //生成订单标号
            $data['order_sn'] = date('YmdHis') . rand(10000,99999);
            //用户id
            $data['user_id'] = session('user_info.id');
            //创建时间
            $data['create_time'] = time();
            //订单总金额要连表查询 $data['cart_ids']
            $goods_data =   M('Cart')->alias('t1') ->field('t1.*,t2.goods_price')
                    ->join('left join tpshop_goods t2 on t1.goods_id = t2.id')
                        ->where("t1.id in ({$data['cart_ids']})")
                            ->select();
//            dump($goods_data);die;
            foreach($goods_data as $k=>$v){
                $data['order_amount'] += $v['goods_price'] * $v['number'];
            }
            unset($k,$v);
            $order_id = M('Order') -> add($data);
//            dump($order_id);die;
            if($order_id){
                foreach($goods_data as $k=>$v){
                    $order_data['order_id'] = $order_id;
                    $order_data['goods_id'] = $v['goods_id'];
                    $order_data['goods_price'] = $v['goods_price'];
                    $order_data['number'] = $v['number'];
                    $order_data['goods_attr_ids'] = $v['goods_attr_ids'];
                    M('OrderGoods')->add($order_data);
                }
                echo 'to pay';
                sleep(3);
                //跳转到支付成功的界面
                $this->redirect('Home/Cart/flow3');

            }else{
                $this->error('提交订单失败');
            }
        }
        //支付成功界面

    }
    public function flow3()
    {
        $this->display();
    }
}