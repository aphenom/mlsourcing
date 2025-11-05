<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ImportedProduct;
use App\Models\Payment;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrdersRequest extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];    protected $table = 'ordersrequests';

    protected $fillable = [
        'sellerID', 'agentID', 'requestNO', 'statusRequest', 'countryFrom', 'countryTo', 'ShippingMethod'
    ];

    public function importedproducts()
    {
        return $this->hasMany(ImportedProduct::class, 'requestID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'requestID');
    }

    
}
