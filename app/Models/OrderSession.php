<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class OrderSession extends Model
    {
        use HasFactory;

        protected $fillable = [
            'order_id',
            'session_date',
            'status'
        ];

        // Define relationships
        public function order()
        {
            return $this->belongsTo(Order::class, 'order_id');
        }

        public function package()
        {
            return $this->belongsTo(Packages::class);
        }

        public function category()
        {
            return $this->belongsTo(categories::class);
        }
    }
