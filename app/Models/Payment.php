<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdersRequest;
use App\Models\ImportedProduct;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;

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