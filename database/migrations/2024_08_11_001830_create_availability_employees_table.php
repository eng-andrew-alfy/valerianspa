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
            Schema::create('availability_employees', function (Blueprint $table) {
                $table->id();
                $table->string('color')->nullable(); // Color to display the employee's availability
                $table->unsignedBigInteger('employee_id');
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->time('start_time'); // Time when the employee starts working
                $table->time('end_time'); // Time when the employee ends working
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('availability_employees');
        }
    };
