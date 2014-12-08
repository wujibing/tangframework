<?php
namespace Lib\Applications\Index\Controllers\Web;
use Tang\Crypt\CryptService;
use Tang\Web\Controllers\WebController;

class Crypt extends WebController
{
    public function main()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Crypt\Drivers\ICryptDriver'));
        $this->display();
    }

    public function demo()
    {
        try
        {
            $driver = CryptService::getService()->driver($_POST['driver']);
        } catch(\Exception $e)
        {
            $this->message($e->getMessage(),$e->getCode());
        }
        $content = $this->request->post('content','');
        $encodeContent = $driver->encode($content);
        $response = $this->request->getResponse();
        $response->writeLine('加密内容：'.$content);
        $response->writeLine('加密结果:'.$encodeContent);
        $response->writeLine('解密结果:'.$driver->decode($encodeContent));
    }
}