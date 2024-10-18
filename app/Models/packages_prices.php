<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class packages_prices extends Model
    {
        use HasFactory;

        protected $table = 'packages_prices';
        protected $fillable = ['package_id', 'at_home', 'at_spa'];

        public function package()
        {
            return $this->belongsTo(Packages::class, 'package_id');
        }
    }
