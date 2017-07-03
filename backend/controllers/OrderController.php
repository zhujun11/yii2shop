<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/26
 * Time: 9:21
 */
namespace backend\controllers;

use backend\models\Order;
use backend\models\OrderGoods;
use yii\data\Pagination;

class OrderController extends BackedContoller {
    //订单列表
    public function actionIndex(){
        $query=Order::find();
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>10,
        ]);
        $orders =$query->offset($page->offset)->limit($page->limit)->all();


        return $this->render('index',['orders'=>$orders,'page'=>$page]);
    }
    //发货
    public function actionDeliver($id){
        $order=Order::findOne($id);
        $order->status=3;
        if ($order->save()){
            \Yii::$app->session->setFlash('success','发货成功');
            return $this->redirect(['order/index']);
        }
    }
    //订单商品详情
    public function actionGoods($id){
        $allGoods=OrderGoods::find()->where(['order_id'=>$id])->all();

        return $this->render('goods',['allGoods'=>$allGoods]);
    }
}