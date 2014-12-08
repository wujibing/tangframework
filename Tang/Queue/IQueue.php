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
use Tang\Interfaces\ISetConfig;
use Tang\Log\ILoger;
/**
 * 消息队列接口
 * Interface IQueue
 * @package Tang\Queue
 */
interface IQueue extends ISetConfig
{
    /**
     * 设置日志
     * @param ILoger $loger
     * @return mixed
     */
    public function setLogger(ILoger $loger);

    /**
     * 根据$name获取队列对象
     * @param string $name
     * @return Stomp
     */
    public function get($name = '');
}