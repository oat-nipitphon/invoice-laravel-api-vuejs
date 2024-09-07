<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Counter;
use App\Models\InvoiceItem;

class InvoiceController extends Controller
{

    public function getInvoices () {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();
        return response()->json([
            'invoices' => $invoices
        ],200);
    }

    public function searchInvoices (Request $request) {

        $search = $request->get('id');
        if($search != null){
            $invoices = Invoice::with('customer')
            ->where('id', 'LIKE', "%$search%")
            ->get();
            return response()->json([
                'invoices' => $invoices,
            ],200);
        }else{
            return $this->getInvoices();
        }
    }

    public function showGetInvoice ($id) {

        $invoice = Invoice::with('customer','InvoiceItem.product')->find($id);
        // return $invoice;
        return response()->json([
            'invoice' => $invoice
        ],200);

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

        return response()->json($formData);

    }

    public function createInvoiceConfig (Request $request) {

        $invoiceitem = $request->input("invoice_item");

        $invoicedata['sub_total'] = $request->input("subtotal");
        $invoicedata['total'] = $request->input("total");
        $invoicedata['customer_id'] = $request->input("customer_id");
        $invoicedata['number'] = $request->input("number");
        $invoicedata['date'] = $request->input("date");
        $invoicedata['due_date'] = $request->input("due_date");
        $invoicedata['discount'] = $request->input("discount");
        $invoicedata['reference'] = $request->input("reference");
        $invoicedata['terms_and_conditions'] = $request->input("terms_and_conditions");

        $invoice = Invoice::create($invoicedata);

        foreach (json_decode($invoiceitem) as $item) {

            $itemdata['product_id'] = $item->id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;

            InvoiceItem::create($itemdata);
        }

        return response()->json([
            'status' => 200,
            'megess' => 'Insert Invoice and Invoiceitem Success.'
        ],200);

    }

    public function deleteInvoiceItem($id){
        $invoice_item = InvoiceItem::findOrFail($id);
        $invoice_item->delete();
    }

    public function updateInvoice(Request $request){

        return $request;

        return response()->json([
            'status' => 200,
            'megess' => 'Update Invoice and Invoiceitem Success.'
        ],200);
    }

    public function deleteInvoice($id){

        $invoice = Invoice::find($id);

        if ($invoice) {
            // ลบรายการที่เกี่ยวข้องในตาราง InvoiceItem ก่อน
            InvoiceItem::where('invoice_id', $id)->delete();

            // ลบใบแจ้งหนี้ (Invoice)
            $invoice->delete();

            return response()->json([
                'message' => 'Invoice and related items deleted successfully'
            ], 200);
        } else {
            return response()->json(['message' => 'Invoice not found'], 404);
        }
    }

}
