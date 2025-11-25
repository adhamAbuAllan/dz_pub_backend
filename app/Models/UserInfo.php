<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    /*
      $table->string('phone_number',15)->nullable();
            //idientity_number
            // unique
            $table->string('identity_number',20)->nullable()->unique();
            //profile_image
            $table->string('profile_image',100)->nullable();
            $table->string('is_verified',3)->default('no');//yes/no

            //user_id
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->unique();

    */
    // table name
    protected $table = 'user_infos';
    protected $fillable = [
        'id',
        'phone_number',
        'identity_number',
        'profile_image',
        'is_verified',
        'user_id',
    ];
   protected $casts = [
        'identity_number' => 'encrypted',
        'profile_image' => 'encrypted'
    ];
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
