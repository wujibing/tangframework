<?php
namespace Lib\Applications\Index\Controllers\Web;
use Lib\Applications\Index\Models\Tag;
use Tang\Exception\SystemException;
use Tang\Web\Controllers\ModelController;

/**
 * 使用ModelController
 * Class OrmDemo
 * @package Lib\Applications\Index\Controllers\Web
 */
class OrmDemo extends ModelController
{
    protected $modelName = 'Index.User';
    public function main()
    {
        //这样关联查询就用4条SQL语句，不如的话会有N*2+2条SQL语句
        $redis = new \Redis();

        $query = $this->model->with('tags','info');//因为with会返回一个新的model 所以要覆盖模型
        $userName = $this->request->get('userName');
        if($userName)
        {
            $query->where('userName','like',$userName.'%');
        }
        parent::main($query);
    }
    public function relationInsert($sucessUrl='',array $faildUrl=array())
    {
        $this->isAjax = true;
        $this->event->removeAllListeners('insertData');
        $this->event->addListener('insertData',function($data,$model,$marking)
        {
            //分割tags
            if(!isset($data['tag']) || !$data['tag'])
            {
                throw new SystemException('请输入tag标签');
            }
            $tags = explode(',',$data['tag']);
            $data['tags'] = Tag::makeTagsArray($tags);//构造关联插入数据数组
            $model->withDml('tags','info')->insert($data,$marking);
        });//因为要关联插入，所有要修改下事件
        parent::insert($sucessUrl,$faildUrl);
    }
}