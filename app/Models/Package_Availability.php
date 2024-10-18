<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Package_Availability extends Model
    {
        use HasFactory;

        protected $fillable = ['package_id', 'in_spa', 'in_home'];
        protected $table = 'package_availability';

        // Define the inverse relationship with Services
        public function package()
        {
            return $this->belongsTo(Packages::class, 'package_id');
        }

        public function isAvailable($location)
        {
            if ($location === 'spa') {
                return $this->in_spa;
            }
            return $this->in_home;
        }

    }
