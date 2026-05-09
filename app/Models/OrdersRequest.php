<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ImportedProduct;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrdersRequest extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];    protected $table = 'ordersrequests';

    protected $fillable = [
        'sellerID', 'agentID', 'requestNO', 'statusRequest', 'countryFrom', 'countryTo', 'ShippingMethod',
        'commission_percent', 'commission_amount', 'commission_type', 'transit_mode',
        'transit_client_amount', 'transit_internal_margin', 'transit_payment_mode',
        'currency_code', 'currency_rate',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'sellerID');
    }

    public function importedproducts()
    {
        return $this->hasMany(ImportedProduct::class, 'requestID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'requestID');
    }

    
}
