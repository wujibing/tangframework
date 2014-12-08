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
namespace Tang\Routing;
/**
 * Web路由
 * Class WebRouter
 * @package Tang\Routing
 */
class WebRouter extends BaseRouter implements IRouter
{
    /**
     * 配置
     * @var array
     */
    protected $config = array();
    /**
     * 分隔符
     * @var string
     */
    protected $delimiter;
    /**
     * 脚本名称
     * @var string
     */
    protected $scripteName;
    /**
     * 是否域名绑定
     * @var bool
     */
    protected $domainBind = false;
    /**
     * 生成网址的缓存
     * @var array
     */
    protected static $urls =array();
    /**
     * 请求后缀
     * @var string
     */
    protected $extension = '';

    /**
     * 设置
     * @param array $config
     * @return mixed|void
     */
    public function setConfig(array $config)
	{
		$this->config = $config;
	}

    /**
     * 获取网页后缀
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * 解析路由
     * @return mixed|void
     */
    public function router()
	{
		$model = $this->config['model'];
		$this->scripteName = $_SERVER['SCRIPT_NAME'];
		if($model > 0)
		{
			$pathInfoParmName = $this->config['pathInfo']['parmName'];
            if(!isset($_SERVER['PATH_INFO']) || !$_SERVER['PATH_INFO'])
            {
                if(isset($_GET[$pathInfoParmName]))
                {
                    $_SERVER['PATH_INFO'] = $_GET[$pathInfoParmName];
                    unset($_GET[$pathInfoParmName]);
                } else
                {
                    $_SERVER['PATH_INFO'] = '';
                }
            }
			if($model == 3)
			{
				$this->scripteName = $_SERVER['SCRIPT_NAME'].'?'.$pathInfoParmName.'=';
			} else if($model == 2)
			{
				$this->scripteName =  dirname($_SERVER['SCRIPT_NAME']);
				if($this->scripteName == '/' || $this->scripteName == '\\')
					$this->scripteName =  '';
			}
			$this->routeByPathInfo();
		}
		$this->checkDefaultValue($this->config['moduleName'], ucfirst($this->config['defaultModule']), $this->moduleValue);
		$this->checkDefaultValue($this->config['controllerName'], ucfirst($this->config['defaultController']), $this->controllerValue);
		$this->checkDefaultValue($this->config['actionName'], $this->config['defaultAction'], $this->actionValue,false);
		if(!$this->domainBind)
		{
			$alias = $this->config['moduleAlias'];
			if(in_array($this->moduleValue, $alias))
			{
				echo 'Alias';exit;
			} else if(isset($alias[$this->moduleValue]) && $alias[$this->moduleValue])
			{
				$this->moduleValue = ucfirst($alias[$this->moduleValue]);
			}
		}
	}

