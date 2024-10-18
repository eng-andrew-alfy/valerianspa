<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Employee_Availability_Category extends Model
    {
        use HasFactory;

        protected $table = 'employee_availability_category';
        protected $fillable = ['employee_id', 'category_id', 'in_spa', 'in_home'];

        public function category()
        {
            return $this->belongsTo(categories::class);
        }

        public function employee()
        {
            return $this->belongsTo(Employee::class);
        }


    }
