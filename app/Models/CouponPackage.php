<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CouponPackage extends Model
    {
        use HasFactory;

        protected $table = 'coupon_package';

        protected $fillable = [
            'coupon_id',
            'package_id',
        ];
        public $timestamps = true;

        public function coupons()
        {
            return $this->belongsToMany(Coupon::class, 'coupon_package', 'category_id', 'coupon_id');
        }

    }
