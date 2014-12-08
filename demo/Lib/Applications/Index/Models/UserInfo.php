<?php
namespace Lib\Applications\Index\Models;
use Tang\Database\Sql\Model;
use Tang\Database\Sql\ORM\Relations\Factory;

class UserInfo extends Model
{
    protected $tableName = 'user_info';
    /**
     * 给子类初始化
     * @return void
     */
    protected function init()
    {
        //定义关联
        $this->relationRules = array(
            'user' => array(//每个用户信息都属于一个用户
                'type' => Factory::BELONGS_TO,
                'modelName' => 'Index.User',
                'foreignKey' => 'userId',//可不填写
                'localKey' => 'userId',//可不填写
                'constraint' => function($result)
                    {
                    }
            )
        );
    }
}