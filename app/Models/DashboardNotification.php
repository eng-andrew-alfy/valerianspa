<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Notifications\Notifiable;

    class DashboardNotification extends Model
    {
        use HasFactory, Notifiable;

        protected $fillable = ['message', 'read_at', 'order_id', 'created_at', 'updated_at'];

        public function markAsRead()
        {
            $this->read_at = now();
            $this->save();
        }

        protected $dates = ['read_at'];

        public function broadcastOn()
        {
            return ['notifications'];
        }

        public function broadcastAs()
        {
            return 'NotificationReceived';
        }

    }
