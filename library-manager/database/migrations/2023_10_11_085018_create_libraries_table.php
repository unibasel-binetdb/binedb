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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active');
            $table->string('name', 255)->nullable();
            $table->string('name_addition', 255)->nullable();
            $table->string('short_name', 8)->nullable();
            $table->string('alternative_name', 512)->nullable();
            $table->string('bibcode', 64)->nullable();
            $table->string('existing_since', 256)->nullable();
            $table->string('shipping_street', 255)->nullable();
            $table->string('shipping_pobox', 128)->nullable();
            $table->string('shipping_zip', 8)->nullable();
            $table->string('shipping_location', 128)->nullable();
            $table->boolean('different_billing_address');
            $table->string('billing_name', 255)->nullable();
            $table->string('billing_name_addition', 255)->nullable();
            $table->string('billing_street', 255)->nullable();
            $table->string('billing_pobox', 128)->nullable();
            $table->string('billing_zip', 8)->nullable();
            $table->string('billing_location', 128)->nullable();
            $table->text('billing_comment')->nullable();
            $table->text('library_comment')->nullable();
            $table->string('institution_url')->nullable();
            $table->string('library_url')->nullable();
            $table->string('associated_type', 64)->nullable();
            $table->string('faculty', 64)->nullable();
            $table->string('departement')->nullable();
            $table->string('uni_regulations')->nullable();
            $table->string('bibstats_identification')->nullable();
            $table->text('associated_comment')->nullable();
            $table->string('uni_costcenter')->nullable();
            $table->string('ub_costcenter')->nullable();
            $table->text('finance_comment')->nullable();
            $table->string('it_provider', 64)->nullable();
            $table->string('ip_address', 128)->nullable();
            $table->text('it_comment')->nullable();
            $table->boolean('iz_library');
            $table->string('state_type', 64)->nullable();
            $table->string('state_since')->nullable();
            $table->string('state_until')->nullable();
            $table->text('location_comment')->nullable();
            $table->text('state_comment')->nullable();
            $table->string('storage', 128)->nullable();
            $table->string('sticker', 16)->nullable();
            $table->text('colletion_comment')->nullable();

            $table->timestamps();
        });

        Schema::create('library_buildings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
            $table->string('copier', 128)->nullable();
            $table->string('additional_devices', 128)->nullable();
            $table->text('comment')->nullable();
            $table->boolean('key');
            $table->string('key_depot', 128)->nullable();
            $table->text('key_comment')->nullable();
            $table->string('operating_area', 128)->nullable();
            $table->string('audience_area', 128)->nullable();
            $table->string('staff_workspaces', 128)->nullable();
            $table->string('audience_workspaces', 128)->nullable();
            $table->string('workspace_comment', 128)->nullable();
            $table->text('space_comment')->nullable();
            
            $table->timestamps();
        });

        Schema::create('library_slsps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
            
            $table->string('status', 16)->nullable();
            $table->text('status_comment')->nullable();
            $table->string('cost', 128)->nullable();
            $table->string('usage', 128)->nullable();
            $table->text('cost_comment')->nullable();
            $table->string('agreement', 16)->nullable();
            $table->text('agreement_comment')->nullable();
            $table->string('ftes', 128)->nullable();
            $table->text('fte_comment')->nullable();
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });

        Schema::create('library_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
            
            $table->boolean('is_special_stock');
            $table->text('special_stock_comment')->nullable();
            $table->boolean('is_depositum');
            $table->boolean('is_inst_depositum');
            $table->text('inst_depositum_comment')->nullable();
            $table->string('pushback', 256)->nullable();
            $table->string('pushback_2010', 256)->nullable();
            $table->string('pushback_2020', 256)->nullable();
            $table->string('pushback_2030', 256)->nullable();
            $table->string('memory_library', 256)->nullable();
            $table->string('running_1899', 64)->nullable();
            $table->string('running_1999', 64)->nullable();
            $table->string('running_2000', 64)->nullable();
            $table->string('running_zss_1999', 64)->nullable();
            $table->string('running_zss_2000', 64)->nullable();
            $table->string('running_zss_1899', 64)->nullable();
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });

        Schema::create('library_catalogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
            
            $table->boolean('is_072');
            $table->boolean('is_082');
            $table->boolean('is_084');
            $table->text('nz_fields')->nullable();
            $table->text('iz_fields')->nullable();
            $table->text('comment')->nullable();

            $table->timestamps();
        });

        Schema::create('library_functions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
            
            $table->string('cataloging', 3)->nullable();
            $table->text('cataloging_comment')->nullable();
            $table->string('subject_idx_local', 3)->nullable();
            $table->string('subject_idx_gnd', 16)->nullable();
            $table->text('subject_idx_comment')->nullable();
            $table->string('acquisition', 3)->nullable();
            $table->text('acquisition_comment')->nullable();
            $table->string('magazine_management', 3)->nullable();
            $table->text('magazine_management_comment')->nullable();
            $table->string('lending', 10)->nullable();
            $table->text('lending_comment')->nullable();
            $table->string('self_lending', 3)->nullable();
            $table->text('self_lending_comment')->nullable();
            $table->string('basel_carrier', 3)->nullable();
            $table->text('basel_carrier_comment')->nullable();
            $table->string('slsp_carrier', 16)->nullable();
            $table->text('slsp_carrier_comment')->nullable();
            $table->string('rfid', 3)->nullable();
            $table->text('rfid_comment')->nullable();
            $table->string('slsp_bursar', 16)->nullable();
            $table->text('slsp_bursar_comment')->nullable();
            $table->string('print_daemon', 16)->nullable();
            $table->text('print_daemon_comment')->nullable();
            
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
};
