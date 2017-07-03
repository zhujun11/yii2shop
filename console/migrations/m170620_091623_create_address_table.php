<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170620_091623_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(30)->comment('收货人姓名'),
            'member_id'=>$this->integer()->comment('账号ID'),
            'province_id'=>$this->integer()->comment('省份ID'),
            'city_id'=>$this->integer()->comment('城市ID'),
            'area_id'=>$this->integer()->comment('区县ID'),
            'address'=>$this->string(100)->comment('详细地址'),
            'tel'=>$this->char(11)->comment('手机号'),
            'status'=>$this->integer()->comment('为默认地址')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
