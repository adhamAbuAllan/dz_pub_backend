<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfUser extends Model
{
    // table name

    public function users()
    {
        return $this->hasMany(User::class, 'type_id');
    }

}
