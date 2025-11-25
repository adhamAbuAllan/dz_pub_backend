<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromationStatus extends Model
{
    // The table associated with the model.
    protected $table = 'promation_statuses';
    protected $fillable = [
        'id',
        'name',
    ];




    
}
