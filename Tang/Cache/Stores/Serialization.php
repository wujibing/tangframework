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
namespace Tang\Cache\Stores;
/**
 * serialization序列号缓存
 * Class Serialization
 * @package Tang\Cache\Stores
 */
class Serialization extends FileStore
{

    /**
     * (non-PHPdoc)
     * @see IStore::set()
     */
	public function set($key,$value,$expire = 0)
	{
		$expire = $this->getExpire($expire);
		$this->write($key,serialize(compact('value','expire')));
	}

    /**
     * (non-PHPdoc)
     * @see FileStore::getType()
     */
	public function getType()
	{
		return 'Serialization';
	}

    /**
     * (non-PHPdoc)
     * @see FileStore::serializeHandler()
     */
	protected function serializeHandler($content)
	{
		$data = unserialize($content);
		return $data;
	}
}