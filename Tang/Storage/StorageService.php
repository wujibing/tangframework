<?php
// +-----------------------------------------------------------------------------------
// | TangFrameWork 致力于WEB快速解决方案
// +-----------------------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.tangframework.com All rights reserved.
// +-----------------------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +-----------------------------------------------------------------------------------
// | HomePage ( http://www.tangframework.com/ )
// +-----------------------------------------------------------------------------------
// | Author: wujibing<283109896@qq.com>
// +-----------------------------------------------------------------------------------
// | Version: 1.0
// +-----------------------------------------------------------------------------------
namespace Tang\Storage;
use Tang\Services\ServiceProvider;

/**
 * 存储服务
 * Class StorageService
 * @package Tang\Storage
 */
class StorageService extends ServiceProvider
{
    /**
     * @return \Tang\Storage\IStorageManager
     */
    public static function getService()
    {
        return parent::getService();
    }
    protected static function register()
    {
        $instance =  parent::initObject('storage','\Tang\Storage\IStorageManager');
        //默认采用local存储
        $config = static::$config->replaceGet('storage.*',array('defaultDriver'=>'local'));
        $instance->setConfig($config);
        return $instance;
    }
}