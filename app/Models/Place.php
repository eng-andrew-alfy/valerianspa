<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Place extends Model
    {
        use HasFactory;

        protected $fillable = [
            'name',
            'coordinates',
            'created_by',
        ];

        protected $casts = [
            'coordinates' => 'array',
        ];

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'created_by');
        }

        public function availabilities()
        {
            return $this->hasMany(Availability_Employees::class, 'place_id');
        }


        public function employees()
        {
            return $this->belongsToMany(Employee::class, 'availability_employee_places', 'place_id', 'employee_id');
        }

    }
