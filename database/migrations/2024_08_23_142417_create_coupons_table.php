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
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('discount_type', ['fixed', 'percentage']);
                $table->enum('coupon_type', ['infinite', 'temporary']);
                $table->decimal('value', 8, 2);
                $table->integer('usage_limit')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
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
            Schema::dropIfExists('coupons');
        }
    };
