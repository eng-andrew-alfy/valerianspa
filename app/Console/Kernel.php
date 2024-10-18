<?php

    namespace App\Console;

    use Illuminate\Console\Scheduling\Schedule;
    use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
    use App\Jobs\SendOrderReminder;
    use App\Jobs\ArchiveOrders;
    use App\Jobs\ArchiveGifts;
    use App\Jobs\UpdateSessionStatusJob;
    use Illuminate\Support\Facades\Log;

    class Kernel extends ConsoleKernel
    {
        protected function schedule(Schedule $schedule): void
        {
            $schedule->command('app:run-scheduled-jobs')->everyMinute()->withoutOverlapping();

//            Log::info('Scheduler is running at: ' . now());
//
//            $schedule->job(new SendOrderReminder)->everyMinute()->name('send_order_reminder')
//                ->before(function () {
//                    Log::info('SendOrderReminder job is about to run');
//                })
//                ->after(function () {
//                    Log::info('SendOrderReminder job has finished');
//                });
//
//            $schedule->job(new ArchiveOrders)->everyMinute()->name('archive_orders')
//                ->before(function () {
//                    Log::info('ArchiveOrders job is about to run');
//                })
//                ->after(function () {
//                    Log::info('ArchiveOrders job has finished');
//                });
//
//            $schedule->job(new ArchiveGifts)->everyMinute()->name('archive_gifts')
//                ->before(function () {
//                    Log::info('ArchiveGifts job is about to run');
//                })
//                ->after(function () {
//                    Log::info('ArchiveGifts job has finished');
//                });
//
//            $schedule->job(new UpdateSessionStatusJob)->everyMinute()->name('update_session_status')
//                ->before(function () {
//                    Log::info('UpdateSessionStatusJob is about to run');
//                })
//                ->after(function () {
//                    Log::info('UpdateSessionStatusJob has finished');
//                });
//
//            $schedule->command('inspire')->everyMinute()->name('inspire')
//                ->before(function () {
//                    Log::info('Inspire command is about to run');
//                })
//                ->after(function () {
//                    Log::info('Inspire command has finished');
//                });
        }

        protected function commands(): void
        {
            $this->load(__DIR__ . '/Commands');

            require base_path('routes/console.php');
        }
    }
