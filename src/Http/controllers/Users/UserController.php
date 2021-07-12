<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Group;
use App\Guard;
use GuzzleHttp\Middleware;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {

     // dd(Auth()->can('create_user'));
       // $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allUser = User::all();
        return view('dashboard-admin.user.listUser')->with('users',$allUser);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $guards = Guard::all();
      //  dd($user->permissions()->unique('name'));
        return view('dashboard-admin.user.userPermissions')
                ->with('user',$user)
                ->with('guards', $guards);
    }
    public function deleteRole($user, $role){
       $r = User::find($user)->roles()->detach($role);
        //dd($r->get());
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->middleware('checkModifileUser:'.$id);
        $user = User::find($id);
        $roles = Role::all();
        $groups = Group::all();
        return view('dashboard-admin.user.editUser')
                ->with('user',$user)
                ->with('roles', $roles)
                ->with('groups',$groups);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->middleware('checkModifileUser:'.$id);
        $user = User::find($id);
        $user->level = $request->level == 0 ? NULL : $request->level;
        $user->roles()->sync($request->roles);
        $user->groups()->sync($request->groups);
        $user->save();
        return redirect('admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
