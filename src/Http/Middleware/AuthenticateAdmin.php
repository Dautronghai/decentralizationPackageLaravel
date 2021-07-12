<?php

namespace hainguyen\decentralization\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
class AuthenticateAdmin
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
        $route = Route::getRoutes()->match($request);
        $route->getActionName();
        //$controlerAction = class_basename($request->route()->action['controller']);
        $controlerAction = explode('@',$request->route()->action['controller']);
        $controller = new \ReflectionClass($controlerAction[0]);
        $className = Str::before($controller->getShortName(), 'Controller');
        $action = Str::slug($controlerAction[1]);

        $request_Check = Str::slug($action. ' '. $className);
        if(Auth::check()){
            if(Auth::user()->groups->count() == 0){
                 return redirect('home');
            }else{
                if(Auth::user()->checkPermission($request_Check)){
                    return $next($request);
                }else{
                    abort(403, 'Unauthorized action.');
                }
            }
           // dd($request->user());
        }else{
            return redirect('admin/login');
        }
      //  return $next($request);
    }
}
