<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use hainguyen\decentralization\Models\Guard;
use hainguyen\decentralization\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $controlerAction = Route::getRoutes();
     //   dd($controlerAction);
        foreach ($controlerAction as $value) {
            $classAction = explode('@',$value->getActionName());
            $refClass= new \ReflectionClass($classAction[0]);

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
                Permission::firstOrCreate([
                    'name'=> $namePermission,
                    'slug'=> $slugPermission,
                    'guard_id' => Guard::where('slug',$guardSlug)->first()->id,
                ]);
            }
        }
    }
}
