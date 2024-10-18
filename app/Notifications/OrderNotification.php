<?php

    namespace App\Notifications;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;

    class OrderNotification extends Notification
    {
        use Queueable;

        protected $orderData;

        public function __construct($orderData)
        {
            $this->orderData = $orderData;
        }


        /**
         * Get the notification's delivery channels.
         *
         * @return array<int, string>
         */
        public function via(object $notifiable): array
        {
            return ['database'];
        }

        /**
         * Get the mail representation of the notification.
         */
//        public function toMail(object $notifiable): MailMessage
//        {
//            return (new MailMessage)
//                ->line('The introduction to the notification.')
//                ->action('Notification Action', url('/'))
//                ->line('Thank you for using our application!');
//        }
        public function toDatabase($notifiable)
        {
            return [
                'order_code' => $this->orderData['order_code'],
                'customer_name' => $this->orderData['customer_name'],
                'customer_phone' => $this->orderData['customer_phone'],
                'customer_id' => $this->orderData['customer_id'],
                'total_price' => $this->orderData['total_price'],
                'service_name' => $this->orderData['service_name'],
            ];
        }

        /**
         * Get the array representation of the notification.
         *
         * @return array<string, mixed>
         */
        public function toArray(object $notifiable): array
        {
            return [
                //
            ];
        }
    }
