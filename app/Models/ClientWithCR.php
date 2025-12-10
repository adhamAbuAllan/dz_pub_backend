<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientWithCR extends Model
{
    // fillable property to allow mass assignment

    protected $fillable = [
        'client_id',
        'reg_owner_name',
        'institution_name',
        'branch_address',
        'institution_address',
        'rc_number',
        'nis_number',
        'nif_number',
        'iban',
        'image_of_license',
    ];
    // table
    protected $table = 'clients_with_cr';

    protected $casts = [
        'rc_number' => 'encrypted',
        'nis_number' => 'encrypted',
        'nif_number' => 'encrypted',            
        'iban' => 'encrypted',
        'image_of_license' => 'encrypted'
    ];

    // Define relationship to Client model

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public $timestamps = true;


}
