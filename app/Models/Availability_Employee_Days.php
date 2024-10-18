<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Availability_Employee_Days extends Model
    {
        use HasFactory;

        protected $fillable = ['availability_employee_id', 'day_of_week_id'];
        protected $table = 'availability_employee_days';
        public $timestamps = true;

        public function availabilityEmployee()
        {
            return $this->belongsTo(Availability_Employees::class, 'availability_employee_id');
        }

        public function dayOfWeek()
        {
            return $this->belongsTo(DayOfWeek::class, 'day_of_week_id');
        }
    }
