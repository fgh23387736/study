### HTTP请求
> https://laravelacademy.org/post/8764.html

#### 访问请求实例
需要在构造函数或方法中对`Illuminate\Http\Request`类进行依赖注入

	<?php
	
	namespace App\Http\Request;
	class UserController extends Controller
	{
		public function store(Request $request){
			$name = $request->input('name');
		}
	}
	

##### 依赖注入&路由参数

	// 定义路由参数
	Route::put('user/{id}','UserController@update');
	
	
	<?php
	
	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	
	class UserController extends Controller{
		public function update(Request $request, $id){
			// code
		}
	}
	
	
##### 通过路由闭包访问

	use Illuminate\Http\Request;
	
	Route::get('/', function(Request $request){
	
	});
	
#### 请求路径&方法

eg: 请求路径为：`http://domain.com/user/1`

##### 获取请求路径

> path方法,获取请求的路径信息

	$path = $request->path();
	
	// $path : 'user/1'

> `is`方法，允许验证请求路径是否与给定模式匹配。支持`*`通配符

	if($request->is('user/*')){
		//code
	}

##### 获取请求URL
	
	eg:http://domain.com/user/1?token=laravelacademy.org
	
	$url = $request->url();
	//  $url = http://domain.com/user/1
	
	$fullUrl = $request->fullUrl();
	
	// $fullUrl = http://domain.com/user/1?token=laravelacademy.org

##### 获取请求方法
`method`方法会返回HTTP请求方式。还可以使用`isMethod`方法验证HTTP请求方式是否匹配给定字符串：

	$method = $request->method(); //GET | POST
	
	if($request->isMethod('post')){
		// true or false
	}

#### 请求字符串处理
> laravel 会自动处理字符串去除两端空格，空字符串转换为`null`
> > 如果想去掉这个功能，在中间件中删除`ConvertEmptyStringsToNull`这个中间件 

#### 获取请求输入
##### 获取所有输入值

	$input = $request->all();
	
##### 获取单个输入值

	$input = $request->input('name')
	
	
	//增加默认值
	$input = $request->input('name', '默认值')
	
	
	//访问数组
	$input = $request->input('products.0.name');
	$name = $request->input('products.*name');
	
	// 当访问http://blog.test/user/1?products[][name]=学院君&products[][name]=学院君小号时
	//$input = '学院君'
	//$name  = [
		0 => "学院君",
		1 => "学院君小号"
	]
	
##### 从查询字符串中获取输入
> 使用`query()`函数,`query()`只会获取url中的参数，而`input()`会同时获取非url中的参数
> 
> 可以用`query`获取`GET`中的所有参数，而其他请求方式要想获得所有参数就只能使用`input`
> 
> `query`使用方法与`input`一样

##### 通过动态属性获取

	$name = $request->name;
	//如果请求中没有就会自动去路由中查找参数

##### 获取JSON输入值
> 只要`Content-Type`请求头被设置为`application/json`，都可以通过`input`方法获取`JSON`数据，还可以通过"."号解析数组：
	
	$name = $request->input('user.name')

##### 获取输入的部分数据
> 使用`only`和`except`方法,这两个方法都可以接受一个数组或动态列表作为参数
	
	$input = $request->only(['username', 'password']);
	$input = $request->only('username', 'password');
	
	$input = $request->except(['credit_card']);
	$input = $request->except('credit_card');

##### 判断参数是否存在

> 判断是否含有某个字段使用`has`方法
	
	if($request->has('name')){
		// 只有当请求中含有name字段时才会返回true
	}
	
	if($request->has(['name', 'email'])){
		// 只有当请求中含有name和email字段时才会返回true
	}

> 判断是否含有并且不为空使用`filled`方法
	
	if($request->filled('name')){
		
	}

#### 上一次请求输入
##### 将输入存入到Session
	
	// 这个方法会把本次数据存入一个一次性Session中,取出后直接销毁
	$requets->flash();
	
	//也可将输入数据的子集存到session中
	$request->flashOnly('username', 'email');
	$request->flashExcept('password');

##### 将输入存储到Session然后重定向
如果你经常需要一次性存储输入请求输入并返回到表单填写页，可以在 redirect 之后调用 withInput 方法实现这样的功能：

	return redirect('form')->withInput();
	return redirect('form')->withInput($request->except('password'));


##### 取出上次请求数据
> 使用`old` 方法

	$name = $request->old('username');
	
