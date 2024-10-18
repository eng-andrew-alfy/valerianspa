<?php

    namespace App\Jobs;

    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Foundation\Queue\Queueable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Http;

    class SendOtpJob implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        protected $phone;
        protected $otp;

        /**
         * Create a new job instance.
         */
        public function __construct($phone, $otp)
        {
            $this->phone = $phone;
            $this->otp = $otp;
        }

        /**
         * Execute the job.
         */
        public function handle()
        {
//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => 'https://watsabot.com/api/send-otp',
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => '',
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => 'POST',
//                CURLOPT_POSTFIELDS => array(
//                    'appkey' => 'df855543-8e78-458d-8baa-a1c08e347951',
//                    'authkey' => 'Cn6OIsqpTbTMDAccQRrJYksGZIVuE9gPWTT7B7SN7TCcT8wN45',
//                    'to' => '+966' . $this->phone,
//                    'otp' => $this->otp,
//                    'sandbox' => 'false'
//                ),
//            ));
//
//            curl_exec($curl);
//            curl_close($curl);

            Log::info('OTP sent to: ' . $this->phone);
        }


//        public function handle()
//        {
//            $phoneNumberId = '428109160384470'; // Phone Number ID
//            $accessToken = 'EAAHle6gCHJkBOxYZBKVWrj8nmJPYdxBiFIbUW899ZC26ecwb7AEVGnQhlDCQUigHF5NtFbJFCsp63Yi3peFa5rcp5ZAAiUqIMDlgXYfj8ZB7by9I1XNS2uAYuNCnVwdsjK6GP4GMAFktvXMEKSSq0e7jtinCIoH5kIw9z9dbgKZBrqGXqsVNZAOjWZB2KjcNWZCHaZANRbJysaVC2Nm0RxZB5ZCwQxuZAHzH'; // Access Token
//
//            Log::info('Starting CURL request');
//            $clientName = 'فاطمة'; // اسم العميل
//
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v20.0/{$phoneNumberId}/messages");
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
//                'messaging_product' => 'whatsapp',
//                'to' => '+201202829754', // رقم الهاتف مع البادئة الدولية
//                'type' => 'template',
//                'template' => [
//                    //'name' => 'hello_world', // اسم القالب
//                    'name' => 'gifts', // اسم القالب
//                    'language' => ['code' => 'ar'],
//                    'components' => [
//                        [
//                            'type' => 'header',
//                            'parameters' => [
//                                [
//                                    'type' => 'image',
//                                    'image' => ['link' => 'https://kodeaa.com/assets/image/logokodea.svg'] // رابط الصورة
//                                ]
//                            ]
//                        ],
//                        [
//                            'type' => 'body',
//                            'parameters' => [
//                                ['type' => 'text', 'text' => 'اهلين وسهلين عندنا لك هدية 🤩🎁 من شخص يعزك و يحبك إسمه غالى عليك وعلينا ` *' . $clientName . '* ` شاركينا اليوم والوقت المناسب لك 😌 الخدمة منزلية لباب بيتك 🦋'] // نص الرسالة مع اسم العميل
//                            ],
//                        ],
//                        [
//                            'type' => 'button',
//                            'sub_type' => 'url',
//                            'index' => '0',
//                            'parameters' => [
//                                [
//                                    'type' => 'text',
//                                    'text' => 'https://valerianspa.com/gift/55555555' // الرابط كـ URL
//                                ]]
//                        ],
//                    ],
//                ]
//            ]));
//            curl_setopt($ch, CURLOPT_HTTPHEADER, [
//                'Authorization: Bearer ' . $accessToken,
//                'Content-Type: application/json',
//            ]);
//
//            Log::info('CURL request sent, waiting for response');
//
//            $response = curl_exec($ch);
//            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//            $error = curl_error($ch);
//            curl_close($ch);
//
//            Log::info('CURL Response code: ' . $httpCode);
//            Log::info('CURL Response body: ' . $response);
//            Log::info('CURL Error: ' . $error);
//        }

    }
