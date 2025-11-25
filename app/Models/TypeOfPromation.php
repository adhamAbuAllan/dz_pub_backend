<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfPromation extends Model
{
    // type name
    protected $table = 'type_of_promations';
    protected $fillable = [
        'promation_id',
        'type_id',
    ];
    // Relationships
    public function promation()
    {
        return $this->belongsTo(Promation::class, 'promation_id');
    }
    public function promationType()
    {
        return $this->belongsTo(PromationType::class, 'type_id');
    }
    public function typeOfPromations()
    {
        return $this->hasMany(TypeOfPromation::class, 'type_id');
    }
}
