<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/26
 * Time: 9:45
 */
?>
<table class="table table-bordered">
    <tr>
        <th>订单号</th>
        <th>收货人</th>
        <th>收货地址</th>
        <th>联系电话</th>
        <th>送货方式</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($orders as $order):?>
    <tr>
        <td><?=$order->id?></td>
        <td><?=$order->name?></td>
        <td><?=$order->name.
            $order->province.
            $order->city.
            $order->area.
            $order->address
            ?>
        </td>
        <td><?=$order->tel?></td>
        <td><?=$order->delivery_name?></td>
        <td><?=\backend\models\Order::$status[$order->status]?></td>
        <td>
        <?php
            if ($order->status ==2){
                echo \yii\bootstrap\Html::a('发货 | ',['order/deliver','id'=>$order->id]);
            }elseif($order->status >2){

                echo '已发货 | ';
            }
            echo \yii\bootstrap\Html::a('查看订单商品',['order/goods','id'=>$order->id])
        ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
