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
            Schema::create('service_availabilities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_id')->nullable();
//            $table->unsignedBigInteger('category_id')->nullable();
//            $table->unsignedBigInteger('package_id')->nullable();
//            $table->unsignedBigInteger('employee_id')->nullable();
                $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade'); // Link service availability to the service (nullable)
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');// Link service availability to the category (nullable)
//            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade'); // Link service availability to the package, if needed (nullable)
//            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');  // Link service availability to the employee, if needed (nullable)
//
                $table->boolean('in_spa')->default(true); // Is it available in SPA?
                $table->boolean('in_home')->default(true); // Is it available at home?
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('service_availabilities');
        }
    };
