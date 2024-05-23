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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('gender', 64)->nullable();
            $table->string('first_name', 128)->nullable();
            $table->string('last_name', 128)->nullable();
            $table->string('seal')->nullable();
            $table->text('comment')->nullable();
            $table->string('training', 32)->nullable();
            $table->boolean('training_cataloging');
            $table->boolean('training_indexing');
            $table->boolean('training_acquisition');
            $table->boolean('training_magazine');
            $table->boolean('training_lending');
            $table->string('education', 32)->nullable();
            $table->boolean('slsp_acq');
            $table->boolean('slsp_acq_plus');
            $table->boolean('slsp_acq_certified');
            $table->boolean('slsp_cat');
            $table->boolean('slsp_cat_plus');
            $table->boolean('slsp_cat_certified');
            $table->boolean('slsp_emedia');
            $table->boolean('slsp_emedia_plus');
            $table->boolean('slsp_emedia_certified');
            $table->boolean('slsp_circ');
            $table->boolean('slsp_circ_plus');
            $table->boolean('slsp_circ_certified');
            $table->boolean('slsp_circ_desk');
            $table->boolean('slsp_circ_limited');
            $table->boolean('slsp_student_certified');
            $table->boolean('slsp_analytics');
            $table->boolean('slsp_analytics_admin');
            $table->boolean('slsp_analytics_certified');
            $table->boolean('slsp_sysadmin');
            $table->boolean('slsp_staff_manager');
            $table->text('access_right_comment')->nullable();
            $table->text('slsp_certification_comment')->nullable();
            $table->string('cataloging_level', 16)->nullable();
            $table->boolean('sls_phere_access');
            $table->text('sls_phere_access_comment')->nullable();
            $table->boolean('alma_completed');
            $table->boolean('edoc_login');
            $table->boolean('edoc_full_text');
            $table->boolean('edoc_bibliographic');
            $table->text('edoc_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
