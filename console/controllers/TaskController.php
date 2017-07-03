<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/27
 * Time: 20:15
 */
//处理过期时间的订单---清除,订单有效时间一个小时
/**
 * 思路:1.获取所有的超时订单
 *      2.修改相应订单的状态--改为已取消,并且返回库存
 *      3.订单量大,使用自动执行
 *          在Yii2框架中,可以直接在控制台文件console下的控制器文件controller下创建一个脚本文件
 *          使用一个死循环让它一直执行,设置脚本执行时间;设置脚本执行间隔时间,然后手动触发第一次
 */

namespace console\controllers;

use backend\models\Goods;
use frontend\models\Order;
use yii\console\Controller;

class TaskController extends Controller{
    public function actionClear(){
        //set_time_limit(0);//设置脚本执行时间,一直执行
        //while (1) {
            $time = time() - 3600;
            $orders = Order::find()->where(['status' => 1])->andWhere(['<', 'create_time', $time])->all();
            foreach ($orders as $order){
            /*$order->status=0;
            $order->save();
            foreach ($order->goods as $goods){//返回库存
                Goods::updateAllCounters(['stock'=>$goods->amount],'id='.$goods->goods_id);
            }*/
            //测试
                echo 'ID为:'.$order->id.'的订单被取消了';
            }
            //sleep(1);//设置清理间隔时间
        //}
    }


}