<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Role;
use App\Permission;
use App\Guard;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Roles = Role::all();
        return view('dashboard-admin.role.listRole')->with('Roles', $Roles);
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
        if(isset($request->name_role) && $request->name_role != null){
            $newRole = new Role;
            $newRole->name = $request->name_role;
            $newRole->slug = Str::slug($request->name_role);
            $newRole->save();
        }
        return redirect('admin/role');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {   $roles = Role::all();
        $role = Role::where('slug','=',$slug)->first();
        $guards = Guard::all();
        return view('dashboard-admin.role.rolePermisions')
                ->with('roles',$roles)
                ->with('role',$role)
                ->with('guards', $guards);
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

        $role = Role::find($id);
        $role->name = $request->name;
        $role->permissions()->sync($request->permissions);
        $role->save();
    //   $role->permissions()->detach();
      return redirect('admin/role');

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
