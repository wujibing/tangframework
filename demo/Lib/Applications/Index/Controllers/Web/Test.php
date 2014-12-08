<?php
namespace Lib\Applications\Index\Controllers\Web;

use Tang\Database\Sql\Model;
use Tang\Web\Controllers\WebController;

class Test extends WebController
{
    public function t()
    {
        $test = Model::loadModel('index.test')->insert(array('name'=>'哈哈','password'=>'123123213','nickName'=>'dsds','registerTime'=>'2014-12-21 12:32:32','registerIp'=>'123'));
        echo $test->uid.'=='.$test->name;
    }
}