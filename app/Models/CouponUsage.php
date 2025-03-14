<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CouponUsage extends Model
    {
        use HasFactory;

        protected $fillable = [
            'coupon_id',
            'user_id',
        ];
        public $timestamps = true;

        public function coupon()
        {
            return $this->belongsTo(Coupon::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
