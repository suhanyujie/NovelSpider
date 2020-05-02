<?php
/**
 * 命令行程序入口
 * @example `php Novel/Consoles/index.php novel:exportTxt`
 */
require __DIR__ . "/../../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use Novel\NovelSpider\Controller\Test;
use Novel\NovelSpider\Models\NovelMainModel;
use Novel\NovelSpider\Services\NovelCacheKeyConfigService;
use Novel\NovelSpider\Services\NovelService;
use Workerman\Worker;

// 解析配置文件
//定义全局常量
define('ROOT', realpath(__DIR__ . '/../../'));
$params = $argv;

// 遍历 Consoles 目录内的目录，找出对应的类
// 解析出对应的 signature
$classBox = [];
getConsoleClass();
// 通过 传入的 signature 识别出要执行的程序
$signature = $params[1] ?? '';
if (empty($signature)) throw new \Exception("请传入正确的脚本命令！", -1);
$obj = $classBox[$signature] ?? '';
if (empty($obj)) throw new \Exception("无法识别的命令行脚本！", -2);
$output = $obj->handle();
if (!empty($output)) {
    echo json_encode($output, 320);die;
}
die;

// 获取并解析命令行脚本
function getConsoleClass($dir = 'Novel/Consoles')
{
    global $classBox;
    $fileInfo = new FilesystemIterator($dir);
    foreach ($fileInfo as $item) {
        $fileName = $item->getFilename();
        if (in_array($fileName, ['index.php', 'CliInterface.php', 'Kernel.php', 'BaseConsole.php'])) {
            continue;
        }
        if ($item->isFile()) {
            $className = $partName = pathinfo($fileName, PATHINFO_FILENAME);
            $classPath = $dir.'/'.$fileName;
            if (!class_exists($className) && file_exists($classPath)) {
                // 获取命名空间
                $pathArr = explode('.', $classPath);
                $namespaceClass = $pathArr[0] ?? '';
                $namespaceClass = str_replace('/', '\\', $namespaceClass);
                require $classPath;
                $obj = new $namespaceClass;
                // 通过反射获取对应 signature
                $refl = new ReflectionClass($obj);
                $sigStr = $obj->getSignature();
                $classBox[$sigStr] = $obj;
            }
        }
        if ($item->isDir()) {
            $dir = "Novel/Consoles/{$fileName}";
            getConsoleClass($dir);
        }
    }
}