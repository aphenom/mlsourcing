<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentOption extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];
    // Specify the table associated with the model
    protected $table = 'payment_options';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'image',
        'name',
        'details',
    ];

    protected $casts = [
        'details' => 'array', // Cast details to an array
    ];
}
