<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_Availabilities extends Model
{
    use HasFactory;
    protected $fillable = ['service_id', 'in_spa', 'in_home'];
    protected $table = 'service_availabilities';

    // Define the inverse relationship with Services
    public function service()
    {
        return $this->belongsTo(Services::class);
    }
}
