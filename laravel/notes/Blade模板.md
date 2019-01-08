# Session
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
组件和插槽给内容片段（section）和布局（layout）带来了方便，不过，有些人可能会发现组件和插槽的模型更容易理解。首先，我们假设有一个可服用的“alert”组件，我们想在真个应用中复用它。

	<!-- /resources/views/alert.blade.php -->

	<div class="alert alert-danger">
	    {{ $slot }}
	</div>

`{{$slot}}`变量包含了我们想要注入组件的内容，现在，要构建这个组件，我们可以使用Blade指令`@component`

	@component('alert')
		<strong> Code </strong> Code!
	@endcomponent
有时候为组件定义多个插槽很有用。下面我们来编辑alert组件以便可以注入“标题”，命名插槽可以通过“echoing”与他们的名字相匹配的变量来显示：

	