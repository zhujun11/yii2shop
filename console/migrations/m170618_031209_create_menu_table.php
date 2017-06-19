<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170618_031209_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label'=>$this->string(20)->comment('名称'),
            'url'=>$this->string(255)->comment('路由地址'),
            'parent_id'=>$this->integer()->comment('上级菜单'),
            'short'=>$this->integer()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
