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
            Schema::create('gifts', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('gift_code')->unique();
                $table->bigInteger('order_id')->unsigned(); // تأكد من استخدام النوع المناسب
                $table->bigInteger('category_id')->nullable()->unsigned();
                $table->bigInteger('package_id')->nullable()->unsigned();
                $table->bigInteger('sender_id')->nullable()->unsigned();
                $table->bigInteger('recipient_id')->nullable()->unsigned();
                $table->timestamp('expiry_date');
                $table->timestamp('first_open_time')->nullable();
                $table->boolean('used')->default(false);
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('recipient_id')->references('id')->on('users')->onDelete('set null');
            });

        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('gifts');
        }
    };
