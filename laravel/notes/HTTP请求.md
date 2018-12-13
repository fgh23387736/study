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