<?php

namespace hainguyen\decentralization;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;

use App\Guard;
use App\Permission;
class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->storeAllRoute();

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            Gate::define($permission->slug,function($user) use($permission){
                return $user->checkPermission($permission->slug);
            });
        }

        //Blade directives
        // echo Str::slug(explode('@', Route::getRoutes()->match(Request::create($a))->getActionName())[1].' '.Str::before(class_basename(explode('@', Route::getRoutes()->match(Request::create($a))->getActionName())[0]), 'Controller'));
        Blade::directive('checkVisible', function ($a){
            return "<?php
                if(Auth::user()->checkPermission(Str::slug(explode('@', Route::getRoutes()->getByName($a)->getActionName())[1].' '.Str::before(class_basename(explode('@', Route::getRoutes()->getByName($a)->getActionName())[0]), 'Controller')))) {
            ?>";
        });

        Blade::directive('endcheckVisible', function () {
            return "<?php } ?>"; //return this endif statement inside php tag
        });
        Blade::directive('checkLevel', function ($user) {
                return "<?php if(json_decode($user)->id == Auth::user()->id || intval(Auth::user()->level) > intval(json_decode($user)->level)){ if(json_decode($user)->level != null ) echo 'disabled';} ?>";
        });
    }
    public function storeAllRoute(){
        Artisan::call('migrate');

        $controlerAction = Route::getRoutes();
     //   dd($controlerAction);
        foreach ($controlerAction as $value) {
            $classAction = explode('@',$value->getActionName());

            $refClass= new \ReflectionClass($classAction[0]);
           // echo $refClass;
            $guardName = Str::before($refClass->getShortName(), 'Controller');
            $guardSlug = Str::slug($guardName);
            Guard::firstOrCreate(
                [
                    'name' => $guardName,
                    'slug' => $guardSlug,
                ]
            );
            // Get action header comments
            if( count($classAction) > 1){
                $refAction=new \ReflectionMethod($classAction[0], $classAction[1]);
                $docComment = $refAction->getDocComment();
                $namePermission = trim(str_replace(array('/', '*'), '', substr($docComment, 0, strpos($docComment, '@'))));
                $slugPermission = Str::slug($classAction[1]. '_'. $guardName);
                if($namePermission == ''){
                    $namePermission = $classAction[1]. ' '. $guardName;
                }
                // echo 'a' .$namePermission . ' \n ';
                // echo Guard::where('slug',$guardSlug)->first();
                $guard_id = Guard::where('slug',$guardSlug)->first()->id;
                Permission::firstOrCreate([
                    'name'=> $namePermission,
                    'slug'=> $slugPermission,
                    'guard_id' => $guard_id,
                ]);
            }
        }
    }
}
