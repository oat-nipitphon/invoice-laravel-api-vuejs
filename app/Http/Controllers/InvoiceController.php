<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;

class InvoiceController extends Controller
{
    public function getAllInvoice () {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();
        return response()->json([
            'invoices' => $invoices
        ],200);
    }

    public function searchInvoice (Request $request) {

        $search = $request->get('id');
        if($search != null){
            $invoices = Invoice::with('customer')
            ->where('id', 'LIKE', "%$search%")
            ->get();
            return response()->json([
                'invoices' => $invoices,
            ],200);
        }else{
            return $this->getAllInvoice();
        }
    }

}
