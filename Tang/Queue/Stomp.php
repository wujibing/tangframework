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
use Stomp as CStomp;
use Tang\Exception\IsNotCallableException;

/**
 * 详细操作查阅http://php.net/manual/zh/book.stomp.php
 * 这里只是增强stomp操作易用性
 * @package Tang\Queue
 */
class Stomp
{
    /**
     * Stomp实例
     * @var CStomp
     */
    protected $instance;
    protected $handlers = array();
    public function __construct(CStomp $instance)
    {
        $this->instance = $instance;
    }

    /**
     * 事物处理
     * @param $name 事物名称
     * @param $handler 事物处理程序 如果抛出异常则回滚 否则提交事物
     */
    public function transaction($name,$handler)
    {
        if(!is_callable($handler))
        {
            throw new IsNotCallableException();
        }
        $this->instance->begin($name);
        $transaction = new StompTransaction($name,$this);
        try
        {
            call_user_func($handler,$transaction);
            $this->instance->commit($name);
        } catch(\Exception $e)
        {
            $this->instance->abort($name);
            throw $e;
        }
    }

    /**
     * 注册$destination处理程序
     * @param $destinations
     * @param $handler
     */
    public function regisetrHandler($destination,$handler)
    {
        if(!is_callable($handler))
        {
            throw new IsNotCallableException();
        }
        $this->handlers[$destination] = $handler;
    }

    /**
     * 客户端开始运行并处理数据
     * 当处理程序返回true和$autoAsk为true的时候才产生自动应答
     * 客户端也可通过传递的Stomp对象进行应答
     * @param bool $autoAsk 是否自动应答
     */
    public function run($autoAsk = false)
    {
        while(true)
        {
            if($this->instance->hasFrame())
            {
                $frame = $this->instance->readFrame();
                $destination = $frame->headers['destination'];
                if($this->handlers[$destination] && is_callable($this->handlers[$destination]))
                {
                    if($autoAsk && call_user_func($this->handlers[$destination],$this,$frame))
                    {
                        $this->instance->ask($frame);
                    }
                }
            }
        }
    }
    public function __call($method,$args)
    {
        call_user_func_array(array($this->instance,$method),$args);
    }
}

class StompTransaction
{
    protected $name;
    protected $stomp;
    public function __construct($name,Stomp $stomp)
    {
        $this->name = $name;
        $this->stomp = $stomp;
    }
    public function send($destination,$message)
    {
        $this->stomp->sned($destination,$message,array('transaction' => $this->name));
    }
}