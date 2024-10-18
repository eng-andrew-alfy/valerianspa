<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class PackageDiscount extends Model
    {
        use HasFactory;

        protected $table = 'package_discounts';

        protected $fillable = [
            'package_id',
            'created_by',
            'at_home',
            'at_spa',
            'is_active',
        ];

        public function package()
        {
            return $this->belongsTo(Packages::class, 'package_id');
        }

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'created_by');
        }
    }
