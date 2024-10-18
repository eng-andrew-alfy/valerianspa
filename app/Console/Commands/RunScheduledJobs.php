<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use App\Jobs\SendOrderReminder;
    use App\Jobs\ArchiveOrders;
    use App\Jobs\ArchiveGifts;
    use App\Jobs\UpdateSessionStatusJob;
    use Illuminate\Support\Facades\Log;

    class RunScheduledJobs extends Command
    {
        protected $signature = 'app:run-scheduled-jobs';
        protected $description = 'Run all scheduled jobs';

        public function handle()
        {
            Log::info('RunScheduledJobs command started');

            $this->info('Running scheduled jobs...');

            SendOrderReminder::dispatch();
            ArchiveOrders::dispatch();
            ArchiveGifts::dispatch();
            UpdateSessionStatusJob::dispatch();

            $this->info('Scheduled jobs completed.');
            Log::info('RunScheduledJobs command completed');
        }
    }
