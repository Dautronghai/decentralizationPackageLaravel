<?php
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin/login', 'App\Http\Controllers\Users\AuthController@login')->name('auth.login');
Route::post('admin/signIn','App\Http\Controllers\Users\AuthController@authenticate')->name('auth.signIn');
Route::prefix('admin')->middleware('checkAdmin')->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\DashboardAdmin\dashboardController@dashboard');
    Route::resource('user','App\Http\Controllers\Users\UserController');
   // Route::get('user/{role}/{id}','Users\UserController@deleteRole');
    Route::resource('role', 'App\Http\Controllers\Roles\RoleController');
    Route::resource('group', 'App\Http\Controllers\Groups\GroupController');
});
Route::get('admin/logout',function(){
    if(Auth::check()){
        Auth::logout();
    }
    return redirect('admin/login');
});


