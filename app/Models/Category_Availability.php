<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Category_Availability extends Model
    {
        use HasFactory;

        protected $fillable = ['category_id', 'in_spa', 'in_home'];
        protected $table = 'category_availability';

        protected $casts = [
            'in_spa' => 'boolean',
            'in_home' => 'boolean',
        ];

        // Define the inverse relationship with Services
        public function category()
        {
            return $this->belongsTo(categories::class, 'category_id');
        }

        public function isAvailable($location)
        {
            if ($location === 'spa') {
                return $this->in_spa;
            }
            return $this->in_home;
        }

    }
