<?php
namespace Lib\Applications\Index\Controllers\Web;
use Tang\Web\Controllers\WebController;
use Tang\Web\Session\SessionService;

class Session extends WebController
{
    public function main()
    {
        $this->display();//直接输出
    }
    public function set()
    {
        $session = $this->request->session();
        $value = $session->get('count',0);
        $value+=1;
        $session->set('count',$value);
        $this->message('已设置count值为'.$value);
    }
    public function get()
    {
        $count = $this->request->session()->get('count',0);
        echo '当前count值:'.$count;
    }
    public function delete()
    {
        $this->request->session()->delete('count');
        $this->message('删除成功');
    }
    public function clean()
    {
        $this->request->session()->destroy();
        $this->message('清除成功');
    }
}