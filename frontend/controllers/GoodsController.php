<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/21
 * Time: 16:25
 */
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller{
    public $layout='address';

    //商品列表---3级
    public function actionIndex($id){
        $query=Goods::find();
        $page=new Pagination([
            'totalCount'=>$query->count($id),
            'defaultPageSize'=>8
        ]);
        $allGoods=$query->offset($page->offset)->limit($page->limit)->where(['goods_category_id'=>$id])->all();
        $brands=[];
        foreach ($allGoods as $goods){
            $brands[]=$goods->brand->name;
        }
        $brands=array_unique($brands);
        $GoodsCategory=GoodsCategory::findOne($id);
//        var_du.mp($page->pageSize);exit;
        return $this->render('lists',['allGoods'=>$allGoods,'goodsCategory'=>$GoodsCategory,'brands'=>$brands,'page'=>$page]);
    }
    //详情页
    public function actionContent($id){
        $goods=Goods::findOne($id);
        if ($goods==null){
            throw new NotFoundHttpException('商品已断货');
        }
        return $this->render('content',['goods'=>$goods]);
    }
    //添加商品到购物车
    public function actionAddCart(){
        //获取被选商品的信息
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        $goods=Goods::findOne($goods_id);
        if ($goods==null){
            throw new NotFoundHttpException('商品已断货');
        }
//        var_dump($goods);exit;
        //判断是否已登录
        if (\Yii::$app->user->isGuest){//是游客及未登录就保存到COOKIE中
            //判读cookie中是否存在购物车数据
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){//没有就设置一个空数组,以便保存新购物车数据
                $cart=[];//保存商品和商品数量的数组key==商品ID,value==商品数量
            }else{//有就获取cookie中的购物车数据
                $cart=unserialize($cookie->value);
            }
            $cookies=\Yii::$app->response->cookies;
            //判断新购物车数据是否已存在,已存在就只更新数量,不是就再添加
            if(key_exists($goods->id,$cart)){
                $cart[$goods->id]+=$amount;
            }else{
                //如果不存在就添加
                $cart[$goods->id]=$amount;
            }
            //设置cookie对象
            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart),
            ]);
            //保存cookie对象到COOKIE中
            $cookies->add($cookie);
        }else{

            //如果是登录的状态就直接保存到数据表中
            //判断数据表中是否存在商品
            $cart=Cart::findOne(['goods_id'=>$goods->id]);
            if ($cart==null){
                $cart=new Cart();
                $cart->goods_id=$goods->id;
                $cart->amount=$amount;
                $cart->member_id=\Yii::$app->user->id;
            }else{
                $cart->amount+=$amount;
            }
                $cart->save();
        }


        return $this->redirect(['goods/cart']);
    }

    //显示购物车中商品列表
    public function actionCart(){
        //判断是否是游客
        $price_total=0;//总金额
        if (\Yii::$app->user->isGuest){
            //是游客就从cookie中取购物车数据
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            //判断COOKIE是否存在
            if ($cookie==null){
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);
            }
            $goods_info=[];//设置一个数组来保存商品信息
            foreach ($cart as $goods_id =>$amount){
                //读取数据库中相应商品信息,并转化成数组
                $goods=Goods::findOne($goods_id)->attributes;
                $goods['amount']=$amount;
                $goods_info[]=$goods;
                $price_total+=$goods['shop_price']*$goods['amount'];
            }

        }else{

            //获取数据库的购物车信息
            $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $goods_info=[];
            if ($carts==null){
                throw new NotFoundHttpException(Html::a('去购物',['goods/index']));
            }else{
                foreach ($carts as $cart){
                    $goods=Goods::findOne($cart->goods_id)->attributes;
                    $goods['amount']=$cart->amount;
                    $goods_info[]=$goods;
                    $price_total+=$goods['shop_price']*$goods['amount'];
                }
            }


        }
        return $this->render('cart',['goods_info'=>$goods_info,'total'=>$price_total]);
    }

    //修改购物车数据
    public function actionUpdateCart(){
        //获取被选商品的信息
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        $goods=Goods::findOne($goods_id);
        if ($goods==null){
            throw new NotFoundHttpException('商品已断货');
        }
//        var_dump($goods);exit;
        //判断是否已登录
        if (\Yii::$app->user->isGuest){//是游客及未登录就保存到COOKIE中
            //判读cookie中是否存在购物车数据
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie==null){//没有就设置一个空数组,以便保存新购物车数据
                $cart=[];//保存商品和商品数量的数组key==商品ID,value==商品数量
            }else{//有就获取cookie中的购物车数据
                $cart=unserialize($cookie->value);
            }
            $cookies=\Yii::$app->response->cookies;
            //判断是否是删除,如果amount=0就是删除
            if ($amount){
                $cart[$goods->id]=$amount;
            }else{
                if (key_exists($goods['id'],$cart))unset($cart[$goods_id]);
            }

            //设置cookie对象
            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart),
            ]);
            //保存cookie对象到COOKIE中
            $cookies->add($cookie);

        }else{
            //如果是登录的状态就直接保存到数据表中
            $cart=Cart::findOne(['goods_id'=>$goods->id]);
            //判断数量是多少,0就删除
            if($amount){
            $cart->amount=$amount;
            $cart->save();
            }else{
                $cart->delete();
            }
        }


        return $this->redirect(['goods/cart']);
    }
    //订单列表页
    public function actionOrder(){
        $user=\Yii::$app->user;
        if($user->isGuest){
            return $this->redirect(['regist/login']);
        }else{
            $orders=Order::find()->where(['member_id'=>$user->id])->where('status>0')->all();
            return $this->render('order',['orders'=>$orders]);
        }
    }
    //确认收货
    public function actionDeliverGoods($id,$goods_id){
        //订单商品--确认收货
        $order_goods=OrderGoods::findOne(['order_id'=>$id,'goods_id'=>$goods_id]);
        $order_goods->status=4;
        $order_goods->save();
        //整个订单确认收货
        $goods_all=OrderGoods::find()->where(['order_id'=>$id])->all();
        $status_sum=0;
        foreach ($goods_all as $goods){
            $status_sum+=$goods->status;
        }
        if(($status_sum%4)==0){
            $order=Order::findOne($id);
            $order->status=4;
            $order->save();
        }
        return $this->redirect(['goods/order']);
    }
    //删除订单商品
    public function actionOrderDel($id,$goods_id){
        $order_goods=OrderGoods::findOne(['order_id'=>$id,'goods_id'=>$goods_id]);
        //将商品数量返还库存
        Goods::updateAllCounters(['stock'=>$order_goods->amount],'id='.$order_goods->goods_id);

        $order_goods->delete();
        //没有商品就删除就删除订单---彻底删除
        $goods_num=OrderGoods::find()->where(['order_id'=>$id])->count();
        if ($goods_num==0){
            $order=Order::findOne($id);
            $order->delete();
        }
        return $this->redirect(['goods/order']);
    }
    //处理过期时间的订单---清除,订单有效时间一个小时
    /**
     * 思路:1.获取所有的超时订单
     *      2.修改相应订单的状态--改为已取消
     *      3.订单量大,使用自动执行
     *          在Yii2框架中,可以直接在控制台文件console下的控制器文件controller下创建一个脚本文件
     *          使用一个死循环让它一直执行,设置脚本执行时间;设置脚本执行间隔时间,然后手动触发第一次
     */
    public function actionClear(){
        set_time_limit(0);//设置脚本执行时间,一直执行
        while (1){
            $time=time()-3600;
            $orders=Order::find()->where(['status'=>1])->andWhere(['<','create_time',$time])->all();
            foreach ($orders as $order){
                /*$order->status=0;
                $order->save();
                foreach ($order->goods as $goods){//返回库存
                    Goods::updateAllCounters(['stock'=>$goods->amount],'id='.$goods->goods_id);
                }*/
                //测试
                echo 'ID为:'.$order->id.'的订单被取消了';
            }
            sleep(1);//设置清理间隔时间
        }

    }



}