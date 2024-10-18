<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Services extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable=['name','image','is_active','created_by'];
    protected $table = 'services';
    public $timestamps = true;
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    // Define the relationship with ServiceAvailability
    public function serviceAvailability()
    {
        return $this->hasOne(Service_Availabilities::class, 'service_id');
    }
}
