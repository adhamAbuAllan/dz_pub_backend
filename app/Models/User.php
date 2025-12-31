<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
             'name', 'email', 'password', 'type_id', 'is_active'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relationships

    public function typeOfUser()
    {
        return $this->belongsTo(TypeOfUser::class, 'type_id');
    }

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'id');
    }
    public function influencer()
    {
        return $this->hasOne(Influencer::class, 'id');
    }


// البلاغات التي قام بها المستخدم
public function reportsMade()
{
    return $this->hasMany(Report::class, 'reporter_id');
}

// البلاغات الموجهة ضد المستخدم
public function reportsReceived()
{
    return $this->hasMany(Report::class, 'reported_id');
}



}
