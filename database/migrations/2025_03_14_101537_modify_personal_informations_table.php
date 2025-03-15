<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPersonalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_information', function (Blueprint $table) {
            // Add any missing columns here
            $table->string('first_name')->nullable()->after('resume_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('profile_title')->nullable()->after('last_name');
            $table->text('about_me')->nullable()->after('profile_title');
            $table->string('image_path')->nullable()->after('about_me');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_information', function (Blueprint $table) {
            // Drop columns if rollback happens
            $table->dropColumn([
                'first_name',
                'last_name',
                'profile_title',
                'about_me',
                'image_path',
            ]);
        });
    }
}
