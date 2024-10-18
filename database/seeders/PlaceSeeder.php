<?php

    namespace Database\Seeders;

    use App\Models\Place;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;

    class PlaceSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            Place::create([
                'name' => 'الرياض',
                'coordinates' => '[[24.52953787452631,46.447089910619],[24.460804950850186,46.60227179538462],[24.43330126902402,46.82474493991587],[24.533285862215692,46.96756720554087],[24.65815467491039,46.93186163913462],[24.7654420540061,46.92087531100962],[24.88758686464267,46.86045050632212],[24.939897722533757,46.78079962741587],[24.987207465385833,46.68192267429087],[24.91000851995108,46.539100408665874],[24.842731342746486,46.467689275853374],[24.66564283960116,46.429237127415874]]',
                'created_by' => 1,
            ]);
        }
    }
