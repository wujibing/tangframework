<?php
namespace Lib\Applications\Index\Controllers\Web;
use Tang\Cache\CacheService;
use Tang\Web\Controllers\WebController;

class Cache extends WebController
{
    public function main()
    {
        $this->display();//直接输出
    }
    public function set()
    {

        $cache = CacheService::getService();//也可以$cache = CacheService::getService()->driver('驱动名')获取其他驱动实例;
        $value = $cache->get('name',function($instance,$key)
        {
            $content = 'cache';
            $instance->set($key,$content,86400);
            return $content;
        });
        $this->message('已设置name缓存值为'.$value);
    }
    public function get()
    {
        $value = CacheService::getService()->get('name');
        echo '当前count值:'.$value;
    }
    public function delete()
    {
        CacheService::getService()->delete('name');
        $this->message('删除成功');
    }
    public function clean()
    {
        CacheService::getService()->clean();
        $this->message('清除成功');
    }
}