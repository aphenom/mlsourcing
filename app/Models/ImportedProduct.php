<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdersRequest;
use App\Models\Payment;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportedProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'importedproducts';

    
    protected $fillable = [
        'requestID', 'productName', 'qte', 'productCategory', 'unitPrice', 'totalPrice',
        'purchase_price', 'margin_percent', 'client_unit_price', 'client_total_price',
        'weight', 'measurement_type', 'cbm',
        'productURL', 'productImage', 'productSpecification', 'agentNote', 'statusProduct', 'carrier', 'trackingNumber',
    ];

    public function ordersrequests()
    {
        return $this->belongsTo(OrdersRequest::class, 'requestID');
    }
}
