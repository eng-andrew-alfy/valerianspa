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
            Schema::create('category_availability', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');// Link service availability to the category (nullable)
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
            Schema::dropIfExists('category_availabilities');
        }
    };
