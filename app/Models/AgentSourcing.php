<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentSourcing extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'agent_sourcing';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'agent_id',
        'sourcing_country_id',
    ];
}
