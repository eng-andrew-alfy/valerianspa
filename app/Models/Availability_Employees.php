<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Availability_Employees extends Model
    {
        use HasFactory;


        protected $fillable = ['start_time', 'end_time', 'employee_id', 'color'];
        protected $table = 'availability_employees';
        public $timestamps = true;

        public function employee()
        {
            return $this->belongsTo(Employee::class, 'employee_id');
        }

        public function place()
        {
            return $this->belongsTo(Place::class, 'place_id');
        }

        public function employeeDays()
        {
            return $this->hasMany(Availability_Employee_Days::class, 'availability_employee_id');
        }

        public function scopeAvailableOn($query, $date)
        {
            $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeek;
            return $query->whereHas('employeeDays', function ($query) use ($dayOfWeek) {
                $query->where('day_of_week_id', $dayOfWeek);
            });
        }

        public static function generateUniqueColor()
        {
            do {
                $color = '#' . substr(md5(rand()), 0, 6);
            } while (self::where('color', $color)->exists());

            return $color;
        }

    }
