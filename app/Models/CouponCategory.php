<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CouponCategory extends Model
    {
        use HasFactory;

        protected $table = 'coupon_category';

        protected $fillable = [
            'coupon_id',
            'category_id',
        ];
        public $timestamps = true;

        public function coupons()
        {
            return $this->belongsToMany(Coupon::class, 'coupon_category', 'category_id', 'coupon_id');
        }
    }
