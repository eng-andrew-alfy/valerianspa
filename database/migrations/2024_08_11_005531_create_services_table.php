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
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->json('name');
                $table->string('image')->nullable(); // Add a column for the service image
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');

                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('services');
        }
    };
