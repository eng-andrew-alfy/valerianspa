<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CategoryDiscount extends Model
    {
        use HasFactory;

        protected $table = 'category_discounts';

        protected $fillable = [
            'category_id',
            'created_by',
            'at_home',
            'at_spa',
            'is_active',
        ];

        public function category()
        {
            return $this->belongsTo(categories::class, 'category_id');
        }

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'created_by');
        }
    }
