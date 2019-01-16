# Blade模板
> https://laravelacademy.org/post/8773.html

Blade视图文件使用`.blade.php`文件扩展名并存放在`resource/views`目录下。

### 模板继承
#### 定义布局
使用Blade的两大优点是模板继承和片段组合

	<!-- 存放在 resources/views/layouts/app.blade.php -->

	<html>
	    <head>
	        <title>应用名称 - @yield('title')</title>
	    </head>
	    <body>
	        @section('sidebar')
	            这里是侧边栏
	        @show
	
	        <div class="container">
	            @yield('content')
	        </div>
	    </body>
	</html>

#### 继承布局
定义子页面的时候，可以使用Blade的`@extends`指令来制定子页面所继承的布局，继承一个Blade布局的视图可以使用`@section`指令注入内容到布局定义的内容片断中，如上面例子所示，这些内容将会显示在布局中使用`@yeild`的地方。

	<!-- 存放在 resources/views/child.blade.php -->

	@extends('layouts.app')
	
	@section('title', 'Laravel学院')
	
	@section('sidebar')
	    @parent
	    <p>Laravel学院致力于提供优质Laravel中文学习资源</p>
	@endsection
	
	@section('content')
	    <p>这里是主体内容，完善中...</p>
	@endsection

在本例中，`sidebar`片段使用`@parent`指令来追加（而非覆盖）内容到继承布局的侧边栏，`@parent`指令在视图渲染时将会被布局中的内容替换。

> 注：与之前的实例相反，`sidebar`部分已`@endsection`结束而不是`@show`，`@endsection`指令只是定义一个section而`@show`指令定义并立即返回这个section。

Blade视图可以通过`view`方法直接从路由返回：
	
	Route::get('blade' function(){
		return view('child');
	});

#### 组件&插槽
组件和插槽给内容片段（section）和布局（layout）带来了方便，不过，有些人可能会发现组件和插槽的模型更容易理解。首先，我们假设有一个可复用的“alert”组件，我们想在真个应用中复用它。

	<!-- /resources/views/alert.blade.php -->

	<div class="alert alert-danger">
	    {{ $slot }}
	</div>

`{{$slot}}`变量包含了我们想要注入组件的内容，现在，要构建这个组件，我们可以使用Blade指令`@component`

	@component('alert')
		<strong> Code </strong> Code!
	@endcomponent
有时候为组件定义多个插槽很有用。下面我们来编辑alert组件以便可以注入“标题”，命名插槽可以通过“echoing”与他们的名字相匹配的变量来显示：
	
	<!-- /resources/views/alert.blade.php -->

	<div class="alert alert-danger">
	    <div class="alert-title">{{ $title }}</div>
	    {{ $slot }}
	</div>

现在，我们可以使用指令`@slot`注入内容到命名的插槽。任何不在`@slot`指令中的内容会被传递到组件的`$slot`变量中

	@component('alert')
		@slot('title')
			Forbidden
		@endslot
		
		this is a test string
	@endcomponent

对应浏览器会输出一下内容
![](https://static.laravelacademy.org/wp-content/uploads/2017/10/15077228160879.jpg)

这段代码的意思是通过组件名`alert`去查找对应的视图文件，装在到当前视图，然后通过组件中`@slot`定义的插槽内容去渲染插槽视图中对应的插槽位，如果组件没有为某个插槽位定义对应的插槽内容片段，则组件中的其他不在`@slot`片段中的内容将用于渲染该插槽位，如果没有其他多余内容则对应插槽位为空。

##### 传到额外数据到组件
有时候你可能需要传递额外的数据到组件，出于这个原因，你可以传递数组数据作为第二个参数草`@component`指令，所有数据都会在逐渐模板中以变量方式生效：

	@component('alert', ['foo' => 'bar'])
		//code
	@endcomponent

##### 组件名
如果Blade组件存储在子目录中，你可能想要给他们起名以便访问。例如，假设有一个存放在`resourse/views/components/alert.blade.php`的Blade组件，你可以使用`component`方法将这个组件设置别名为`alert`(原名是`componenrs.alert`)。通常，这个操作在`AppServiceProvider`的`boot`方法中完成：

	use Illuminate\Support\Facades\Blade;
	Blade::component('component.alert', 'alert');

组件设置别名后，就可以使用如下命令来渲染：

	@alert(['type' => 'danger'])
		You are not allowed to access this resource!
	@endalert

如果没有额外插槽的话也可以省略组件参数

	@alert
		You are not allowed to access this resource!
	@endalert

### 数据显示
可以通过两个花括号包裹变量来显示传递到视图的数据，比如，如果给出如下路由：

	Route::get('greeting' function() {
		return view('welcome', ['name' => '学院君']);
	});

那么可以通过如下方式显示`name`变量的内容：

	您好，{{ $name }}。

当然，不限制显示到视图中的变量内容，你还可以输出任何PHP函数的结果，实际上，可以将任何PHP代码放到Blade模板语句中：

	The current UNIX timestamp is {{ time() }}.

> 注：Blade的`{{}}`语句已经经过PHP的`htmlentities`函数处理以避免XSS攻击。

##### 输出存在的数据
有时候你想要输出一个变量，但是不确定该变量是否被设置，我们可以通过如下PHP代码：
	
	{{ isset($name) ? $name : 'Default' }}
除了三元式，Blade还一共了更简单的方式：

	{{ $name or 'Default' }}

##### 显示原生数据
默认情况下，Blade的`{{}}`语句已经通过PHP的`htmlentities`函数处理以避免XSS攻击，如果不想要数据被处理，可以使用如下语法：

	Hello，{!! $name !!}.

##### 渲染JSON内容
Blade提供了特殊的json处理函数`@json`
	
	<script>
		var app = @json($array);
	</script>

##### HTML实体编码