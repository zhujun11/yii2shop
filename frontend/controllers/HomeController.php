<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/19
 * Time: 17:57
 */
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;

class HomeController extends Controller{
    public $layout='index';
    //后台首页
    public function actionIndex(){
        //获取商品分类
        $goodsCategories=GoodsCategory::find()->where(['parent_id'=>0])->all();

        return $this->render('index',['goodsCategories'=>$goodsCategories]);
    }
    //测试redis
//    public function actionRedis(){
//        \Yii::$app->redis->set('test','111');  //设置redis缓存
//        echo \Yii::$app->redis->get('test');   //读取redis缓存
//        exit;
//    }
    //商品订单
    public function actionOrder(){
        $user=\Yii::$app->user;
        if($user->isGuest){
            return $this->redirect(['regist/login']);

        }else{
            $addresses=Address::find()->where(['member_id'=>$user->id])->all();//查地址表
            $carts=Cart::find()->where(['member_id'=>$user->id])->all();

            return $this->render('order',['addresses'=>$addresses,'carts'=>$carts]);
        }
    }
    public function actionAddOrder(){
        $order=new Order();
        $data=\Yii::$app->request->post();
        $cart_ids=explode(',',$data['cart_ids']);
        //生成订单
        $order->member_id=\Yii::$app->user->id;
        $address=Address::findOne($data['address_id']);
        $order->name=$address->name;
        $order->province=$address->province->name;
        $order->city=$address->city->name;
        $order->area=$address->area->name;
        $order->address=$address->address;
        $order->tel=$address->tel;
        //送货方式
        $order->delivery_id=$data['delivery_id'];
        $delivery_name='';
        $delivery_price=0;
        foreach (Order::$deliveries as $delivery){
            if ($delivery['id']==$data['delivery_id']){
                $delivery_name=$delivery['name'];
                $delivery_price=$delivery['price'];
            }
        }
        $order->delivery_name=$delivery_name;
        $order->delivery_price=$delivery_price;
        //付款方式
        $order->payment_id=$data['payment_id'];
        $payment_name='';
        foreach (Order::$payments as $payment){
            if ($payment['id']==$data['payment_id']){
                $payment_name=$payment['name'];
            }
        }
        $order->payment_name=$payment_name;
        $order->total=$data['total'];
        $order->status=1;
        $order->create_time=time();
        $transaction=\Yii::$app->db->beginTransaction();//开启事务,在数据写入之前
        try{
            if ($order->save()){

                //生成订单商品表数据和删除购物车数据
                foreach ($cart_ids as $cart_id){
                    $order_goods=new OrderGoods();
                    $order_goods->order_id=$order->id;
                    $cart=Cart::findOne($cart_id);
                    $goods=Goods::findOne($cart->goods_id);
                    if ($goods==null){
                        //商品不存在了
                        $goods=new Exception('商品不存在');
                        throw $goods;
                    }
                    if ($cart->amount>$goods->stock){
                        //商品库存不满足购买数量的时候
                        $stock=new Exception('商品已卖完');
                        throw $stock;
                    }

                    $order_goods->goods_id=$cart->goods_id;
                    $order_goods->goods_name=$goods->name;
                    $order_goods->logo=$goods->logo;
                    $order_goods->price=$goods->shop_price;;
                    $order_goods->amount=$cart->amount;
                    $order_goods->status=1;
                    $order_goods->total=$cart->amount*$goods->shop_price;
                    if($order_goods->save()){
                        //修改商品库存
                        $goods->stock-=$cart->amount;
                        $goods->save();
                        //删除购物车数据
                        $cart->delete();
                    }
                }
            }
            $transaction->commit();
            echo 'success';
        }catch (Exception $e){
            $transaction->rollBack();
            if ($e==$stock){
                echo '商品已卖完';
            }
            if ($e==$goods){
                echo '商品保存在了';
            }
        }

    }
    //订单生成成功提示页
    public function actionOrder1(){
        return $this->render('order1');
    }

}