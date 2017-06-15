<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170608_090722_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('文章名称'),
            'intro'=>$this->text()->comment('文章简介'),
            'article_category_id'=>$this->integer()->comment('文章分类ID'),
            'sort'=>$this->integer()->comment('文章排序'),
            'status'=>$this->integer()->comment('状态'),
            'create_time'=>$this->integer()->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
