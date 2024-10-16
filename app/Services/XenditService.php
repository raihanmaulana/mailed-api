<?php

namespace App\Services;

use App\Models\Product;
use Xendit\Configuration;
use Illuminate\Support\Str;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class XenditService
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    public function createInvoice(Product $product, $payerEmail)
    {
    
        $apiInstance = new InvoiceApi();
        $externalId = 'order_' . Str::uuid();

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $externalId,  
            'description' => $product->description,
            'amount' => $product->price,
            'currency' => 'IDR', 
            'invoice_duration' => 172800, 
            'reminder_time' => 1, 
            'payer_email' => $payerEmail, 
            'success_redirect_url' => 'YOUR_SUCCESS_URL',
            'failure_redirect_url' => 'YOUR_FAILURE_URL', 
        ]);

        try {
        
            $result = $apiInstance->createInvoice($createInvoiceRequest);
            return $result; 
        } catch (\Xendit\XenditSdkException $e) {
            
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'full_error' => $e->getFullError(),
            ];
        }
    }
}
