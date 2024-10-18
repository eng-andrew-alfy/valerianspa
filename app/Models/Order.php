<?php

    namespace App\Models;

    use App\Mail\OrderCreated;
    use App\Notifications\OrderNotification;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Date;

    class Order extends Model
    {
        use HasFactory;

        protected $table = 'orders';

        protected $fillable = [
            'order_code',
            'user_id',
            'reservation_status',
            'neighborhoods',
            'package_id',
            'category_id',
            'employee_id',
            'total_price',
            'initial_session_date',
            'payment_gateway',
            'location',
            'notes',
            'qr_code',
            'created_by',
            'sessions_count',
            'is_paid',
            'invoice_url',
            'is_gift',
            'neighborhoods',
        ];

        // Define relationships
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function package()
        {
            return $this->belongsTo(Packages::class);
        }

        public function category()
        {
            return $this->belongsTo(categories::class);
        }

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'created_by');
        }

        public function employee()
        {
            return $this->belongsTo(Employee::class, 'employee_id');
        }

        public function sessions()
        {
            return $this->hasMany(OrderSession::class);
        }

        public static function generateCustomId($clientCode)
        {
            $clientCode = strtoupper($clientCode);

            $date = Date::now()->format('Ymd');

            $namePart = strtoupper('valerian_spa');

            $randomString = Str::random(6);

            $randomNumber = random_int(1000, 9999);

            //   return "{$date}-{$clientCode}-{$namePart}-{$randomString}-{$randomNumber}";
            return $namePart . '-' . $randomNumber . '-' . $clientCode . '-' . $date;
        }


        protected static function boot()
        {
            parent::boot();

            static::creating(function ($model) {
                // Ensure custom_id is set before saving
                if (empty($model->order_code)) {
                    $model->order_code = self::generateCustomId();
                }
            });

//            static::created(function ($model) {
//                Log::info('Order created event triggered for order code: ' . $model->order_code);
//
//                $orderData = [
//                    'code' => $model->order_code,
//                    'customer_name' => $model->user->name,
//                    'total_amount' => $model->total_price,
//                    'service_name' => $model->package ? $model->package->getTranslation('name', 'ar') : $model->category->getTranslation('name', 'ar'),
//                ];
//
//                try {
//                    Mail::to('andrewalfy222@gmail.com')->send(new OrderCreated($orderData));
//                    //Log::info('Email sent successfully to andrewalfy222@gmail.com');
//                } catch (\Exception $e) {
//                    Log::error('Error sending email: ' . $e->getMessage());
//                }
//            });
            static::created(function ($model) {
                //if (!auth('admin')->check()) {
                $orderData = [
                    'order_code' => $model->order_code,
                    'customer_name' => $model->user->name,
                    'customer_phone' => $model->user->phone,
                    'total_price' => $model->total_price,
                    'customer_id' => $model->user->id,
                    'service_name' => $model->package ? $model->package->getTranslation('name', 'ar') : $model->category->getTranslation('name', 'ar'),
                ];

                $adminUsers = Admin::get();

                // إرسال إشعار لكل إدمن
                foreach ($adminUsers as $adminUser) {
                    //Log::info('Sending notification to admin: ' . $adminUser->name);
                    $adminUser->notify(new OrderNotification($orderData));
                }
                //}
            });

        }
    }
