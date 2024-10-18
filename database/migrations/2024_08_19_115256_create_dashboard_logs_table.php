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
            Schema::create('dashboard_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->string('action');
                $table->string('url');
                $table->ipAddress('ip');
                $table->text('device');
                $table->longText('details')->nullable();

                $table->timestamp('created_at')->useCurrent();

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('dashboard_logs');
        }
    };
