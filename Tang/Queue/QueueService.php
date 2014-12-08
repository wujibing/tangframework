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
namespace Tang\Queue;
use Tang\Log\LogService;
use Tang\Services\ServiceProvider;

/**
 * 消息队列服务
 * Class QueueService
 * @package Tang\Queue
 */
class QueueService extends ServiceProvider
{
    /**
     * @return \Tang\Queue\IQueue
     */
    public static function getService()
    {
        return parent::getService();
    }
    protected static function register()
    {
        $instance =  parent::initObject('queque','\Tang\Queue\IQueue');
        //默认采用activemq存储
        $config = static::$config->get('queque.*');
        $instance->setConfig($config);
        $instance->setLogger(LogService::getService()->driver());
        return $instance;
    }
}