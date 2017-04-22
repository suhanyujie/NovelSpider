# NovelSpider

* 爬取个人喜欢的小说,练练手


# 类库
* mysql采用pdo类库,来自第三方.https://github.com/indieteq/indieteq-php-my-sql-pdo-database-class
* workerman框架,来自第三方. https://github.com/walkor/Workerman
* DOM解析,来自第三方.http://doc.querylist.cc/

# 坑

* 对于上方所说的pdo类,在使用过程中,踩了个坑.
* 在用它进行中文的insert的时候,到了数据库中,乱码了.
* 乱码的第一反应是,先用其他的方法替代,就不用它的方法.
* 用其他的方法测试成功之后,我再来找乱码的原因,发现是因为在`Db.class.php`这个而文件中,有一个bind方法,使用了utf8_encode方法,将抓取到的数据,进行了编码导致,这对于英文来讲没什么关系,但对于中文却是致命的..
* 最后,将bind稍加修改,又能再次使用上方所说的pdo类.

# 安装(install)

* 将第三方pdo的类做小小调整:将`/vendor/indieteq/indieteq-php-my-sql-pdo-database-class/easyCRUD/easyCRUD.class.php`下的私有属性改为protected.(private $db;->protected $db;)


## 关于爬取小说的一些思路
### 列表爬虫思路：
* 一个单独的进程，会有定时器，定时循环所有小说看他们是否产生最新连载。 20170422
* 一旦有发现一个，则将其尚未爬取的章节加入到“爬取详情页”的任务队列
*


