<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Counter;
use App\Models\InvoiceItem;
use App\Models\Product;


class InvoiceController extends Controller
{

    public function getInvoices () {
        // ASC น้อยไปมาก DESC มากไปน้อย
        $invoices = Invoice::with('customer')->orderBy('id', 'ASC')->get();
        return response()->json([
            'invoices' => $invoices,
            'magess' => 'Get Invoices Successfully.'
        ], 200);
    }

    public function searchInvoices (Request $request) {
        $search = $request->get('id');
        if($search != null){
            $invoices = Invoice::with('customer')
            ->where('id', 'LIKE', "%$search%")
            ->get();

            return response()->json([
                'invoices' => $invoices,
                'magess' => 'Search Get Invoices Successfully.'
            ], 200);

        }else{
            return $this->getInvoices();
        }
    }

    public function showGetInvoice ($id) {

        $invoice = Invoice::with('customer','InvoiceItem.product')->find($id);
        return response()->json([
            'invoice' => $invoice,
            'magess' => 'Get Cuntomer Successfully.'
        ], 200);
    }

    public function createInvoice (Request $request) {

        $counter = Counter::where('key', 'invoice')->first();
        $random = Counter::where('key', 'invoice')->first();
        $invoice = Invoice::orderBy('id', 'DESC')->first();

        if($invoice){
            $invoice = $invoice->id+1;
            $counters = $counter->value + $invoice;
        }else{
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'customer' => null,
            'customer_id' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'term_and_conditions' => 'Default Terms and Conditions',
            'item' => [
                'product_id' => null,
                'product' => null,
                'unit_price' => 0,
                'quantity' => 1
            ],
        ];

        return response()->json([
            'formData' => $formData,
            'magess' => 'Insert Form New Invoice Connect Success.'
        ], 200);

    }

    public function createInvoiceConfig (Request $request) {

        if($request->input("invoice_item")){
            if(isset($request)){
                $invoicedata = [
                    'sub_total'=> $request->input("subtotal"),
                    'total'=> $request->input("total"),
                    'customer_id'=> $request->input("customer_id"),
                    'number'=> $request->input("number"),
                    'date'=> $request->input("date"),
                    'due_date'=> $request->input("due_date"),
                    'discount'=> $request->input("discount"),
                    'reference'=> $request->input("reference"),
                    'terms_and_conditions'=> $request->input("terms_and_conditions"),
                ];
                $invoice = Invoice::create($invoicedata);
            }
            if($invoiceitem = $request->input("invoice_item")){
                foreach (json_decode($invoiceitem) as $item) {
                    $itemdata = [
                        'product_id' => $item->id,
                        'invoice_id' => $invoice->id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                    ];
                    InvoiceItem::create($itemdata);
                }
            }
        }
        return response()->json([
            'status' => 200,
            'megess' => 'Insert Invoice and Invoiceitem Success.'
        ],200);

    }

    public function deleteInvoiceItemCartItem($id) {
        $invoice_item = InvoiceItem::where('id', $id)->first();
        // dd($invoice_item);
        if ($invoice_item) {
            $invoice_item->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Invoice item deleted successfully.'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Invoice item not found.'
            ], 404);
        }
    }

    public function updateSqlEditInvoice(Request $request, $id){

        $invoice = Invoice::with('customer', 'InvoiceItem.product')->where('id', $id)->first();
        $invoice_items = $request->input('invoice_item');


        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->discount = $request->discount;
        $invoice->reference = $request->reference;
        $invoice = $this->deleteInvoiceItemCartItem($id);

        $invoice->update(all());

        if (!is_array($invoice_items)) {
            return response()->json([
                'status' => 400,
                'message' => 'Invoice items should be an array.'
            ], 400);
        }else{
            foreach ($invoice_items as $item) {
                $itemdata['product_id'] = $item['product_id'];
                $itemdata['invoice_id'] = $id;
                $itemdata['quantity'] = $item['quantity'];
                $itemdata['unit_price'] = $item['unit_price'];
                InvoiceItem::create($itemdata);
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Update Invoice and InvoiceItem Success.',
            'response' => $invoice
        ], 200);

    }

    public function deleteInvoice($id){
        try {
            $invoice = Invoice::find($id);
            if (isset($invoice)) {
                InvoiceItem::where('invoice_id', $id)->delete();
                $invoice->delete();
            }

            return response()->json([
                'status' => 200,
                'megess' => 'Delete Invoice and Invoiceitem Success.'
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

}
