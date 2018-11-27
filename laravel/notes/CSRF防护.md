### CSRF保护
> https://laravelacademy.org/post/8742.html
> 
> 源码： `vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php`

构建 JavaScript 驱动的应用时，为方便起见，可以让 JavaScript HTTP 库自动在每个请求中添加 CSRF 令牌。默认情况下，resources/assets/js/bootstrap.js 文件会将 csrf-token meta 标签值注册到 Axios HTTP 库。如果你没有使用这个库，则需要手动在应用中配置该实现。


除了将 CSRF 令牌作为 POST 参数进行验证外，还可以通过设置 X-CSRF-Token 请求头来实现验证，VerifyCsrfToken 中间件会检查 X-CSRF-TOKEN 请求头。实现方式如下，首先创建一个 meta 标签并将令牌保存到该 meta 标签：
	
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
然后在 js 库（如 jQuery）中添加该令牌到所有请求头，这为基于 AJAX 的请求提供了简单、方便的方式来避免 CSRF 攻击：
	
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});