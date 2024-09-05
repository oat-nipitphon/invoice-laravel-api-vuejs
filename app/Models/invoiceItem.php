<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'invoice_id',
        'quantity',
        'unit_price'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
