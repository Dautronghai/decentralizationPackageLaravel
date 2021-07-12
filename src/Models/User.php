<?php

namespace App;

use Dotenv\Result\Result;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    const level = array("No Level","level 1", "Level 2", "Level 3");

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'userName', 'email', 'password', 'level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all roles of user
     * @var collection
     */
    public function roles(){
        return $this->belongsToMany(Role::class,'role_user');
    }
    /**
     * Get all groups of user
     */
    public function groups(){
        return $this->belongsToMany(Group::class,'user_group');
    }
    public function permissions(){
        $roles = $this->roles;
        foreach($this->groups as $group){
         //   dd($group->role()->get()->toArray());
            if($group->role != null){
                if(!$roles->contains($group->role)){
                    $roles->push($group->role);
                }
            }
        }
        $permissions = $roles->pluck('permissions')->collapse()->unique('slug');
        //dd($permissions);
        return $permissions;
    }
    /**
     * Check permission
     * @var string permission slug
     * return true if has permission
     *
     */
    public function checkPermission($slug){
      // dd ($this->permissions()->toArray());
       return $this->permissions()->pluck('slug')->contains($slug);
    }

}
