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
            Schema::create('places', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->json('coordinates');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');

            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('places');
        }
    };
