<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'total',
        'customer_id',
        'sub_total',
        'date',
        'due_date',
        'reference',
        'discount',
        'number',
        'terms_and_conditions',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function cartitem(){
        return $this->belongsTo(CartItem::class);
    }

    public function invoiceItem(){
        return $this->hasMany(InvoiceItem::class);
    }

}
