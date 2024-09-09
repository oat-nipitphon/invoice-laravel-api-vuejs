<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Counter;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\CartItem;

class CartItemController extends Controller
{
    public function cartItems(){
        // index
    }

    public function cartItemsInsert(Request $request) {
        try {

            $listCart = $request->input("listCart");


            // return $listCart;

            foreach (json_decode($listCart) as $item) {
                $itemdata = [
                    'product_id' => $item->id,
                    'invoice_id' => $invoice->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount'=> $request->discount,
                    'sub_total'=> $request->sub_total,
                    'total'=> $request->total
                ];
                CartItem::create($itemdata);
            }

            return response()->json([
                'status' => 200,
                'megess' => 'Insert Invoice and Invoiceitem Success.'
            ],200);
        } catch (error) {
            console.log(error);
        }

    }

}
