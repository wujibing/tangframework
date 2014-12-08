<?php
/**
 * 我们要建立namespace方便框架定位加载我们的文件
 * 如果不写入namespace的话，需要开发者自己include或者编写自己的spl_autoload_register
 */
namespace Lib\Service\OutPut;

/**
 * 输出接口
 * Interface IOutPut
 * @package Lib\Service\OutPut
 */
interface IOutPut
{
    /**
     * 输出$string
     * @param $string
     * @return mixed
     */
    public function write($string);

    /**
     * 输出$array数组
     * @param array $array
     * @return mixed
     */
    public function writeArray(array $array);
}