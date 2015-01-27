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
use Tang\Amqp\Interfaces\IAmqp;
use Tang\Exception\SystemException;

class Exchannel
{
    /**
     * 配置
     * @var array
     */
    protected $config = [];
    /**
     * @var IAmqp
     */
    protected $amqp;
    /**
     * 名称
     * @var string
     */
    protected $name;
    protected $exchannel;
    /**
     * 队列数组
     * @var array
     */
    protected $queques = [];
    protected $channel;
    public function __construct(IAmqp $amqp,$name,$config)
    {
        $this->amqp = $amqp;
        $this->name = $name;
        $this->channel = $this->amqp->createChannel();
        $this->exchannel = new \AMQPExchange($this->channel);
        $this->exchannel->setName($name);
        $config = array_replace_recursive(['type'=>'direct','durable'=>false,'passive'=>false,'autoDelete'=>false,'internal'=>false,'noWait'=>false,'queues'=>[],'arguments'=>[]],$config);
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
        $this->exchannel->setFlags($flags);
        if(!in_array($config['type'],['direct','fanout','topic']))
        {
            $config['type'] = 'direct';
        }
        $this->exchannel->setType($config['type']);
        if(isset($config['arguments']) && is_array($config['arguments']))
        {
            $this->exchannel->setArguments($config['arguments']);
        }
        $this->exchannel->declare();
        $this->config = $config;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getChannel()
    {
        return $this->channel;
    }
    public function publish($message,$routingkey='',$flags = false)
    {
        return $this->exchannel->publish($message,$routingkey,$flags);
    }
    public function getQueue($name)
    {
        if(!isset($this->queques[$name]))
        {
            $this->createQueueByConfig($name);
        }
        return $this->queques[$name];
    }
    public function existsQuery($name)
    {
        return isset($this->queques[$name]);
    }
    public function createQuery($name,$routingKey='',$durable=false,$passive=false,$autoDelete=false,$internal=false,$noWait=false,$arguments=[])
    {
        if($this->existsQuery($name))
        {
            throw new SystemException('%s queue is exisis',[$name],1865);
        }
        return $this->ceateQueryByArray($name,compact('routingKey','durable','passive','autoDelete','internal','noWait','arguments'));
    }
    protected function createQueueByConfig($name)
    {
        if(!isset($this->config['queues'][$name]))
        {
            throw new SystemException('%s queue config is not exisis',[$name],1866);
        }
        $this->ceateQueryByArray($name,$this->config['queues'][$name]);
    }
    protected function ceateQueryByArray($name,$config)
    {
        $this->queques[$name] = new Queue($this,$name,$config);
        return $this->queques[$name];
    }
}