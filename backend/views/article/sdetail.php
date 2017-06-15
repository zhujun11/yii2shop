
        <h1 style="text-align: center"><?=$article->name?></h1>
        <?='文章分类:'.$article->articleCategory->name?>


        <p><?=$content->content?></p>


<?php echo \yii\bootstrap\Html::a('返回文章列表',['article/index'])?>