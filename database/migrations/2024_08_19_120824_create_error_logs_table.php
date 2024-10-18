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
            Schema::create('error_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->nullable();

                $table->text('error_message');
                $table->text('stack_trace')->nullable();
                $table->string('url')->nullable();
                $table->ipAddress('ip')->nullable();
                $table->text('device')->nullable();
                $table->text('user_agent')->nullable();
                $table->integer('error_code')->nullable();
                $table->json('request_data')->nullable();
                $table->string('previous_url')->nullable();
                $table->string('os')->nullable(); // نظام التشغيل (Windows, Mac, iOS, Android)
                $table->string('platform')->nullable(); // نوع البلاتفورم (web, mobile, desktop)
                $table->timestamps();

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('error_logs');
        }
    };
