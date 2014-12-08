<?php
namespace Lib\Applications\Index\Controllers\Web;
use Tang\Web\Controllers\JsonRpcController;

class Rpc extends JsonRpcController
{
    public function run()
    {
        return "1";
    }
}