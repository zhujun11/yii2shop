<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/23
 * Time: 9:37
 */
use \yii\helpers\Html;
foreach ($goodsCategories as $k=> $goodsCategory):?>
    <div class="cat <?=$k==0?'item1':'';?>">
        <h3><?php echo Html::a($goodsCategory->name,['goods/index','id'=>$goodsCategory->id]);?><b></b></h3>
        <div class="cat_detail">
            <?php foreach ($goodsCategory->children as $k1=> $child):?>
                <dl class="<?=$k1==0?'dl_1st':'';?>">
                    <dt>
                        <?php echo Html::a($child->name,['goods/index','id'=>$child->id]);?>
                    </dt>
                    <?php foreach ($child->children as $cate):?>
                        <dd>
                            <?=Html::a($cate->name,['goods/index','id'=>$cate->id]);?>

                        </dd>
                    <?php endforeach;?>
                </dl>
            <?php endforeach;?>

        </div>
    </div>
<?php endforeach;?>