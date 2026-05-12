<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,SoftDeletes;

    protected $dates = ['deleted_at'];

    public function getRoleAttribute(){
        return $this->attributes["role"];
    }

    public function isAdmin(){
        return $this->role === 1;
    }

    public function isAgent(){
        return $this->role === 2;
    }

    public function isSeller(){
        return $this->role === 3;
    }

    public function isComptable(){
        return $this->role === 4;
    }

    public function isPending(){
        return $this->status === 'pending';
    }

    public function isBlocked(){
        return $this->status === 'blocked';
    }

    public function isActive(){
        return $this->status === 'active';
    }

    // Other model methods and properties

    public function sourcingCountries()
    {
        return $this->belongsToMany(SourcingCountry::class, 'agent_sourcing', 'agent_id', 'sourcing_country_id');
    }

    public function destinationCountries()
    {
        return $this->belongsToMany(DestinationCountry::class, 'agent_destinations', 'agent_id', 'destination_country_id');
    }
    
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = generate_code('USR', 'users');
            }
        });
    }

    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'address',
        'role', 'status', 'user_type', 'company_name', 'company_information',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'integer',
        ];
    }

    
}
