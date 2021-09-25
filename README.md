# NovelSpider
* 爬取小说，练练手。仅供学习交流之用。
* 可抓取特定小说，并生成 txt 文件，然后生成二维码手机扫描下载小说。

## Feature
* 多进程抓取 [biquge](https://www.biquwu.cc) 小说，存入数据库
* 将单个小说的所有章节导出为 txt 文件
* 将文件上传到云端，生成二维码，以便手机下载 txt 文件

## 环境要求
* `*Unix` 环境
* redis 服务
* PHP >= 7.2

## 使用
### docker 环境
* 构建镜像 `docker build . --file ./Dockerfile --tag {your_tagname}`
* 也可以直接拉取我构建好的镜像 
    * `docker pull suhanyujie/novel_spider:latest`
    * `docker run --name novel_spider -d suhanyujie/novel_spider:latest`

### 安装(install)
* 前端界面参考 https://github.com/suhanyujie/NovelSpiderFrontend

### 配置
* `cp env.example .env`
* 在 `.env` 文件中配置好对应的环境信息
* 安装 composer 依赖：`composer install`

### 运行
* 启动提供 web 服务的程序，**必须**到项目根目录运行：`php Novel/NovelAdmin/index.php start`
* 启动：`./start.sh start`
* 停止：`./start.sh stop`

#### 功能使用
* 抓取列表 `php Novel/NovelSpider/start_list.php start`
* 抓取章节内容 `php Novel/NovelSpider/start_detail.php start`
* 将小说导出为 txt 文件：`php Novel/Consoles/index.php novel:exportTxt 10`，其中 10 是小说id
* 通过二维码下载小说：`php Novel/Consoles/index.php novel:uploadToCloud 10`，其中 10 是小说id

## 一些注意事项
* 更新 workerman 的软件包 `composer update workerman/workerman`

### composer 镜像源
* 可以先把 composer.lock 文件删除，配置阿里的镜像源 `composer config repo.packagist composer https://mirrors.aliyun.com/composer/`
* 然后 `composer install`

## 关于爬取小说的一些思路
### web api
* 控制器路径位于 `Novel/Controllers`。例如控制器 `Novel/Controllers/Access/LoginController.php` 的 URI 是 `/Access/Login/login`

### 列表爬虫思路：
* 一个单独的进程，会有定时器，定时循环所有小说看他们是否产生最新连载。 20170422
* 一旦有发现一个，则将其尚未爬取的章节加入到“爬取详情页”的任务队列

#### 数据表
* `novel_main` 一部小说一条记录 

##### 处理状态流程图
* ![deal_status_transfer](./doc/images/deal_status_transfer.png)

#### 一些记录
* 将 [`illuminate/database`](https://github.com/illuminate/database) 升级到 `^8.61.0`
* dom解析使用一个[dom解析服务](https://github.com/suhanyujie/practice/tree/master/htmlParserServer)  20190406
* 实现和前端NovelSpiderFrontend的简单交互                                     20190405
* 去除软件包`indieteq/indieteq-php-my-sql-pdo-database-class`，改用`illuminate/database`  20190303
* 引入league/route路由，对应文档http://route.thephpleague.com/4.x/usage/   20190109
* 添加前端界面展示 20181106
* 更换出具库的查询工具 20181102
* 建立好测试目录,引入phpunit 20170423
* 编写测试代码.已成功在本地运行一个测试代码

## 参考
* workerman 官方手册 http://doc.workerman.net/

### 类库
* 数据库 orm，使用`illuminate/database`
* workerman 框架,来自第三方. https://github.com/walkor/Workerman
* DOM 解析，[querylist](http://doc.querylist.cc/)。
* 前端界面是基于：[iview-admin](https://github.com/iview/iview-admin)，仓库地址是：https://github.com/suhanyujie/NovelSpiderFrontend
