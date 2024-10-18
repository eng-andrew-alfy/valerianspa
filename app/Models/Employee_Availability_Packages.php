<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Employee_Availability_Packages extends Model
    {
        use HasFactory;

        protected $table = 'employee_availability_packages';
        protected $fillable = ['employee_id', 'package_id', 'in_spa', 'in_home'];

        public function employee()
        {
            return $this->belongsTo(Employee::class);
        }


        public function package()
        {
            return $this->belongsTo(Packages::class, 'package_id');
        }


    }