    /**
     * 创建URL
     * @param string $uPath
     * @param string $args
     * @param bool $suffix
     * @return string
     */
    public function createUrl($uPath='/',$args='',$suffix=false,$CSXF =false)
	{
		//先取出域
		if(isset(static::$urls[$uPath]))
		{
			extract(static::$urls[$uPath],true);
		} else 
		{
			$index = strpos($uPath, '@');
			$host =  '';
			$url = $uPath;
			if($index > 0)
			{
				$host = substr($url, $index+1);
				$url = substr($url, 0,$index);
			}
			$info = parse_url($url);
			$url = trim($info['path'],'/');
			if(substr_count($url, '/') > 2)
			{
				$tmp = explode('/', $url);
				$url = implode('/', array_slice($tmp, 0,3));
			}
			$anchor = isset($info['fragment']) ? $info['fragment']:'';
			$rooDomain = '.'.implode('.', array_slice(explode('.',$_SERVER['HTTP_HOST']),strpos($this->config['subDomain']['suffix'],'.') ? -3 : -2));
			if($host)
			{
				$domain = $host.$rooDomain;
			}else if($this->config['subDomain']['support'])
			{
					// '子域名'=>array('模块[/控制器]');
					$hasSubDomain = false;
					foreach ($this->config['subDomain']['rules'] as $key => $rule)
					{
						$rule = is_array($rule)?$rule[0]:$rule;
						if(false === strpos($key,'*') && 0 === stripos($url,$rule))
						{
							$domain = $key.$rooDomain; // 生成对应子域名
							$url = substr_replace($url,'',0,strlen($rule)+ 1) ;
							$hasSubDomain = true;
							break;
						}
					}
					if(!$hasSubDomain)
					{
						$domain = $_SERVER['HTTP_HOST'];
					}
			}
			$path =  explode('/',$url);
			$moduleName = $this->config['moduleName'];
			$varNames = array($this->config['actionName'],$this->config['controllerName'],$moduleName);
			$vars = array();
			foreach ($varNames as $name)
			{
				if(!$path)
				{
					break;
				}
				$vars[$name] = array_pop($path);
			}
			$vars = array_reverse($vars);
			if(isset($vars[$moduleName]) && $vars[$moduleName])
			{
				$aliasModule = array_search(ucfirst($vars[$moduleName]), $this->config['moduleAlias']);
				if($aliasModule)
				{
					$vars[$moduleName] = lcfirst($aliasModule);
				}
			}
			static::$urls [$uPath] = array('vars' =>$vars,'url'=>$url,'domain'=>$domain,'anchor'=>$anchor);
			if(isset($info['query'])) // 解析地址里面参数
			{
				parse_str($info['query'],$params);
				static::$urls [$uPath]['params'] = $params;
			}
		}
		
		// 解析参数
		if(is_string($args) && $args)  // aaa=1&bbb=2 转换成数组
		{
			parse_str($args,$args);
		}elseif(!is_array($args))
		{
			$args = array();
		}
		if(isset($params))
		{
			$args = array_merge($params);
		}
        if($CSXF)
        {
            $CSXFInstance = $this->request->CSRF();
            $args[$CSXFInstance->getName()] = $CSXFInstance->getValue();
        }
		if($this->config['model'] == 0) // 普通模式URL转换
		{
			$url = $this->scripteName.'?';
			if($vars)
			{
				$url .= http_build_query($vars);
			}
			if(!empty($args)) 
			{
				$url  .=  '&'.http_build_query($args);
			}
		} else
		{
			$url  =  $this->scripteName.'/'.implode($this->delimiter, $vars);
			if(!empty($args))// 添加参数
			 { 
				foreach ($args as $var => $val)
				{
					if('' !== trim($val))   $url .= $this->delimiter . $var . $this->delimiter . urlencode($val);
				}
			}
			if($suffix && substr($url,-1) != '/')
            {
                if($this->extension)
                {
                    $url  .=  '.'.$this->extension;
                } else if($this->config['rewrite']['htmlSuffix'])
                {
                    $suffix = $this->config['rewrite']['htmlSuffix'];
                    $index = strpos($suffix, '|');
                    if($index)
                    {
                        $suffix = substr($suffix,0, $index);
                    }
                    $url  .=  '.'.ltrim($suffix,'.');
                }
			}
		}
		if($anchor)
		{
			$url  .= '#'.$anchor;
		}
		return  ($this->request->isSsl() ? 'https://':'http://').$domain.$url;
	}

    /**
     * 获取类型
     * @return string
     */
    public function getType()
	{
		return 'Web';
	}

