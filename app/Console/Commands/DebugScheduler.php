<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Console\Scheduling\Schedule;

    class DebugScheduler extends Command
    {
        protected $signature = 'schedule:debug';
        protected $description = 'Debug the scheduler';

        public function handle(Schedule $schedule)
        {
            $events = $schedule->events();

            if (empty($events)) {
                $this->info('No scheduled commands found.');
                return;
            }

            foreach ($events as $event) {
                $this->info('Command: ' . $event->command);
                $this->info('Schedule: ' . $event->expression);
                $this->info('Next Due: ' . $event->nextRunDate());
                $this->info('---');
            }
        }
    }
