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
            Schema::create('packages', function (Blueprint $table) {
                $table->id();
                $table->json('name');
                $table->tinyInteger('sessions_count');
                $table->tinyInteger('duration_minutes');
                $table->json('description');
                $table->json('benefits');
                $table->unsignedBigInteger('service_id');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
                $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('packages');
        }
    };
