<?php
namespace Lib\Applications\Index\Models;
use Tang\Database\Sql\Model;
use Tang\Database\Sql\ORM\Relations\Factory;

class User extends Model
{
    //给添加或编辑加密码
    public function updatePasswordHandler($from,$marking,&$attributes)
    {
        if(isset($attributes['password']) && $attributes['password'])
        {
            $attributes['password'] = md5($attributes['password']);
        } elseif ($marking == 'IndexOrmDemoinsert')
        {
            throw new SystemException('请输入密码');
        } else
        {
            unset($attributes['password']);
        }
        return $attributes;
    }
    /**
     * 给子类初始化
     * @return void
     */
    protected function init()
    {

        //定义过滤
        //userName错误提示使用的是语言包
        $this->validates = array(
          'userName' => array('type' => 'validate','method' => 'userName','parameters'=>array(4,10),'message' =>array('key'=>'user->Is not a valid username or user length should be between characters in 4-10'),),
          'password' => array('type'=>'validate','method'=>'stringLength','parameters'=>array(4,10),'message'=>'密码的长度应该在4-10位之间','isEmpty'=>true),//允许为空
          'realName' => array('type'=>'validate','method'=>'stringLength','parameters'=>array(2,20),'message'=>'真实姓名的长度应该在2-20位之间'),
        );
        //定义允许字段
        $this->allowColumns = array(
            'IndexOrmDemoinsert' => array('password','userName','realName'),//从index模块中OrmDemo控制器中update方法提交的 只允许提交'password','userName','realName'字段
            'IndexOrmDemoupdate' => array('password','realName')
        );
        //增加事件
        $updatePasswordHandler = array($this,'updatePasswordHandler');
        //添加前和插入前增加验证密码事件
        $this->event->addBeforeInsert($updatePasswordHandler);
        $this->event->addBeforeUpdate($updatePasswordHandler);
        //再次添加一个插入前事件，验证用户名是否存在
        $this->event->addBeforeInsert(function($from,$marking,&$attributes)
        {
            if($from->where('userName','=',$attributes['userName'])->first())
            {
                throw new \Exception('用户名已存在',2048);//2048定义错误码，控制器好根据错误码进行URL跳转，API根据错误码进行处理
            }
        });
        //定义关联
        $this->relationRules = array(
            'tags' => array(
                'type' => Factory::MANY_TO_MANY,
                'modelName' => 'Index.Tag',
                'foreignKey' => 'tagId',//必须填写，如果不填写则为userId
                'relationTable' => 'user_tag',//可不填写 系统自动生成 user_tag
                'relationForeignKey' => 'tagId',//可不填写 系统自动生成tagId
                'relationLocalKey' => 'userId',//可不填写 系统自动生成userId
                //'localKey' => 'userId',//可不填写 默认使用主键
               //这里没有数量要求 'limit' => 10,
                'constraint' => function($result)
                {
                }
            ),
            'sign' => array(
                'type' => Factory::HAS_MANY,
                'modelName' => 'Index.UserSign',
                'foreignKey' => 'userId',
                'localKey' => 'userId',//可不填写 默认使用主键
                'limit' => 10,//要10条最新
                'constraint' => function($result)
                {
                    $result->orderBy('user_sign.signTime','desc');//排序
                }
            ),
            'info' => array(
                'type' => Factory::HAS_ONE,
                'modelName' => 'Index.UserInfo',
                'foreignKey' => 'userId',//可不填写因为UserInfo模型主键是userId
                'localKey' => 'userId',//可不填写 默认使用主键
            )
        );
    }
}