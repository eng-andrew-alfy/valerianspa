<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Spatie\Translatable\HasTranslations;

    class DayOfWeek extends Model
    {
        use HasFactory;
        use HasTranslations;

        public $translatable = ['day_name']; // Make `day_name` translatable

        protected $fillable = ['day_name'];
        protected $table = 'days_of_week';
        public $timestamps = true;

        public function availabilityEmployeeDays()
        {
            return $this->hasMany(Availability_Employee_Days::class, 'day_of_week_id');
        }

    }
