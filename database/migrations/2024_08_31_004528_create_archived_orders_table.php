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
            Schema::create('archived_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->json('data');
                $table->timestamp('archived_at');
                $table->timestamps();
                $table->index('order_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('archived_orders');
        }
    };
