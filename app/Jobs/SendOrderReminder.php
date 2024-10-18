<?php

    namespace App\Jobs;

    use App\Models\DashboardNotification;
    use App\Models\OrderSession;
    use Carbon\Carbon;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Foundation\Queue\Queueable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;

    class SendOrderReminder implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        /**
         * Create a new job instance.
         */
        public function __construct()
        {
            //
        }

        /**
         * Execute the job.
         */
        public function handle()
        {
            $now = Carbon::now();
            $reminderDate = $now->addDays(2);

            $sessions = OrderSession::where('status', 'pending')
                ->whereDate('session_date', $reminderDate)
                ->with('order')
                ->get();

            foreach ($sessions as $session) {
                $order = $session->order;

                DashboardNotification::create([
                    'order_id' => $order->id,
                    'message' => 'تذكير بأن موعد الجلسة التالية في ' . $session->session_date->format('d-m-Y') . ' سيكون بعد يومين.',
                ]);
            }
        }
    }
