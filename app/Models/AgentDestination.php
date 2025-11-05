<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AgentDestination extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'agent_destinations';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'agent_id',
        'destination_country_id',
    ];
}