> 在`blade`模板中可以使用`old()`函数
	
	<input type="text" name="username" value="{{ old('username') }}">

#### Cookie
##### 从请求中取出Cookie
> laravel的cookie会自动加密

	$value = $request->cookie('name');
	$value = Cookie::get('name);
	//以上两种方法都可以获得cookie

##### 添加Cookie到响应
可以使用`cookie`方法将一个`Cookie`添加到返回的`Response`实例，需要传递名称，值，以及有效期(分钟)到这个方法：

	return response('你好')->cookie(
		'name', '学院君', $minutes
	);

cookie 方法可以接收一些使用频率较低的参数，一般来说，这些参数和 PHP 原生函数 setcookie 作用和意义一致：
	
	return response('欢迎来到 Laravel 学院')->cookie(
	    'name', '学院君', $minutes, $path, $domain, $secure, $httpOnly
	);

添加用于附件的cookie到响应队列，这些Cookie会在响应发送到浏览器之前添加上

	Cookie::queue(Cookie::make('name', 'value', $minutes));
	
	Cookie::queue('name', 'value', $minutes);

##### 生成Cookie实例

	$cookie = cookie('name', '学院君', $minutes);
	return response('欢迎来到 Laravel 学院')->cookie($cookie);


#### 文件上传
##### 获得上传的文件
> `Illuminate\Http\UploadedFile` 看一下这个类

	$file = $request->file('photo');
	$file = $request->photo;
	//以上两种方法都可以
	
使用`hasFile`方法检测文件是否存在与请求中

	if($request->hasFile('photo')) {
		//code
	}

##### 验证文件是否上传成功
使用`isValid`方法判断文件再上传过程中是否出错
	
	if($request->file('photo')->isValid()){
		//code
	}

##### 文件路径&扩展名
`UploadedFile` 类还提供了访问上传文件绝对路径和扩展名的方法。 `extension` 方法可以基于文件内容判断文件扩展名，该扩展名可能会和客户端提供的扩展名不一致：

	$path = $request->photo->path();
	$extension = $request->photo->extension();

##### 其他方法
> 看API

#### 保存上传的文件
要保存上传的文件，需要使用配置的某个文件系统，对应配置位于`config/filesystems.php`:
![](https://static.laravelacademy.org/wp-content/uploads/2018/03/15064074232597.jpg)	

laravel 默认使用`local`配置存放上传文件，即本地文件系统，默认根目录是`storage/app`,`public`也是本地文件系统，只不过存放在这里的文件可以被公开访问，其对应根目录是`storage/app/public`,要让web用户访问到该目录下存放文件的前提是在应用入口`public`目录下建一个软连接`storage`连接到`storage/app/public`

UploadedFile 类有一个 store 方法，该方法会将上传文件移动到相应的磁盘路径上，该路径可以是本地文件系统的某个位置，也可以是云存储（如Amazon S3）上的路径。

store 方法接收一个文件保存的相对路径（相对于文件系统配置的根目录 ），该路径不需要包含文件名，因为系统会自动生成一个唯一ID作为文件名。

store 方法还接收一个可选的参数 —— 用于存储文件的磁盘名称作为第二个参数（对应文件系统配置 disks 的键名，默认值是 local），该方法会返回相对于根目录的文件路径：

	$path = $request->photo->store('images');
	$path = $request->photo->store('images', 's3');

如果你不想自动生成文件名，可以使用 storeAs 方法，该方法接收保存路径、文件名和磁盘名作为参数：

	$path = $request->photo->storeAs('images', 'filename.jpg');
	$path = $request->photo->storeAs('images', 'filename.jpg', 's3');

#### 配置信任代理

要解决这个问题可以使用 `App\Http\Middleware\TrustProxies` 中间件,配置其中的`$proxies`属性列表

	<?php

	namespace App\Http\Middleware;
	
	use Illuminate\Http\Request;
	use Fideloper\Proxy\TrustProxies as Middleware;
	
	class TrustProxies extends Middleware
	{
	    /**
	     * The trusted proxies for this application.
	     *
	     * @var array
	     */
	    protected $proxies = [
	        '192.168.1.1',
	        '192.168.1.2',
	    ];
	
	    /**
	     * The headers that should be used to detect proxies.
	     *
	     * @var string
	     */
	    protected $headers = Request::HEADER_X_FORWARDED_ALL;
	}
	


或者使用通配符'*'

	protected $proxies = '*';

	