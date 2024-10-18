<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class ArchivedGiftOrder extends Model
    {
        use HasFactory;

        protected $table = 'archived_orders';

        protected $fillable = [
            'order_id',
            'gift_data',
            'archived_at',
        ];

        protected $casts = [
            'gift_data' => 'array',
        ];

        public function getOrderAttribute()
        {
            return $this->gift_data['order'] ?? [];
        }

        public function getSessionsAttribute()
        {
            return isset($this->gift_data['sessions']) ? collect($this->gift_data['sessions']) : collect();
        }

        // تعريف العلاقة مع نموذج User
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    }
