<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class DashboardLog extends Model
    {
        use HasFactory;

        protected $fillable = [
            'admin_id',
            'action',
            'url',
            'ip',
            'device',
            'details',
        ];
        public $timestamps = false;

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'admin_id');
        }
    }
