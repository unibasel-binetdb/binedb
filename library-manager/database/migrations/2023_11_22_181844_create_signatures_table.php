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
        Schema::create('signature_spans', function (Blueprint $table) {
            $table->id();
            $table->string('span', 64)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->foreignId('library_id')->constrained()->onDelete('cascade');
        });

        Schema::create('signature_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment', 16)->nullable();
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
        Schema::dropIfExists('signatures');
    }
};
