<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Support\Str;

    class Gift extends Model
    {
        use HasFactory;

        protected $keyType = 'string';
        public $incrementing = false;

        protected $fillable = [
            'id', 'gift_code', 'category_id', 'package_id', 'sender_id', 'recipient_id', 'expiry_date', 'used', 'order_id'
        ];

        protected static function boot()
        {
            parent::boot();

            static::creating(function ($model) {
                $model->id = (string)Str::uuid();
            });
        }

        public static function generateGiftCode()
        {
            $date = now()->format('Ymd');
            $namePart = strtoupper('valerian_spa');
            $randomString = Str::random(6);
            $randomNumber = bin2hex(random_bytes(2));

            return "GIFT-{$date}-{$namePart}-{$randomString}-{$randomNumber}";
        }


        public function sender()
        {
            return $this->belongsTo(User::class, 'sender_id');
        }

        public function recipient()
        {
            return $this->belongsTo(User::class, 'recipient_id');
        }

        public function category()
        {
            return $this->belongsTo(categories::class, 'category_id');
        }

        public function package()
        {
            return $this->belongsTo(Packages::class, 'package_id');
        }

        public function order()
        {
            return $this->belongsTo(Order::class);
        }


    }
