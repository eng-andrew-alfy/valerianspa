<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Spatie\Translatable\HasTranslations;

    class categories extends Model
    {
        use HasFactory;
        use HasTranslations;

        public $translatable = ['name', 'description', 'benefits'];
        protected $fillable = ['name', 'created_by', 'sessions_count', 'duration_minutes', 'description', 'benefits', 'service_id', 'is_active'];
        protected $table = 'categories';
        public $timestamps = true;
        protected $casts = [
            'name' => 'array',
            'description' => 'array',
            'benefits' => 'array',
            'is_active' => 'boolean',
        ];


        public function prices()
        {
            return $this->hasOne(categories_prices::class, 'category_id');
        }

        public function employees()
        {
            return $this->belongsToMany(Employee::class, 'employee_availability_category', 'category_id', 'employee_id');
        }

        public function availability()
        {
            return $this->hasOne(Category_Availability::class, 'category_id');
        }

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'created_by');
        }

        public function service()
        {
            return $this->belongsTo(Services::class, 'service_id');
        }

        public function discount()
        {
            return $this->hasOne(CategoryDiscount::class, 'category_id');
        }
    }
