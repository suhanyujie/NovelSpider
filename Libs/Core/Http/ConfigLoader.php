<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2019-03-10
 * Time: 13:19
 */

namespace Libs\Core\Http;


class ConfigLoader
{
    public static $ins;

    protected $cacheData = [];

    protected $fileCache = [];

    private function __construct()
    {

    }

    /**
     * @desc
     */
    public static function getInstance()
    {
        if (!is_null(self::$ins)) return self::$ins;
        self::$ins = new self();
        return self::$ins;
    }

    /**
     * @desc
     */
    public function getConfigFiles()
    {

    }


    /**
     * @desc
     */
    public static function config($key='',$default='')
    {
        /**
         * @var ConfigLoader
         */
        $ins = self::getInstance();
        return $ins->parseKey($key);
    }

    /**
     * @desc
     */
    public function parseKey($key='')
    {
        if (!$key)return '';
        if (isset($this->cacheData[$key])) {
            return $this->cacheData[$key];
        }
        $configBasePath = ROOT."/Config";
        $keyArr = explode('@', $key);
        if (count($keyArr)<2)return '';
        $filePath = $configBasePath.'/'.str_replace('.','/', $keyArr[0]).'.php';
        if (!file_exists($filePath)) {
            throw new \Exception("config file {$filePath} not exist");
        }
        if (isset($this->fileCache[$filePath])) {
            $options = $this->fileCache[$filePath];
        } else {
            $options = require_once $filePath;
            $this->fileCache[$filePath] = $options;
        }
        $tmpVal = $options;
        $keyStep = explode('.', $keyArr[1]);
        foreach ($keyStep as $k=>$v) {
            if (!isset($tmpVal[$v])) {
                throw new \Exception("config options {$key} not exist!",-3);
            }
            $tmpVal = $tmpVal[$v];
        }
        $this->cacheData[$key] = $tmpVal;

        return $tmpVal;
    }
}
