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
namespace Tang\Web\Ip;
use Tang\Services\ServiceProvider;

/**
 * ClientIp服务
 * Class ClientIpService
 * @package Tang\Web\Ip
 */
class ClientIpService extends ServiceProvider
{
    /**
     * @return \Tang\Web\Ip\IClientIp
     */
    public static function getService()
    {
        return parent::getService();
    }
    protected static function register()
    {
        return static::initObject('clientIp','\Tang\Web\Ip\IClientIp');
    }
}