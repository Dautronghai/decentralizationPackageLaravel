<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Guard extends Model
{
    use Notifiable;
    protected $fillable = ['name','slug'];
    /**
     * Get all role of group
     */
        /**
     * Get all permission of role
     */
    public function permissions(){
        return $this->hasMany(Permission::class);
    }
}
