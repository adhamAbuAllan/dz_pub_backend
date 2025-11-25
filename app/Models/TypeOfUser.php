<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfUser extends Model
{
    // table name
    protected $table = 'type_of_users';
    protected $fillable = [
        'name',
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'type_id');
    }
    
}
