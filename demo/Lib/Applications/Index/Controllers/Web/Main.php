<?php
namespace Lib\Applications\Index\Controllers\Web;
use Lib\Applications\Admin\Model\Applications;
use Lib\Applications\Admin\Model\User;
use Tang\Web\Controllers\WebController;

class Main extends WebController
{
	public function firstLaytou()
	{
		$this->isAjax = true;
		$user = new User();
		$applicationModel = new Applications();
		$this->view->assgin('applications',$applicationModel->with('tags')->get());
		$this->display();
	}
}