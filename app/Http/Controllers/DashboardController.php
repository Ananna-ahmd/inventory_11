<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;

class DashboardController extends Controller
{
    function summary(Request $request){
        $user_id=$request->header('id');
        $product=Product::where('user_id','=',$user_id)->count();
        $customer=Customer::where('user_id','=',$user_id)->count();
        $invoice=Invoice::where('user_id','=',$user_id)->count();
        $total=Invoice::where('user_id','=',$user_id)->sum('total');
        $vat=Invoice::where('user_id','=',$user_id)->sum('vat');
        $payable=Invoice::where('user_id','=',$user_id)->sum('payable');
        return[
            'product'=>$product,
            'customer'=>$customer,
            'invoice'=>$invoice,
            'total'=>round($total,2),
            'vat'=>round($vat,2),
            'payable'=>round($payable,2)
        ];


}
}
