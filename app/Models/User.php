<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_url',
        'website_url',
        'country',
        'professional_portofolio',
        'social',
        'setting_id',
        'stats',
        'current_subscription_status',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function role(){
       return $this->belongsTo(Role::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }
    public function stats(): Attribute{
        return Attribute::make(
           get : fn ($value) => json_decode($value,true),
           set : fn ($value) => json_encode($value)
        );
   }
   public function social(): Attribute{
    return Attribute::make(
       get : fn ($value) => json_decode($value,true),
       set : fn ($value) => json_encode($value)
    );
   }
   public function portofolios(): Attribute{
    return Attribute::make(
       get : fn ($value) => json_decode($value,true),
       set : fn ($value) => json_encode($value)
    );
   }
}
