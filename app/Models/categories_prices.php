<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class categories_prices extends Model
    {
        use HasFactory;

        protected $table = 'categories_prices';
        protected $fillable = ['category_id', 'price', 'is_active'];

        public function category()
        {
            return $this->belongsTo(categories::class, 'category_id');
        }

        public function discount()
        {
            return $this->hasOne(CategoryDiscount::class, 'category_id');
        }
    }
