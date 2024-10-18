<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->decimal('at_home', 8, 2);
            $table->decimal('at_spa', 8, 2);
            $table->timestamps();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages_prices');
    }
};
