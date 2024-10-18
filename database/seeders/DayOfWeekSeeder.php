<?php

    namespace Database\Seeders;

    use App\Models\DayOfWeek;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;

    class DayOfWeekSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            $days = [
                ['en' => 'Monday', 'ar' => 'الاثنين'],
                ['en' => 'Tuesday', 'ar' => 'الثلاثاء'],
                ['en' => 'Wednesday', 'ar' => 'الأربعاء'],
                ['en' => 'Thursday', 'ar' => 'الخميس'],
                ['en' => 'Friday', 'ar' => 'الجمعة'],
                ['en' => 'Saturday', 'ar' => 'السبت'],
                ['en' => 'Sunday', 'ar' => 'الأحد'],
            ];

            foreach ($days as $day) {
                DayOfWeek::create([
                    'day_name' => $day,
                ]);
            }
        }
    }
