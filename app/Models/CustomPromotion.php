<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'text',
    ];
    protected $table = 'custom_promotions';

    // Relationship to Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

