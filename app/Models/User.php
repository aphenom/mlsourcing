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
    protected $fillable = [
        'name',
        'email',
        'password',
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
