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
            Schema::create('order_sessions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('order_id'); // Order ID
                $table->dateTime('session_date')->nullable(); // Date of the session
                $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending'); // Status of the session (e.g., 'pending', 'completed', 'canceled')
                $table->text('notes')->nullable(); // Notes for the session
                $table->timestamps();
                // Relationships
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });

        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('order_sessions');
        }
    };
