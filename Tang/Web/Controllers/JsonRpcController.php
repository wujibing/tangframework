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
namespace Tang\Web\Controllers;
use Tang\ThirdParty\ThirdPartyService;

/**
 * JsonRpc控制器
 * Class JsonRpcController
 * @package Tang\Web\Controllers
 */
class JsonRpcController extends Controller
{
    /**
     * @see Controller::invoke
     */
    protected function invoke($action)
    {
        ThirdPartyService::import('JsonRpc.jsonRPCServer');
        \jsonRPCServer::handle($this);
    }
}