    /**
     * PathInfo模式解析
     */
    protected function routeByPathInfo()
	{
		$this->delimiter = $this->config['delimiter'];
		$this->getSubDomain();
		$this->checkPathInfo();
		$rewriteRules = $this->config['rewrite']['rules'];
		$rules = $this->moduleValue && isset($rewriteRules[$this->moduleValue]) ? $rewriteRules[$this->moduleValue] : $rewriteRules['Public'];
		foreach ($rules as $key => $value)
		{
			$regx = '%^'.$key.'$%';
			if(preg_match($regx, $_SERVER['PATH_INFO']))
			{
				$_SERVER['PATH_INFO'] = preg_replace('%^'.$key.'%', $value, $_SERVER['PATH_INFO']);
				break;
			}
		}
		$_SERVER['PATH_INFO'] = trim($_SERVER['PATH_INFO'],'/');
		if(!$_SERVER['PATH_INFO'])
		{
			return ;
		} else
		{
            // 去除URL后缀
            $htmlSuffix = $this->config['rewrite']['htmlSuffix'];
            if($htmlSuffix)
            {
                $this->extension = strtolower(pathinfo($_SERVER['PATH_INFO'],PATHINFO_EXTENSION));
                $htmlSuffix = trim($htmlSuffix,'.');
                if(preg_match('/\.('.$htmlSuffix.')$/i',$_SERVER['PATH_INFO']))
                {
                    $_SERVER['PATH_INFO'] = preg_replace('/\.('.$htmlSuffix.')$/i', '',$_SERVER['PATH_INFO']);
                } else if($this->extension)
                {
                    $this->request->getResponse()->httpStatus(404);
                    exit;
                }

            }

			$index = strpos($_SERVER['PATH_INFO'], '?');
			$parms  =  array();
			if($index !== false)
			{
				$parmsString = substr($_SERVER['PATH_INFO'], $index+1);
				$_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'], 0,$index);
				parse_str($parmsString,$parms);
				$_GET = array_merge($parms,$_GET);
				if($index === 0)
				{
					return ;
				} else 
				{
					$parms = array();
				}
			}

			$paths  = explode($this->delimiter,trim($_SERVER['PATH_INFO'],$this->delimiter));
			foreach (array('setModuleValue','setControllerValue','setActionValue') as $method)
			{
				if($paths && $this->$method($paths[0]))
				{
					array_shift($paths); 
				}
			}
			if($paths)
			{
				// 解析剩余的URL参数
				$paramsBindType = $this->config['rewrite']['paramsBindType'];
				if($paramsBindType && 1 == $paramsBindType)
				{
					$parms = $paths;
				}else
				{
					preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$parms){$parms[$match[1]]=strip_tags($match[2]);}, implode('/',$paths));
				}
			}
			$_GET = array_merge($parms,$_GET);
            $_REQUEST = array_merge($_REQUEST,$_GET);
		}
	}

    /**
     * @param $name
     * @param $defaultValue
     * @param $value
     * @param bool $isUcfirst
     */
    protected function checkDefaultValue($name,$defaultValue,&$value,$isUcfirst = true)
	{
		if($value)
		{
			return;
		}
		if(isset($_GET[$name]) && $_GET[$name])
		{
			$value = $isUcfirst ? ucfirst($_GET[$name]):$_GET[$name];
		} else 
		{
			$value = $defaultValue;
		}
	}

    /**
     * 设置模块值
     * @param $value
     * @return bool
     */
    protected function setModuleValue($value)
	{
		if(!$this->moduleValue && $this->checkVarname($value))
		{
			$this->moduleValue = ucfirst($value);
			return true;
		} else 
		{
			return false;
		}
	}

    /**
     * 设置控制器值
     * @param $value
     * @return bool
     */
	protected function setControllerValue($value)
	{
		if($this->moduleValue && !$this->controllerValue && $this->checkVarname($value))
		{
			$this->controllerValue = ucfirst($value);
			return true;
		} else
		{
			return false;
		}
	}

    /**
     * 设置动作值
     * @param $value
     * @return bool
     */
	protected function setActionValue($value)
	{
		if($this->moduleValue && $this->controllerValue && !$this->actionValue && $this->checkVarname($value))
		{
			$this->actionValue = $value;
			return true;
		} else
		{
			return false;
		}
	}

    /**
     * 检查变量名
     * @param $value
     * @return bool
     */
    public function checkVarname($value)
	{
		return $value && !is_numeric($value) && preg_match('%^[A-Za-z_][A-Za-z0-9|_]+$%', $value);
	}

    /**
     * 检查PathInfo
     */
    protected function checkPathInfo()
	{
		if(!isset($_SERVER['PATH_INFO']) && is_array($this->config['pathInfo']['otherPathInfo']))
		{
			foreach ($this->config['pathInfo']['otherPathInfo'] as $name)
			{
				if(!empty($_SERVER[$name]))
				{
					$_SERVER['PATH_INFO'] = (0 === strpos($_SERVER[$name],$_SERVER['SCRIPT_NAME']))?substr($_SERVER[$name], strlen($_SERVER['SCRIPT_NAME']))   :  $_SERVER[$name];
					break;
				}
			}
		}
	}

	/**
	 * 获取子域支持
	 */
	protected function getSubDomain()
	{
		$subDomainConfig = $this->config['subDomain'];
		if(!$subDomainConfig['support'] || !is_array($subDomainConfig['rules']) || !$subDomainConfig['rules'])
		{
			return;
		}
		$rules = $subDomainConfig['rules'];
		$domainParms = '';
		$index = strpos($subDomainConfig['suffix'], '.') ? -3 : -2;
		$domain = array_slice(explode('.', $_SERVER['HTTP_HOST']), 0, $index);
		if(!empty($domain))
		{
			$subDomain = implode('.', $domain);
			$domain2 = $domain3 = '';
			$domain2   = array_pop($domain); // 二级域名
			$domain && $domain3 = array_pop($domain);
			if(isset($rules[$subDomain])) //判断该域名是否有规则
			{
				$rule = $rules[$subDomain];
			}elseif(isset($rules['*.' . $domain2]) && !empty($domain3))// 泛三级域名
			{
				$rule = $rules['*.' . $domain2];
				$domainParms = $domain3;
			}elseif(isset($rules['*']) && !empty($domain2) && 'www' != $domain2 )// 泛二级域名
			{
				$rule = $rules['*'];
				$domainParms = $domain2;
			}
		} else 
		{
			return;
		}
		if(empty($rule))
		{
			return;
		}
		if(is_array($rule))
		{
			isset($rule[1]) && $vars = $rule[1];
			$rule = $rule[0];
		}
		$array =   explode('/',$rule);
		if($this->setModuleValue($array[0]))
		{
			$this->domainBind = true;
			array_shift($array);
		}
		if($array && $this->setControllerValue($array[0]))
		{
			array_shift($array);
		}
		if(isset($vars))  // 传入参数
		{
			parse_str($vars,$parms);
			if($domainParms)
			{
				$pos = array_search('[domain]', $parms);
				if(false !== $pos)
				{
					$parms[$pos] = $domainParms;// 泛域名作为参数
				}
			}
			$_GET = array_merge($_GET,$parms);
		}
	}
}