<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Permission extends Model
{
    //
    use Notifiable;
    protected $fillable = ['name','slug','guard_id'];
    /**
     * Get role permission
     */
    public function roles(){
        return $this->belongsToMany(Role::class,'role_permission');
    }
    /**
     * Get Guard of permission
     */
    public function guard_P(){
        return $this->belongsTo(Guard::class);
    }
}
