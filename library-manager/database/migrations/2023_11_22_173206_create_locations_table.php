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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->nullable();
            $table->string('loc_name', 256)->nullable();
            $table->string('example_rule', 256)->nullable();
            $table->string('usage_unit', 16)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->foreignId('library_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
