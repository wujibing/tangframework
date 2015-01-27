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
namespace Tang\Amqp;
use Tang\Exception\IsNotCallableException;

class Queue
{
    /**
     * AMQPQueue对象
     * @var AMQPQueue
     */
    protected $queue;
    public function __construct(Exchannel $exchannel,$name,$config)
    {
        $this->queue = new \AMQPQueue($exchannel->getChannel());
        $this->queue->setName($name);
        $config = array_replace_recursive(['durable'=>false,'passive'=>false,'autoDelete'=>false,'internal'=>false,'noWait'=>false,'routingKey'=>'','arguments'=>[]],$config);
        $flags = 0;
        if($config['durable'])
        {
            $flags = $flags | AMQP_DURABLE;
        }
        if($config['passive'])
        {
            $flags = $flags | AMQP_PASSIVE;
        }
        if($config['autoDelete'])
        {
            $flags = $flags | AMQP_AUTODELETE;
        }
        if($config['internal'])
        {
            $flags = $flags | AMQP_INTERNAL;
        }
        if($config['noWait'])
        {
            $flags = $flags | AMQP_NOWAIT;
        }
        $this->queue->setFlags($flags);
        if(isset($config['arguments']) && is_array($config['arguments']))
        {
            $this->queue->setArguments($config['arguments']);
        }
        $this->queue->declare();
        $this->queue->bind($exchannel->getName(),$config['routingKey']);
    }
    public function get($callback,$isAutoAck = false)
    {
        if(!is_callable($callback))
        {
            throw new IsNotCallableException();
        }
        $envelope = $this->queue->get($isAutoAck);
        $callback($envelope,$this);
    }
    public function consume($callback,$isAutoAck = false)
    {
        if(!is_callable($callback))
        {
            throw new IsNotCallableException();
        }
        $this->queue->consume($callback,$isAutoAck);
    }

    /**
     * 确认已处理消息，当$multiple为true的时候，发送
     * @param $envelope
     * @param bool $multiple
     * @return mixed
     */
    public function ack($envelope,$multiple = false)
    {
        return $this->queue->ack($envelope->getDeliveryTag(),$multiple);
    }

    /**
     * 拒绝消息 当$requeue为true的时候，则发送到下一个消费者。否则删除消息
     * @param $envelope
     * @param bool $requeue
     * @return mixed
     */
    public function nack($envelope,$requeue = false)
    {
        return $this->queue->ack($envelope->getDeliveryTag(),$requeue);
    }

    /**
     * 拒绝接受消息 $requeue为true的话则会将消息发送到下一个消费者。否则删除消息
     * @param $envelope
     * @param bool $requeue
     * @return mixed
     */
    public function reject($envelope,$requeue = false)
    {
        return $this->queue->reject($envelope->getDeliveryTag(),$requeue);
    }

    /**
     * 清除所有暂存在Queue里面的消息
     * @return mixed
     */
    public function purge()
    {
        return $this->queue->purge();
    }
    public function __call($method,$arguments)
    {
        call_user_func([$this->queue,$method],$arguments);
    }
}