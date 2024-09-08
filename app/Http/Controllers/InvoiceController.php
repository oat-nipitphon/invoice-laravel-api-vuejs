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
        try {
            // ASC น้อยไปมาก DESC มากไปน้อย
            $invoices = Invoice::with('customer')->orderBy('id', 'ASC')->get();
            return response()->json([
                'invoices' => $invoices,
                'magess' => 'Get Invoices Successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Get Invoices Error Success : ' . $e->getMessage(),
            ], 500);
        }
    }

    public function searchInvoices (Request $request) {
        try {
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

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Search Get Invoices Error Success : ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showGetInvoice ($id) {
        try {

            $invoice = Invoice::with('customer','InvoiceItem.product')->find($id);
            return response()->json([
                'invoice' => $invoice,
                'magess' => 'Get Cuntomer Successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Get Cuntomer Error Success. : ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createInvoice (Request $request) {
        try {
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

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createInvoiceConfig (Request $request) {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cartItemDeleteInvoiceItem($id){
        try {
            $invoice_item = InvoiceItem::findOrFail($id);
            $invoice_item->delete();
            return response()->json([
                'status' => 200,
                'megess' => 'Insert Invoice and Invoiceitem Success.'
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // public function updateSqlEditInvoice(Request $request, $id){
    //     try {

    //         $invoice = Invoice::where('id', $id)->first();
    //         $invoice = [
    //             'sub_total' => $request->subtotal,
    //             'total' => $request->total,
    //             'customer' => $request->customer_id,
    //             'number' => $request->number,
    //             'date' => $request->date,
    //             'due_date' => $request->due_date,
    //             'discount' => $request->discount,
    //             'refernce' => $request->reference,
    //         ];
    //         $invoice->update($request->all());

    //         $invoice->cartItemDeleteInvoiceItem()->delete();

    //         $invoice_item = $request->input('invoice_item');
    //         foreach($invoice_item as $item){
    //             $itemdata = [
    //                 'product_id' => $item->product_id,
    //                 'invoice_id' => $invoice->id,
    //                 'quantity' => $item->quantity,
    //                 'unit_price' => $item->unit_price,
    //             ];
    //             InvoiceItem::create($itemdata[]);
    //         }

    //         return response()->json([

    //             'status' => 200,
    //             'megess' => 'Update Invoice and Invoiceitem Success.',
    //             'response' => response

    //         ],200);

    //     } catch (\Exception $e) {
    //         return response()->json([

    //             'status' => 500,
    //             'error' => 'An error occurred: ' . $e->getMessage(),

    //         ], 500);
    //     }
    // }

    public function updateSqlEditInvoice(Request $request, $id){
        try {
            // ตรวจสอบว่าคุณได้ query ข้อมูล invoice ถูกต้อง
            $invoice = Invoice::where('id', $id)->first();

            if(isset($invoice)){
                // ตรวจสอบการอัปเดตข้อมูล
                $invoice->update([
                    'sub_total' => $request->subtotal,
                    'total' => $request->total,
                    'customer_id' => $request->customer_id, // ระวังการอ้างอิง key
                    'number' => $request->number,
                    'date' => $request->date,
                    'due_date' => $request->due_date,
                    'discount' => $request->discount,
                    'reference' => $request->reference,
                ]);
            }

            // ตรวจสอบว่า $invoice_item ถูกส่งมาและเป็น array หรือ object
            $invoice_item = json_decode($request->input('invoice_item'), true);
            if($invoice_item){
                // ลบรายการเดิม
                $invoice->cartItemDeleteInvoiceItem()->delete();
                foreach($invoice_item as $item){
                    // ตรวจสอบข้อมูลที่ถูกต้องก่อน insert
                    InvoiceItem::create([
                        'product_id' => $item['product_id'],
                        'invoice_id' => $invoice->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                    ]);
                }
            }

            return response()->json(['status' => 200, 'message' => 'Update Success'], 200);

        } catch (\Exception $e) {
            // จับข้อผิดพลาดที่เกิดขึ้นและแสดงผล
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
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
