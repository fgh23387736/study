# 生成URL
> https://laravelacademy.org/post/8787.html

### 快速入门
#### 生成URL
`url`辅助函数可用于为应用生成任意URL,并且生成的URL会自动使用当前请求的`scheme`（HTTP or HTTPS）和 `host` 属性

	$post = App\Post::find(1);
	echo url("/posts/{$post->id}");
	
	// 输出 http://example.com/post/1

#### 访问当前URL
如果没有传递路径信息给`url`辅助函数，则会返回一个`Illuminate\Routing\UrlGenerator`实例，从而允许你访问当前URL的信息。

	// 获取不带请求字符串的当前URL
	echo url()->current();
	
	// 获取包含请求字符串的当前URL
	echo url()->full();
	
	//获取上一个请求的完整URL
	echo url()->previous();

上述每一个方法都可以用URL门面进行访问，例如

	use Illuminate\support\Facades\URL;
	
	echo URL::current();

### 命名路由URL
`route`可用于生成指向命名路由的URL。明明路由允许你生成不与路由中定义的实际URL耦合的URL，因此，当路由的URL改变了，`route`函数调用不需要做任何更改。例如，加入你的应用包含一个定义如下的路由：

	Route::get('post/{post}', function(){
		//code
	})->name('post.show');
	
要生成指向该路由的URL，可以这样使用`route`辅助函数：

	echo route('post.show', ['post' => 1]);
	
	//输出 http://example.com/post/1

通常我们会使用Eloquent模型的主见来生成URL，因此可以传递Eloquent模型作为参数值，`route`辅助函数会自动解析模型主键值，所以，上述方法还可以这么调用：

	echo route('post.show', ['post' => $post]);

### 控制器动作URL
`action`辅助函数用于为控制器动作生成URL，和路由中的定义一样，你不需要传递完成的控制器命名空间，取而代之地，传递相对于`App\Http\Controllers`命名空间的控制器类名即可：

	$url = action('HomeController@index');
	
如果控制器方法接受路由参数，你可以将其作为第二个参数传递给该方法：
	
	$url = action('UserController@profile', ['id' => 1]);
	
### 参数默认值
对某些应用而言，你可能希望为特定的URL参数制定请求默认值，例如，假设多个路由都定义了一个`{local}`变量：
	
	Route::get('/{local}/posts', function(){
		//code
	})->name('post.index');

每次调用`route`辅助函数都要传递`locale`变量显得很笨拙，所以，我们可以在当前请求中使用`URL::defaults`方法为这个参数定义一个默认值，我们可以在某个路由中间件中调用该方法以便可以访问当前请求：

	<?php

	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Support\Facades\URL;
	
	class SetDefaultLocaleForUrls
	{
	    public function handle($request, Closure $next)
	    {
	        URL::defaults(['locale' => $request->user()->locale]);
	
	        return $next($request);
	    }
	}

一旦设置好`locale`参数的默认值之后，就不必再通过`route`辅助函数生成URL时每次指定传递的值了。