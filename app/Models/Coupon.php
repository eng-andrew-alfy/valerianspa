<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Spatie\LaravelPackageTools\Package;

    class Coupon extends Model
    {
        use HasFactory;

        protected $fillable = [
            'code',
            'discount_type',
            'coupon_type',
            'value',
            'usage_limit',
            'created_by',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
            'is_active',
        ];

        public $timestamps = true;


        public function categories()
        {
            return $this->belongsToMany(categories::class, 'coupon_category', 'coupon_id', 'category_id');
        }

        public function packages()
        {
            return $this->belongsToMany(Packages::class, 'coupon_package', 'coupon_id', 'package_id');
        }

        public function usages()
        {
            return $this->hasMany(CouponUsage::class);
        }

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'created_by');
        }
    }
