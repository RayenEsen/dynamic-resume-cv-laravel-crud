<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyContactInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_informations', function (Blueprint $table) {
            // Add missing columns here
            $table->string('email')->nullable()->after('resume_id');
            $table->string('phone_number')->nullable()->after('email');
            $table->string('website')->nullable()->after('phone_number');
            $table->string('linkedin_link')->nullable()->after('website');
            $table->string('github_link')->nullable()->after('linkedin_link');
            $table->string('twitter')->nullable()->after('github_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_informations', function (Blueprint $table) {
            // Drop columns if rollback happens
            $table->dropColumn([
                'email',
                'phone_number',
                'website',
                'linkedin_link',
                'github_link',
                'twitter',
            ]);
        });
    }
}
