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
