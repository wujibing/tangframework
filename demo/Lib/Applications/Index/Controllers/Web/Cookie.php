<?php
namespace Lib\Applications\Index\Controllers\Web;
use Tang\Web\Controllers\WebController;

class Cookie extends WebController
{
    public function main()
    {
        $this->display();//直接输出
    }
    public function set()
    {
        $cookie = $this->request->cookie();
        $cookieValue = $cookie->get('count',0);
        $cookieValue+=1;
        $cookie->set('count',$cookieValue);
        $this->message('已设置count值为'.$cookieValue);
    }
    public function get()
    {
        $count = $this->request->cookie()->get('count',0);
        echo '当前count值:'.$count;
    }
    public function delete()
    {
        $this->request->cookie()->delete('count');
        $this->message('删除成功');
    }
    public function clean()
    {
        $this->request->cookie()->clean();
        $this->message('清除成功');
    }
}