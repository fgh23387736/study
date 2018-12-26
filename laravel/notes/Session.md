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

### 使用Session
#### 获取数据
在Laravel中主要有两种方式处理Session数据：全局的辅助函数`session`或者`Request`实例（启动过程中会将Session数据设置到请求实例的`session`属性中）

Request实例
首先，我们通过`Request`实例来访问Session数据，我们可以在控制其方法中对请求实例进行依赖注入（控制其方法依赖通过Laravel服务容器制动注入）

	public function show(Request $request, $id){
		$value = $request->session()->get('key');
	}
	
	$value = $request->session()->get('key', 'default');
	
	$value = $request->session()->get('key', function(){
		return 'default';
	});
	
#### 全局Session辅助函数
还可以使用全局的PHP函数`session`来获取和存储Session数据，如果只传递一个字符串到`session`方法，则返回该Session键对应的值；如果传递的参数是key/value键值对数组，则将这些数据保存到Session：

	Route::get('home', function(){
		//从session获取数据
		$value = session('key');
		
		//指定默认值
		$value = session('key', 'default');
		
		//存储数据到session
		session(['key' => 'value']);
	});

#### 获取所有Session数据
如果你想要从Session中获取所有数据，可以使用`all`方法：

	$data = $request->session()->all();
	
#### 判断Session中是否存在指定项
`has`方法可用于检查数据项在Session中是否存在。如果存在且不为null的话返回`true`：

	if($request->session()->has('users')){
		//code
	}
	
要判断某个值在Session中是否存在，即使是`null`也无所谓，则可以使用`exists`方法。如果存在的话`exists`返回`true`

	if($request->session()->exists('users')){
		//code
	}
	
### 存储数据
要在Session中存储数据，通常可以通过`put`方法或`session`辅助函数：

	//通过调用请求实力的put方法
	$request->session()->put('key', 'value');
	
	//通过全局辅助函数session
	session(['key', 'value']);

#### 推送数据到数组Session
`push`方法可用于推送数据到值为数组的Session，例如，如果`user.teams`键包含团队名数组，可以像这样推送新值到该数组：

	$request->session()->push('user.teams', 'developers');

#### 获取&删除数据
`pull`方法江湖童工一条语句从Session获取并删除数据

	$value = $request->session()->pull('key', 'default');
	
### 一次性数据
有时候可能想要在Session中存储只在下个请求中有效的数据，这可以通过`flash`方法来实现。使用该方法存储的Session数据只在随后的HTTP请求中有效，然后将会被删除。

	$request->session()->flash('status', 'llllll');
	
如果需要在更多请求中保持该一次性数据，可以使用`reflash`方法，该方法将所有一次性数据保留到下一个请求，如果你只是想要保持特定一次性数据，可以使用`keep`方法：

	$request->session()->reflash();
	$request->session()->keep(['username', 'email']);
	
### 删除数据
`forget`方法从Session中移除指定数据，如果你想要从Session中移除所有数据，可以使用`flush`方法：

	$request->session()->forget('key');
	$request->session()->flush();

### 重新生成Session ID
从新生成Session ID经常用于阻止恶意用户对应用进行session fixation攻击

如果使用Laravel内置的`LoginController`的话 ，Laravel会在认证期间自动重新生成session ID，如果需要手动生成session ID，可以使用`regenerate`方法：

	$request->session()->regenerate();


### 自定义驱动
> 以后再学