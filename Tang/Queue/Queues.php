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
use Tang\Exception\SystemException;
use Stomp as CStomp;
use Tang\Log\ILoger;

class Queues implements IQueue
{
    protected $config = array();
    protected $default = '';
    private $stomps = array();
    /**
     * @var ILoger
     */
    private $loger;

    /**
     * 设置日志
     * @param ILoger $loger
     */
    public function setLogger(ILoger $loger)
    {
        $this->loger = $loger;
    }

    /**
     * 设置配置文件
     * @param array $config
     * @return mixed|void
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        $keys = array_keys($config);
        $this->default = $keys[0];
    }

    /**
     * @param string $name
     * @return Stomp
     * @throws SystemException
     */
    public function get($name = '')
    {
        if(!$name)
        {
            $name = $this->default;
        }
        if(!isset($this->stomps[$name]))
        {
            $this->stomps[$name] = $this->create($name);
        }
        return $this->stomps[$name];
    }

    /**
     * @param $name
     * @return Stomp
     * @throws SystemException
     */
    protected function create($name)
    {
        $list = $this->config[$name];
        $length = count($list);
        $stomp = null;
        if($length == 0)
        {
            throw new SystemException('The %s does not contain any server information',array($name),7052,'EMERG');
        } else if($length == 1)
        {
            $stomp = $this->createStomp($list[0]);
        } else
        {
            while(true)
            {
                if($length == 0)
                {
                    throw new SystemException('cannot connect stomp %s',array($name),7053,'EMERG');
                }
                $rand = mt_rand(0,$length);
                try
                {
                    $stomp = $this->createStomp($list[$rand]);
                    break;
                } catch(\Exception $e)
                {
                    unset($list[$rand]);
                    $length = count($list);
                    $this->loger->write($e->getMessage(),'EMERG');
                }
            }
        }
        print_r($stomp);exit;
        return new Stomp($stomp);
    }

    protected function createStomp($config)
    {
        $config = array_replace_recursive(array('brokerUri'=>'','username'=>'','password'=>''),$config);
        if(!$config['brokerUri'])
        {
            throw new SystemException('has not brokerUri');
        }
        try
        {
            $stomp = new CStomp($config['brokerUri'],$config['username'],$config['password']);
        } catch(\Exception $e)
        {
            throw new SystemException('cannot connect stomp:%s;error:%s',array($config['brokerUri'],$e->getMessage()),7054,'EMERG');
        }
    }
}