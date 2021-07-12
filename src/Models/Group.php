<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Group extends Model
{
    use Notifiable;
    protected $fillable = ['name','role_id','slug'];
    /**
     * Get all role of group
     */
    public function role(){
        return $this->belongsTo(Role::class);
    }
    /**
     * Get all user in group
     */
    public function users(){
        return $this->belongsToMany(User::class,'user_group');
    }
}
