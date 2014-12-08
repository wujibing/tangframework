<?php
namespace Lib\Applications\Index\Controllers\Web;


use Tang\Web\Controllers\WebController;

class Index extends WebController
{
    public function main()
    {
        $this->isAjax = true;
        $this->view->assgin('name','德玛西亚！');
        $this->view->assgin('user',array('userId'=>1,'userName'=>'Hi.'));
        $this->display();
    }
    public function get()
    {
        $this->display();
    }
}