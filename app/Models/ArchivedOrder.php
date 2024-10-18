<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class ArchivedOrder extends Model
    {
        use HasFactory;

        protected $table = 'archived_orders';

        protected $fillable = [
            'order_id',
            'data',
            'archived_at',
        ];

        protected $casts = [
            'data' => 'array',
        ];

        public function getOrderAttribute()
        {
            return $this->data['order'] ?? [];
        }

        public function getSessionsAttribute()
        {
            return isset($this->data['sessions']) ? collect($this->data['sessions']) : collect();
        }

        // تعريف العلاقة مع نموذج User
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    }
