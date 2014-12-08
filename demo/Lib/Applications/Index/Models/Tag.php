<?php
namespace Lib\Applications\Index\Models;
use Tang\Database\Sql\Model;
use Tang\Database\Sql\ORM\Relations\Factory;

/**
 * Tag标签模型
 * Class Tag
 * @package Lib\Applications\Index\Models
 */
class Tag extends Model
{

    public static function makeTagsArray(array $tags)
    {
        $result = Tag::whereIn('tag',$tags)->get();
        foreach($result as $tag)
        {
            $key = array_search($tag->tag,$tags);
            if($key !== false)
            {
                $tags[$key] = array('tag' => $tag->tag,'tagId' => $tag->tagId);
            }
        }
        foreach($tags as $key => $value)
        {
            if(!is_array($value))
            {
                $tags[$key] = array('tag' => $value);
            }
        }
        return $tags;
    }
    /**
     * 给子类初始化
     * @return void
     */
    protected function init()
    {
        //定义关联
        $this->relationRules = array(
            'user' => array(//1个标签也可以有多个用户
                'type' => Factory::MANY_TO_MANY,
                'modelName' => 'Index.User',
                // 'foreignKey' => 'userId',//可不填写
                'relationTable' => 'user_tag',//必须 系统自动生成 tag_user 而我们的表是user_tag
                'relationForeignKey' => 'userId',//可不填写 系统自动生成userId
                'relationLocalKey' => 'tagId',//可不填写 系统自动生成tagId
                //'localKey' => 'tagId',//可不填写 默认使用主键
                //这里没有数量要求 'limit' => 10,
                'constraint' => function($result)
                {
                }
            )
        );
    }
}