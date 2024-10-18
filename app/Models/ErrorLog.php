<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class ErrorLog extends Model
    {
        use HasFactory;

        protected $table = 'error_logs';

        protected $fillable = [
            'admin_id',
            'error_message',
            'stack_trace',
            'url',
            'ip',
            'device',
            'user_agent',
            'error_code',
            'request_data',
            'previous_url',
            'os',
            'platform',
        ];
        protected $casts = [
            'request_data' => 'array', // لتخزين البيانات في شكل JSON
        ];
        public $timestamps = true;

        public function admin()
        {
            return $this->belongsTo(Admin::class, 'admin_id', 'id');
        }
    }
