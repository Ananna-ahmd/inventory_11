<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use DB;

class InvoiceController extends Controller
{
        public function CreateInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
        $user_id=$request->header('id');
        $total=$request->input('total');
        $discount=$request->input('discount');
        $vat=$request->input('vat');
        $payable=$request->input('payable');
        $customer_id=$request->input('customer_id');

        $invoice=Invoice::create([
            'total'=>$total,
            'discount'=>$discount,
            'vat'=>$vat,
            'payable'=>$payable,
            'user_id'=>$user_id,
            'customer_id'=>$customer_id,

        ]);
        $invoice_id=$invoice->id;
        $products=$request->input('products');

        foreach ($products as $product) {
            InvoiceProduct::create([
                "invoice_id"=>$invoice_id,
                "user_id"=>$user_id,
                "product_id"=>$product['product_id'],
                "quantity"=>$product['quantity'],
                "sale_price"=>$product['sale_price'],

            ]);


        }
        DB::commit();
            return response()->json(['status'=>'success','message'=>'Invoice Created Successfully'],200);

    }
    catch (\Exception $e) {
        DB::rollBack();
        return 0;

    }
    }


    /**
     * Display the specified resource.
     */
    public function invoiceSelect(Request $request,Invoice $invoice)
    {
        $user_id=$request->header('id');
        return Invoice::where('user_id','=',$user_id)->with('customer')->get();
    }



    /**
     * Update the specified resource in storage.
     */
    public function InvoiceDetails(Request $request)
    {
        $user_id=$request->header('id');
        $customerDetails=Customer::where('id','=',$request->input('customer_id'))->where('user_id','=',$user_id)->first();
        $invoiceTotal= Invoice::where('id','=',$request->input('inv_id'))->where('user_id','=',$user_id)->first();
        $invoiceProducts=InvoiceProduct::where('invoice_id','=',$request->input('inv_id'))->where('user_id','=',$user_id)->get();
        return array(
            'customerDetails'=>$customerDetails,
            'invoiceTotal'=>$invoiceTotal,
            'invoiceProducts'=>$invoiceProducts
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function DeleteInvoice(Request $request,Invoice $invoice)
    {
        DB::beginTransaction();
        try{
            $user_id=$request->header('id');
            InvoiceProduct::where('invoice_id','=',$request->input('inv_id'))->where('user_id','=',$user_id)->delete();
            Invoice::where('id','=',$request->input('inv_id'))->delete();
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Invoice Deleted Successfully'],200);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return 0;
        }

    }

}
