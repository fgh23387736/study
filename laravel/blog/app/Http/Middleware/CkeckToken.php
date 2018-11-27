<?php

namespace App\Http\Middleware;

use Closure;

class CkeckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->input('token') != 'hongli'){
            return redirect()->to('http://www.baidu.com');
        }
        return $next($request);
    }
}
