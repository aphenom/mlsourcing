<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdersRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = generate_code('PAY', 'payments');
            }
        });
    }

    protected $dates = ['deleted_at'];
    protected $table = 'payments';

    protected $fillable = [
        'sellerID', 'requestID', 'amount', 'paymentMethod', 'screenshot', 'status'
    ];

    public function ordersrequests()
    {
        return $this->belongsTo(OrdersRequest::class, 'requestID');
    }
}