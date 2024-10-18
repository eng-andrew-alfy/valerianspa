<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class AvailabilityEmployeePlace extends Model
    {
        protected $table = 'availability_employee_places';

        protected $fillable = [
            'employee_id',
            'place_id',
        ];

        public function employee()
        {
            return $this->belongsTo(Employee::class);
        }


        public function place()
        {
            return $this->belongsTo(Place::class);
        }


    }
