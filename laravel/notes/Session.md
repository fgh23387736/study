# Session
> https://laravelacademy.org/post/8791.html

* Laravel 有一套自身的Session逻辑，所以不要在Laravel中使用`$_SESSION`方法去获得session值。
* Laravel的控制器构造函数中无法获得session值，因为laravel的Session是在`StartSession`中间件启动的，而中间件会在服务容器注册所有服务之后才执行

### 配置
`Session`配置文件`config/session.php`。默认情况下，Laravel使用的驱动为`file`驱动，这对许多应用而言没有什么问题，在生产环境中，可以考虑使用`memcached`或者`redis`驱动以获取更加的`Session`性能，尤其是线上同一个应用部署到多台机器的时候，这才是最佳实践。

Session驱动用于定义请求的Sessionw数据放在哪里，Laravel可以处理多种类型的驱动：

* `file` -Session 数据存储在 `storage/framework/sessions`目录下；
* `cookie` -Session 数据存储在经过安全加密的Cookie中
* `database` -Session 数据存储在数据库中
* `mecached`/`redis` -Session数据存储在Memcached/Redis缓存中，访问速度最快；
* `array` -Session 数据存储在简单的PHP数组中，在多个请求之间是非持久化的。

> 注：数组驱动通常用于运行测试以避免Session数据持久化

### 驱动预备知识
#### 数据库
> 暂略

#### Redis
需要通过`Composer`安装`predis/predis`包。可以再`database`配置文件中配置Redis连接，早Session配置文件中，`connection`选项用于指定Session使用哪一个Redis连接。
> composer require  predis/predis

比如在`config/database.php`中为Redis配置了一个Session连接：
![](https://static.laravelacademy.org/wp-content/uploads/2018/03/15098120616851.jpg)