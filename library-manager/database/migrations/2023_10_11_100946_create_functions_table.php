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
        Schema::create('person_functions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->foreignId('library_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('work', 64)->nullable();
            $table->string('work_start', 255)->nullable();
            $table->string('work_end', 255)->nullable();
            $table->boolean('exited');
            $table->string('percentage_of_employment', 256)->nullable();
            $table->text('percentage_comment')->nullable();
            $table->string('presence_times')->nullable();
            $table->string('institution', 16)->nullable(false);
            $table->boolean('address_list');
            $table->boolean('email_list');
            $table->boolean('personal_login');
            $table->text('personal_login_comment')->nullable();
            $table->boolean('impersonal_login');
            $table->text('impersonal_login_comment')->nullable();
            $table->text('function_comment')->nullable();
            $table->string('slsp_contact', 32)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('functions');
    }
};
