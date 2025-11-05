<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinationCountry extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'destination_countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code',
        'country_name',
    ];

    /**
     * The agents associated with the destination country.
     */
    public function agents()
    {
        return $this->belongsToMany(User::class, 'agent_destinations');
    }
}
