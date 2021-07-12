<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use Notifiable;
    protected $fillable = ['name','slug'];
    /**
     * Get all user of role
     */
    public function users(){
        return $this->belongsToMany(User::class,'role_user');
    }
    /**
     * Get all permission of role
     */
    public function permissions(){
        return $this->belongsToMany(Permission::class,'role_permission');
    }
    /**
     * Get all group have role
     */
    public function groups(){
        return $this->hasMany(Group::class);
    }
}
