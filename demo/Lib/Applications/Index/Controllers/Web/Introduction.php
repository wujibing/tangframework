<?php
namespace Lib\Applications\Index\Controllers\Web;
use Lib\Service\OutPutService;
use Tang\Services\ConfigService;
use Tang\Services\FileService;
use Tang\Web\Browser;
use Tang\Web\Ip\ClientIpService;

class Introduction extends \Tang\Web\Controllers\WebController
{
    public function service()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Services\ServiceProvider'));
        $this->display();
    }
    public function serviceDemo()
    {
        $outPuter = OutPutService::getService();
        $outPuter->write('我要输出数组了：');
        $outPuter->writeArray(array('Tang','Framework!'));
    }
    public function manager()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Manager\IManager'));
        $this->view->assgin('cacheServiceSourceCode',getSourceCode('Cache\CacheService'));
        $this->view->assgin('cacheManagerSourceCode',getSourceCode('Cache\CacheManager'));
        $this->display();
    }
    public function I18n()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('I18n\II18n'));
        $this->display();
    }
    public function file()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('IO\Interfaces\IFile'));
        $this->display();
    }
    public function directory()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('IO\Interfaces\IDirectory'));
        $this->display();
    }
    public function log()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Log\ILogManager'));
        $this->view->assgin('interfaceSourceCode2',getSourceCode('Log\ILoger'));
        $this->display();
    }
    public function thirdParty()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('ThirdParty\IThirdParty'));
        $this->display();
    }
    public function token()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Token\IToken'));
        $this->display();
    }
    public function storage()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Storage\StorageManager'));
        $this->view->assgin('interfaceSourceCode2',getSourceCode('Storage\Drivers\IStorage'));
        $this->display();
    }
    public function pagination()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Pagination\IPaginator'));
        $this->display();
    }
    public function request()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Request\IRequest'));
        $this->display();
    }
    public function response()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Response\IResponse'));
        $this->display();
    }
    public function router()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Routing\IRouter'));
        $this->display();
    }
    public function browser()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Web\Browser\IBrowser'));
        $browser = Browser::getService();
        $this->view->assgin('browserName',$browser->getBrowserName());
        $this->view->assgin('browserVersion',$browser->getBrowserVersion());
        $this->view->assgin('browserPlatform',$browser->getPlatform());
        $this->view->assgin('browserLanguage',$browser->getLanguage());
        $this->view->assgin('browserUserAgent',$browser->getUserAgent());
        $this->view->assgin('browserSupportsActiveX',$browser->getSupportsActiveX());
        $this->display();
    }
    public function Ip()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Web\Ip\IClientIp'));
        $this->view->assgin('ip',ClientIpService::getService());
        $this->display();
    }
    public function view()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Web\View\IView'));
        $this->display();
    }
    public function template()
    {
        $this->view->assgin('interfaceSourceCode',getSourceCode('Web\View\Templates\ITemplate'));
        $this->display();
    }
    public function controller()
    {
        $this->display();
    }
    public function jsonRpcController()
    {
        $this->display();
    }
    public function cliController()
    {
        $this->display();
    }
    public function restController()
    {
        $this->display();
    }
    public function webController()
    {
        $this->display();
    }
    public function modelController()
    {
        $this->display();
    }
    public function config()
    {
        $this->view->assgin('configServiceSourceCode',getSourceCode('Services\ConfigService'));
        $this->view->assgin('interfaceSourceCode',getSourceCode('Config\IConfig'));
        $this->display();
    }
}