<?php 
//声明命名空间
namespace Admin\Model;
//引入父类模型
use Think\Model;
//定义模型类
class GoodsModel extends Model{
//模型中的属性和方法
//
//定义字段
//    protected $fields = array('goods_id','goods_name');
    //字段映射
    protected $_map = array(
        'name' => 'goods_name',
        'price' => 'goods_price',
        'number' => 'goods_number'
    );
    protected $_validate = array(
      array('goods_name','require','商品名称不能为空','0','3'),
        array('goods_price','require','商品价格不能为空','0','3'),
        array('goods_price','currency','商品价格格式不正确','0','3'),
        array('goods_number','require','商品数量不能为空','0','3'),
        array('goods_number','number','商品数量不能为空','0','3'),
    );
    protected $_auto = array(
      array('goods_create_time','time',1,'function'),
    );
    public function upload_logo($files,$data){   //传递文件数组 和需要存到数据库得$data数组
        $config = array(
            'maxSize' => 2*1024*1024,//上传文件的最大限制
            'ext' => array('jpg','png','gif','jpeg',),//允许上传的文件的后缀
            'rootPath' => WEB_ROOT.UPLOAD_PATH,//保存的根路径
        );
        $upload = new \Think\Upload($config);
        $upload_res = $upload->uploadOne($files['goods_img']);
        //dump($upload_res);die();
        if($upload_res){
            //文件上传成功
            $data['goods_big_img'] = UPLOAD_PATH.$upload_res['savepath'].$upload_res['savename'];
            //文件上传成功后生成缩略图
            $image = new \Think\Image();
            //打开一幅图像  用open方法
            $image->open(WEB_ROOT.UPLOAD_PATH.$upload_res['savepath'].$upload_res['savename']);
            //生成缩略图
            $image->thumb(50,30);
            //保存缩略图
            $save_path = UPLOAD_PATH.$upload_res['savepath'].'thumb'.$upload_res['savename'];
            $image->save(WEB_ROOT.$save_path);
            $data['goods_small_img'] = $save_path;
            return $data;
    }else{
            return false;
        }
    }
//    public function upload_pics($files,$goods_id){
//        //先判断是否有文件要上传  如果最小值为0  表示有文件是要上传得  如果额米有0 则 表示没有文件要上传
//        if(min($files['goods_pics']['error']) !=0 ){
//            return false;
//        }
//        $config = array(
//            'maxSize' => 2*1024*1024,//上传文件的最大限制
//            'ext' => array('jpg','png','gif','jpeg',),//允许上传的文件的后缀
//            'rootPath' => WEB_ROOT.UPLOAD_PATH,//保存的根路径
//        );
//        $upload = new \Think\Upload($config);
//        //使用upload方法实现文件得上传
//        $res = $upload -> upload($files);
//        if(!$res){
//            return false;
//        }
//        //上传成功 需要对$res进行处理 要上传成功得文件都保存在数据库tpshop_goodspics表
//        //多文件上传 需要同时插入多条数据
//        //如何组装多条数据
//        $data = array();
//        foreach ($res as $k => $v){
//            $data['$k']['goods_id'] = $goods_id;
//            $data['$k']['pics_origin'] = UPLOAD_PATH.$v['savepath'].$v['savename'];
//
//
//            //生成缩略图
//            $image = new \Think\Image();
//            //打开缩略图
//            $image->open(WEB_ROOT.$data[$k]['pics_origin']);
//            //生成缩略图
//            $image->thumb(800,800);
//            $image->save(WEB_ROOT.UPLOAD_PATH.$v['savepath'].'big_'.$v['savename']);
//            $image->thumb(350,350);
//            $image->save(WEB_ROOT.UPLOAD_PATH.$v['savepath'].'mid_'.$v['savename']);
//            $image->thumb(50,50);
//            $image->save(WEB_ROOT.UPLOAD_PATH.$v['savepath'].'sma_'.$v['savename']);
//
//            $data['$k']['pics_big'] = UPLOAD_PATH.$v['savepath'].'big_'.$v['savename'];
//            $data['$k']['pics_mid'] = UPLOAD_PATH.$v['savepath'].'mid_'.$v['savename'];
//            $data['$k']['pics_sma'] = UPLOAD_PATH.$v['savepath'].'sma_'.$v['savename'];
//        }
//        //添加多条数据到数据表
//        $result = M('Goodspics')->addAll($data);
//        if($result){
//            return true;
//        }else{
//            return false;
//        }
//    }
    public function upload_pics($files,$goods_id){
        //判断是否有文件需要上传
        //方法：判断$files['goods_pics']['error'] 这个数组中 最小值 如果为0 表示有文件需要上传，否则所有文件都发生了错误，不需要上传
        // if判断条件中 如果是判断一个变量 == 值， 为了防止少写一个=，可以把值写在前面 变量写在等号后面 这样如果少些一个等号 直接会报错
        if(0 != min($files['goods_pics']['error'])){
            return false;
        }
        //实例化文件上传类
        $config = array(
            'maxSize' => 2 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)(单位byte)
            'exts' => array('jpg','png','gif','jpeg'), //允许上传的文件后缀
            'rootPath' => WEB_ROOT . UPLOAD_PATH, //保存根路径
        );
        $upload = new \Think\Upload($config);
        //使用upload方法完成多文件的上传
        $res = $upload -> upload($files);
        // dump($res);die;
        if(!$res){
            return false;
        }
        //上传成功，需要对$res进行处理，要上传成功的图片地址都保存到数据库tpshop_goodspics表
        //多文件上传，需要同时向数据库插入多条记录  add()  addAll($data)
        //如何组装多条数据的数组
        $data = array();
        foreach($res as $k => $v){
            $data[$k]['goods_id'] = $goods_id;
            $data[$k]['pics_origin'] = UPLOAD_PATH . $v['savepath'] . $v['savename'];

            //生成缩略图
            $image = new \Think\Image();
            $image -> open(WEB_ROOT . $data[$k]['pics_origin']);
            //生成大图 800 * 800
            $image -> thumb(800, 800);
            $image -> save(WEB_ROOT . UPLOAD_PATH . $v['savepath'] . 'big_' . $v['savename']);
            //生成中图 350 * 350
            $image -> thumb(350, 350);
            $image -> save(WEB_ROOT . UPLOAD_PATH . $v['savepath'] . 'mid_' . $v['savename']);
            //生成中图 50 * 50
            $image -> thumb(50, 50);
            $image -> save(WEB_ROOT . UPLOAD_PATH . $v['savepath'] . 'sma_' . $v['savename']);

            $data[$k]['pics_big'] = UPLOAD_PATH . $v['savepath'] . 'big_' . $v['savename'];
            $data[$k]['pics_mid'] = UPLOAD_PATH . $v['savepath'] . 'mid_' . $v['savename'];
            $data[$k]['pics_sma'] = UPLOAD_PATH . $v['savepath'] . 'sma_' . $v['savename'];
        }
        //添加多条数据到数据表
        $result = M('Goodspics') -> addAll($data);
        if($result){
            return true;
        }else{
            return false;
        }

    }

}



 ?>