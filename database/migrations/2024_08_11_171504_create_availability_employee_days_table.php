<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('availability_employee_days', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('availability_employee_id');
                $table->unsignedBigInteger('day_of_week_id');
                $table->foreign('availability_employee_id')->references('id')->on('availability_employees')->onDelete('cascade');
                $table->foreign('day_of_week_id')->references('id')->on('days_of_week')->onDelete('cascade');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('availability_employee_days');
        }
    };
