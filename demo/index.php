<?php
include __DIR__.'/../trunk/TangApplication.php';
use Tang\TangApplication;
function getSourceCode($classPath)
{
    $interfaceSourceCode = '';
    $filePath = \Tang\Services\ConfigService::getService()->get('frameworkDirectory').str_replace('\\', DIRECTORY_SEPARATOR,$classPath).'.php';
    \Tang\Services\FileService::getService()->read($filePath,$interfaceSourceCode);
    return  $interfaceSourceCode;
}
$application = new TangApplication(__DIR__);
$application->run();