<?php
namespace Lib\Applications\Index\Models;
use Tang\Database\Sql\Model;
use Tang\Database\Sql\ORM\Relations\Factory;

/**
 * 用户签名
 * Class UserSign
 * @package Lib\Applications\Index\Models
 */
class UserSign extends Model
{
    protected $tableName = 'user_sign';
    /**
     * 给子类初始化
     * @return void
     */
    protected function init()
    {
        //定义关联
        $this->relationRules = array(
            'user' => array(//每个签名都属于一个用户
                'type' => Factory::BELONGS_TO,
                'modelName' => 'Index.User',
                 'foreignKey' => 'userId',//可不填写
                 'localKey' => 'userId',//必须填写，不然使用默认主键id
                'constraint' => function($result)
                 {
                }
            )
        );
    }
}