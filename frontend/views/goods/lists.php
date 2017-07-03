<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Html;
//$this->registerCssFile('/assets/98f761c4/css/bootstrap.css')
$this->registerCss('#goods_page li{float:left}')
?>

<div class="list w1210 bc mt10">
    <!-- 面包屑导航 start -->
    <div class="breadcrumb">
        <h2>当前位置：<?=Html::a('首页',['home/index'])?> > <?=Html::a($goodsCategory->parent->name)?></h2>
    </div>
    <!-- 面包屑导航 end -->

    <!-- 左侧内容 start -->
    <div class="list_left fl mt10">
        <!-- 分类列表 start -->
        <div class="catlist">
            <h2><?=$goodsCategory->parent->name?></h2>
            <div class="catlist_wrap">
                <?php foreach ($goodsCategory->parent->children as $child):?>
                <div class="child">
                    <h3 class="on"><b></b><?=$child->name?></h3>
                    <ul>
                    <?php foreach ($child->children as $third):?>
                        <li><?=Html::a($third->name,['goods/index','id'=>$third->id])?></li>
                    <?php endforeach;?>
                    </ul>
                </div>

                <?php endforeach;?>
            </div>

            <div style="clear:both; height:1px;"></div>
        </div>
        <!-- 分类列表 end -->

        <div style="clear:both;"></div>

        <!-- 新品推荐 start -->
        <div class="newgoods leftbar mt10">
            <h2><strong>新品推荐</strong></h2>
            <div class="leftbar_wrap">
                <ul>
                    <li>
                        <dl>
                            <dt><a href=""><img src="images/list_hot1.jpg" alt="" /></a></dt>
                            <dd><a href="">美即流金丝语悦白美颜新年装4送3</a></dd>
                            <dd><strong>￥777.50</strong></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><img src="images/list_hot2.jpg" alt="" /></a></dt>
                            <dd><a href="">领券满399减50 金斯利安多维片</a></dd>
                            <dd><strong>￥239.00</strong></dd>
                        </dl>
                    </li>

                    <li class="last">
                        <dl>
                            <dt><a href=""><img src="images/list_hot3.jpg" alt="" /></a></dt>
                            <dd><a href="">皮尔卡丹pierrecardin 男士长...</a></dd>
                            <dd><strong>￥1240.50</strong></dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>
        <!-- 新品推荐 end -->

        <!--热销排行 start -->
        <div class="hotgoods leftbar mt10">
            <h2><strong>热销排行榜</strong></h2>
            <div class="leftbar_wrap">
                <ul>
                    <li></li>
                </ul>
            </div>
        </div>
        <!--热销排行 end -->

        <!-- 最近浏览 start -->
        <div class="viewd leftbar mt10">
            <h2><a href="">清空</a><strong>最近浏览过的商品</strong></h2>
            <div class="leftbar_wrap">
                <dl>
                    <dt><a href=""><img src="images/hpG4.jpg" alt="" /></a></dt>
                    <dd><a href="">惠普G4-1332TX 14英寸笔记...</a></dd>
                </dl>

                <dl class="last">
                    <dt><a href=""><img src="images/crazy4.jpg" alt="" /></a></dt>
                    <dd><a href="">直降200元！TCL正1.5匹空调</a></dd>
                </dl>
            </div>
        </div>
        <!-- 最近浏览 end -->
    </div>
    <!-- 左侧内容 end -->

    <!-- 列表内容 start -->
    <div class="list_bd fl ml10 mt10">
        <!-- 热卖、促销 start -->
        <div class="list_top">
            <!-- 热卖推荐 start -->
            <div class="hotsale fl">
                <h2><strong><span class="none">热卖推荐</span></strong></h2>
                <ul>
                    <li>
                        <dl>
                            <dt><a href=""><img src="images/hpG4.jpg" alt="" /></a></dt>
                            <dd class="name"><a href="">惠普G4-1332TX 14英寸笔记本电脑 （i5-2450M 2G 5</a></dd>
                            <dd class="price">特价：<strong>￥2999.00</strong></dd>
                            <dd class="buy"><span>立即抢购</span></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><img src="images/list_hot3.jpg" alt="" /></a></dt>
                            <dd class="name"><a href="">ThinkPad E42014英寸笔记本电脑</a></dd>
                            <dd class="price">特价：<strong>￥4199.00</strong></dd>
                            <dd class="buy"><span>立即抢购</span></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><img src="images/acer4739.jpg" alt="" /></a></dt>
                            <dd class="name"><a href="">宏碁AS4739-382G32Mnkk 14英寸笔记本电脑</a></dd>
                            <dd class="price">特价：<strong>￥2799.00</strong></dd>
                            <dd class="buy"><span>立即抢购</span></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <!-- 热卖推荐 end -->

            <!-- 促销活动 start -->
            <div class="promote fl">
                <h2><strong><span class="none">促销活动</span></strong></h2>
                <ul>
                    <li><b>.</b><a href="">DIY装机之向雷锋同志学习！</a></li>
                    <li><b>.</b><a href="">京东宏碁联合促销送好礼！</a></li>
                    <li><b>.</b><a href="">台式机笔记本三月巨惠！</a></li>

                </ul>

            </div>
            <!-- 促销活动 end -->
        </div>
        <!-- 热卖、促销 end -->

        <div style="clear:both;"></div>

        <!-- 商品筛选 start -->
        <div class="filter mt10">
            <h2><a href="">重置筛选条件</a> <strong>商品筛选</strong></h2>
            <div class="filter_wrap">
                <dl>
                    <dt>品牌：</dt>
                    <dd class="cur"><a href="">不限</a></dd>
                    <?php
                    foreach ($brands as $brand):
                    ?>
                    <dd><a href=""><?=$brand?></a></dd>
                    <?php endforeach;?>
                </dl>

                <dl>
                    <dt>价格：</dt>
                    <dd class="cur"><a href="">不限</a></dd>
                    <dd><a href="">1000-1999</a></dd>
                    <dd><a href="">2000-2999</a></dd>
                    <dd><a href="">3000-3499</a></dd>
                    <dd><a href="">3500-3999</a></dd>
                    <dd><a href="">4000-4499</a></dd>
                    <dd><a href="">4500-4999</a></dd>
                    <dd><a href="">5000-5999</a></dd>
                    <dd><a href="">6000-6999</a></dd>
                    <dd><a href="">7000-7999</a></dd>
                </dl>

                <dl>
                    <dt>尺寸：</dt>
                    <dd class="cur"><a href="">不限</a></dd>
                    <dd><a href="">10.1英寸及以下</a></dd>
                    <dd><a href="">11英寸</a></dd>
                    <dd><a href="">12英寸</a></dd>
                    <dd><a href="">13英寸</a></dd>
                    <dd><a href="">14英寸</a></dd>
                    <dd><a href="">15英寸</a></dd>
                </dl>

                <dl class="last">
                    <dt>处理器：</dt>
                    <dd class="cur"><a href="">不限</a></dd>
                    <dd><a href="">intel i3</a></dd>
                    <dd><a href="">intel i5</a></dd>
                    <dd><a href="">intel i7</a></dd>
                    <dd><a href="">AMD A6</a></dd>
                    <dd><a href="">AMD A8</a></dd>
                    <dd><a href="">AMD A10</a></dd>
                    <dd><a href="">其它intel平台</a></dd>
                </dl>
            </div>
        </div>
        <!-- 商品筛选 end -->

        <div style="clear:both;"></div>

        <!-- 排序 start -->
        <div class="sort mt10">
            <dl>
                <dt>排序：</dt>
                <dd class="cur"><a href="">销量</a></dd>
                <dd><a href="">价格</a></dd>
                <dd><a href="">评论数</a></dd>
                <dd><a href="">上架时间</a></dd>
            </dl>
        </div>
        <!-- 排序 end -->

        <div style="clear:both;"></div>

        <!-- 商品列表 start-->
        <div class="goodslist mt10">
            <ul>
                <?php foreach ($allGoods as $goods):?>
                <li>
                    <dl>
                        <dt><?=Html::a(Html::img('http://admin.yii2shop.com'.$goods->logo),['goods/content','id'=>$goods->id])?>
                        </dt>
                        <dd><?=Html::a($goods->name,['goods/content','di'=>$goods->id])?></dd>
                        <dd><strong><?=$goods->shop_price?></strong></dd>
                        <dd><a href=""><em>已有10人评价</em></a></dd>
                    </dl>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
        <!-- 商品列表 end-->

        <!-- 分页信息 start -->
        <div class="page mt20">
        <?php
        /**
         * @var
         */
        echo \yii\widgets\LinkPager::widget([
                'pagination'=>$page,
//                'hideOnSinglePage'=>false,
//                'firstPageLabel'=>'首页',
                'prevPageLabel'=>'上一页',
                'nextPageLabel'=>'下一页',
//                'lastPageLabel'=>'末页',
                'options'=>['id'=>'goods_page'],
            ]
        )?>

    </div>
    <!-- 列表内容 end -->
</div>