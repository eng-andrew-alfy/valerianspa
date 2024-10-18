<?php

    namespace App\Jobs;

    use App\Models\OrderSession;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Foundation\Queue\Queueable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Carbon\Carbon;

    class UpdateSessionStatusJob implements ShouldQueue
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
            // الحصول على الوقت الحالي
            $now = Carbon::now();

            // البحث عن الجلسات التي حان وقتها وحالتها "معلقة"
            $sessions = OrderSession::where('session_date', '<=', $now)
                ->where('status', 'pending')
                ->get();

            foreach ($sessions as $session) {
                // تحديث حالة الجلسة إلى "اكتملت"
                $session->status = 'completed';
                $session->save();
            }
        }
    }
