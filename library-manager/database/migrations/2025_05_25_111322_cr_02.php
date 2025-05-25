<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('library_functions', function (Blueprint $table) {
            $table->string('acquisition', 11)->nullable()->change();
            $table->string('digitization', 9)->nullable();
            $table->text('digitization_comment')->nullable();
            $table->string('sls_key', 9)->nullable();
            $table->text('sls_key_comment')->nullable();
            $table->string('emedia', 3)->nullable();
            $table->text('emedia_comment')->nullable();
        });

        Schema::table('library_catalogs', function (Blueprint $table) {
            $table->text('catalog_comment')->nullable();
        });

        Schema::table('people', function (Blueprint $table) {
            $table->boolean('training_emedia')->default(false);
            $table->boolean('digirech_share')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('library_functions', function (Blueprint $table) {
            $table->string('acquisition', 3)->nullable()->change();
            $table->dropColumn([
                'digitization',
                'digitization_comment',
                'sls_key',
                'sls_key_comment',
                'emedia',
                'emedia_comment',
            ]);
        });

        Schema::table('library_catalogs', function (Blueprint $table) {
            $table->dropColumn('catalog_comment');
        });

        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('training_emedia');
            $table->dropColumn('digirech_share');
        });
    }
};
