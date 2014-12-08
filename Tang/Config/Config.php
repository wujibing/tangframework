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
namespace Tang\Config;
use Tang\Cache\Stores\IStore;
use Tang\Exception\SystemException;
use Tang\Log\LogService;
use Tang\TangApplication;

/**
 * 修改版array_replace_recursive
 * @param array $array1
 * @param array $array2
 * @return array
 */
function arrayReplaceRecursive(array $array1,array $array2)
{
    $array = array_replace_recursive($array1,$array2);
    foreach($array as $key => $value)
    {
        if(isset($array1[$key]))
        {
            if(is_array($array1[$key]) && is_array($value))
            {
                if($array1[$key] == $array2[$key])
                {

                } else if(is_array($array2))
                {
                    $array[$key] = arrayReplaceRecursive($array1[$key],$array2[$key]);
                } else
                {
                    $array[$key] = $array1[$key];
                }
            } else if(!$value)
            {
                $array[$key] = $array1[$key];
            }
        }
    }
    return $array;
}

if(!function_exists('ftok'))
{
	function ftok($pathname, $proj_id)
	{
		$st = @stat($pathname);
		if (!$st) {
			return -1;
		}

		$key = sprintf("%u", (($st['ino'] & 0xffff) | (($st['dev'] & 0xff) << 16) | (($proj_id & 0xff) << 24)));
		return $key;
	}
}

/**
 * 配置类
 * Class Config
 * @package Tang\Config
 */
class Config implements IConfig
{
    /**
     * 配置数据
     * @var array
     */
    private $_data = array();
	private $_replaceData =array(
			  		'runtimeFile' => '~RunTime.php',
			  		'debug' => 'true',
			  		'timezone' => 'Asia/Chongqing',
			  		'lang' => 'zh_cn',
			  		'charset' => 'UTF-8',
			  		'dataDirctory' => 'Data',
			  		'configDirctory' => 'Config',
			  		'moduleName' => 'module',
			  		'controllerName' => 'controller',
			  		'actionName' => 'action',
			  		'defaultModule' => 'index',
			  		'defaultController' => 'index',
			  		'defaultAction' => 'main',
                    '404Page'=>'404.html', //404页面
                    'messagePage'=>'message.html',//消息提示页面
                    'exceptionPage'=>'exception.htm',//异常页面
	);

	/**
	* 获取$key的配置内容，如果不存在的话则返回$defautValue
	* @param string $key
	* @param string $defautValue
	* @return string
	*/
	public function get($key,$defautValue='')
	{
		$name = '';
		$this->getConfigNameAndValueName($key,$name);
		if(!isset($this->_data[$name]))
		{
			$this->load($name);
		}
		if($key == '*')
		{
			return $this->_data[$name];
		} else if (isset($this->_data[$name][$key]))
		{
			return $this->_data[$name][$key];
		} else 
		{
			return $defautValue;
		}
	}

    /**
     * 根据$key获取配置内容，并且根据$replaceData进行替换
     * @param $key
     * @param array $replaceData
     * @return array|string
     */
    public function replaceGet($key,array $replaceData)
	{
		$config = $this->get($key);
		if(!is_array($config))
		{
			return $replaceData;
		}
		return arrayReplaceRecursive($replaceData,$config);
	}

    /**
     * 设置一个配置文件值
     * @param string $key
     * @param mixed $value
     */
    public function set($key,$value)
	{
		$name = '';
		$this->getConfigNameAndValueName($key,$name);
		if($key == '*')
		{
			$this->_data[$name] = $value;
		} else
		{
			$this->_data[$name][$key] = $value;
		}
	}

    /**
     * 保存所有配置
     */
    public function saveAll()
	{
		foreach ($this->_data as $name => $value)
		{
			$this->save($name);
		}
	}

    /**
     * 保存一个配置文件，如果不存在数据的话。$isCreate为true的时候则创建一个配置文件
     * @param string $name
     * @param bool $isCreate
     * @throws \Tang\Exception\SystemException
     */
    public function save($name,$isCreate=false)
	{
		$filePath = $this->getConfigPath($name);
		if(!isset($this->_data[$name]) && !file_exists($filePath))
		{
			if($isCreate)
			{
				file_put_contents($filePath, json_encode(array()));
			} else 
			{
				throw new SystemException('Save the configuration file contains no data!',null,10000,LogService::EMERG);
			}
		} else 
		{
			$content = json_encode($this->_data[$name]);
			$ret = '';
			$pos = 0;
			$length = strlen($content) -1;
			$indent = '    ';
			$newline = "\n";
			$prevchar = '';
			$outofquotes = true;
			
			for($i=0; $i<=$length; $i++)
			{
				$char = $content[$i];
				if($char=='"' && $prevchar!='\\')
				{
					$outofquotes = !$outofquotes;
				}elseif(($char=='}' || $char==']') && $outofquotes)
				{
					$ret .= $newline;
					$pos --;
					for($j=0; $j<$pos; $j++)
					{
						$ret .= $indent;
					}
				} else if ($i > 0 && ($char=='{' || $char=='['))
				{
					$ret .= $newline;
					for($j=0; $j<$pos; $j++)
					{
						$ret .= $indent;
					}
				}
				$ret .= $char;
				if(($char==',' || $char=='{' || $char=='[') && $outofquotes){
					$ret .= $newline;
					if($char=='{' || $char=='['){
						$pos ++;
					}
					for($j=0; $j<$pos; $j++){
						$ret .= $indent;
					}
				}
				$prevchar = $char;
			}
			file_put_contents($filePath, $ret);
		}
	}

	/**
	 * 创建一个配置文件
	 * @param string $name
	 */
	public function create($name)
	{
		return $this->save($name,true);
	}

    /**
     * 加载
     * @param $name
     * @throws \Tang\Exception\SystemException
     */
    protected function load($name)
	{
		$filePath = $this->getConfigPath($name);
		if(!$filePath || !file_exists($filePath))
		{
			throw new SystemException('Configuration file [%s] does not exist!',array($filePath),10001,LogService::EMERG);
		}
		$content = file_get_contents($filePath);
		$regex = '%((?:(?:\".*?\"|\'.*?\')*?[^\'\"]*?)*?)/{2,}.*?$%m';
		if(preg_match($regex, $content))
		{
			$content = preg_replace($regex,'\1',$content);
		}
		$jsonData = json_decode($content,true);
        $isApplicationConfigFile = $name == 'application';
		if(!$jsonData && !is_array($jsonData))
		{
            if($isApplicationConfigFile)
            {
                $this->_data[$name] = $this->_replaceData;
            }
            throw new SystemException('Configuration file [%s] JSON decoding failure!Error code:%d',array($filePath,json_last_error()),10002,LogService::EMERG);
		} else if($isApplicationConfigFile)
        {
            $this->_data[$name] = arrayReplaceRecursive($this->_replaceData,$jsonData);
        }
		$this->_data[$name] = $jsonData;
	}

    /**
     * 获取配置文件路径
     * @param $name
     * @return string
     */
    protected function getConfigPath($name)
	{
		return sprintf('%sLib%sConfig%s%s.conf',TangApplication::getApplicationPath(),DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR,ucfirst($name));
	}

	protected function getConfigNameAndValueName(&$key,&$name)
	{
		$index = strpos($key,'.');
		if($index)
		{
			$name = substr($key,0,$index);
			$key = substr($key, $index+1);
		} else
		{
			$name = 'application';
		}
	}
	private function __clone()
	{
	}
}