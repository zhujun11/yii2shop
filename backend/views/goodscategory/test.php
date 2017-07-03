<?php if($goodsCategory->depth==0){
    echo Html::a($goodsCategory->name);
    foreach (\backend\models\GoodsCategory::find()->where(['parent_id'=>$goodsCategory->id]) as $secondCategory){
        if($goodsCategory->depth==1){
            echo Html::a($secondCategory->name);
            foreach (\backend\models\GoodsCategory::find()->where(['parent_id'=>$secondCategory->id]) as $thirdCategory){
                echo Html::a($thirdCategory->name);
            }
        }
    }
}?>