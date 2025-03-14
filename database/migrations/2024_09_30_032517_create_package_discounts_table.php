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
            Schema::create('package_discounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_id');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->decimal('at_home', 10, 2)->nullable();
                $table->decimal('at_spa', 10, 2)->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();

                $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('package_discounts');
        }
    };
