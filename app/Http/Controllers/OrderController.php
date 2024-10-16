<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\XenditService;

class OrderController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }
    public function createOrder(Request $request, $productId)
    {

        $product = Product::findOrFail($productId);

        $invoice = $this->xenditService->createInvoice($product, $request->input('payer_email'));
       
        if (isset($invoice['error'])) { 

            return response()->json(['error' => $invoice['message']], 500);
        }

        $order = new Order();
        $order->external_id = $invoice['external_id']; 
        $order->product_id = $product->id; 
        $order->status = 'PENDING'; 

        $order->checkout_link = $invoice['invoice_url'];
        $order->save(); 

        return response()->json([
            'invoice' => $invoice,
            'order' => $order
        ]);
    }


    public function handleXenditCallback(Request $request)
    {
        $data = $request->all();

        if ($data['status'] === 'PAID') {
 
            $order = Order::where('external_id', $data['external_id'])->first();

            if ($order) {
                $order->status = 'PAID';
                $order->save();
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
