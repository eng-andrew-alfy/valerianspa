<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Spatie\Translatable\HasTranslations;

    class Employee extends Model
    {
        use HasFactory;
        use HasTranslations;

        public $translatable = ['name'];
        protected $fillable = ['name', 'code', 'email', 'phone', 'identity_card', 'country', 'work_location', 'created_by'];
        protected $table = 'employees';
        public $timestamps = true;

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'created_by');
        }

        public function availability()
        {
            return $this->hasOne(Availability_Employees::class, 'employee_id');
        }

        public function places()
        {
            return $this->hasMany(AvailabilityEmployeePlace::class, 'place_id', 'id');
        }

        public function orders()
        {
            return $this->hasMany(Order::class);
        }

        public function workingDays()
        {
            return $this->belongsToMany(DayOfWeek::class, 'availability_employee_days', 'availability_employee_id', 'day_of_week_id');
        }

    }
