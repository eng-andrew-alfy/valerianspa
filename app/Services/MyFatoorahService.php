<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Log;
    use MyFatoorah\Library\MyFatoorah;
    use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
    use Exception;

    class MyFatoorahService
    {
        public $mfConfig;

        public function __construct()
        {
            $this->mfConfig = [
                'apiKey' => config('myfatoorah.api_key'),
                'isTest' => config('myfatoorah.test_mode'),
                'countryCode' => config('myfatoorah.country_iso'),
            ];
        }

        public function getInvoiceURL($orderId, $paymentId = 0, $sessionId = null)
        {
            try {
                $curlData = $this->getPayLoadData($orderId);
                $mfObj = new MyFatoorahPayment($this->mfConfig);
                $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);
                return $payment['invoiceURL'];
            } catch (Exception $ex) {
                Log::error('Error in getInvoiceURL: ' . $ex->getMessage(), ['orderId' => $orderId]);
                throw new Exception(__('myfatoorah.' . $ex->getMessage()));
            }
        }

        public function getPaymentStatus($paymentId)
        {
            try {
                $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
                return $mfObj->getPaymentStatus($paymentId, 'PaymentId');
            } catch (Exception $ex) {
                Log::error('Error in getPaymentStatus: ' . $ex->getMessage(), ['paymentId' => $paymentId]);
                throw new Exception(__('myfatoorah.' . $ex->getMessage()));
            }
        }

        public function getCheckoutGateways($orderTotal, $orderCurrency, $registerApplePay)
        {
            try {
                $mfObj = new MyFatoorahPaymentEmbedded($this->mfConfig);
                $response = $mfObj->getCheckoutGateways($orderTotal, $orderCurrency, $registerApplePay);

                Log::info('MyFatoorah Checkout Gateways Response:', ['response' => $response]);

                return $response;
            } catch (Exception $ex) {
                Log::error('Error in getCheckoutGateways: ' . $ex->getMessage(), ['orderTotal' => $orderTotal]);
                throw new Exception(__('myfatoorah.' . $ex->getMessage()));
            }
        }

        private function getPayLoadData($orderId = null, $productName = null, $invoiceCode = null, $invoiceValue = 0)
        {
            // $callbackURL = route('myfatoorah.callback');
            if (session()->get('MyFatoorah') === 'OrderMyFatoorah') {
                // If the session value is "OrderMyFatoorah", redirect to the callback URL 🎯
                session()->forget('MyFatoorah');  // Remove the session after use 🗑️

                $callbackURL = route('myfatoorah.callback');
            } elseif (session()->get('MyFatoorah') === 'GiftMyFatoorah') {
                session()->forget('MyFatoorah');  // Remove the session after use 🗑

                $callbackURL = route('PaymentGIFT.MyFatoorah.callback');

            } else {
                // If the session value is not "OrderMyFatoorah", send an error message and remove the session ❌
                session()->forget('MyFatoorah');  // Remove the session after checking 🗑️
                return redirect()->back()->with('error', '❌ Oops! The session value is not valid for MyFatoorah. Please try again. ❌'); // Error message
            }

            $order = $this->getOrderData($orderId);

            return [
                'CustomerName' => $order['customer_name'],
                'InvoiceValue' => $invoiceValue,
                'DisplayCurrencyIso' => 'SAR',
                'CustomerEmail' => $order['customer_email'],
                'CallBackUrl' => $callbackURL,
                'ErrorUrl' => $callbackURL,
                'MobileCountryCode' => '+966',
                'CustomerMobile' => $order['customer_mobile'],
                'Language' => app()->getLocale() === "en" ? "en" : " ar",
                'CustomerReference' => $orderId,
                'SourceInfo' => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION,
                'ProductName' => $productName, // إضافة اسم المنتج
                'InvoiceCode' => $invoiceCode // إضافة كود الفاتورة
            ];
        }

        private function getOrderData($orderId)
        {
            // افترض أنك تستخدم نموذج Order للحصول على بيانات الطلب
            $order = \App\Models\Order::find($orderId);

            if (!$order) {
                throw new \Exception('Order not found');
            }

            // يمكنك تعديل هذا حسب بنية بيانات الطلب الخاصة بك
            return [
                'customer_name' => $order->user ? $order->user->name : 'Unknown',
                'total' => $order->total_price,
                'currency' => 'SAR',
                'customer_email' => $order->user ? $order->user->email : 'test@valerianspa.com',
                'customer_mobile' => $order->user ? $order->user->phone : '0000000000',
                'invoice_code' => $order->order_code
            ];
        }

        public function createInvoice($orderId, $productName = null, $invoiceCode = null, $paymentId = 0, $sessionId = null, $totalPrice = 0)
        {
            try {
                $payloadData = $this->getPayLoadData($orderId, $productName, $invoiceCode, $totalPrice);

                $mfObj = new MyFatoorahPayment($this->mfConfig);
                $response = $mfObj->getInvoiceURL($payloadData, $paymentId, $orderId, $sessionId);

                //Log::info('MyFatoorah Response:', ['response' => $response]);

                if (isset($response['invoiceURL'])) {
                    return $response['invoiceURL'];
                } else {
                    // Log::error('Response does not contain invoiceURL', ['response' => $response]);
                    throw new \Exception('فشل في الحصول على رابط الفاتورة.');
                }
            } catch (\Exception $ex) {
                // Log::error('MyFatoorah Error: ' . $ex->getMessage(), ['orderId' => $orderId]);
                throw new \Exception(__('myfatoorah.' . $ex->getMessage()));
            }
        }
    }
