<?php
/**
 * 我们要建立namespace方便框架定位加载我们的文件
 * 如果不写入namespace的话，需要开发者自己include或者编写自己的spl_autoload_register
 */
namespace Lib\Service;
use Tang\Services\ServiceProvider;

/**
 * 服务对象需要继承ServiceProvider
 * Class OutPutService
 * @package Lib\Service
 */
class OutPutService extends ServiceProvider
{
    /**
     * 这里是因为可以给开发者提供代码提示，如果不需要可以不写该方法
     * 一定要写@ return 返回值才能代码提示 我们这里返回接口地址
     * @return \Lib\Service\OutPut\IOutPut
     */
    public static function getService()
    {
        return parent::getService();
    }

    /**
     * 我们来实现注册对象，每个服务都必须实现该方法
     */
    protected static function register()
    {
        //initObject方法的第一参数是名称，这里会从配置文件中根据名称找到类地址
        //第二个参数是接口地址，如果提供的对象没有实现该接口，会有异常
        return static::initObject('output', '\Lib\Service\OutPut\IOutPut');
    }
}