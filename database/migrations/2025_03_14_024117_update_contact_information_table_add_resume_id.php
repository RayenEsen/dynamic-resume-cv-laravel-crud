<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_informations', function (Blueprint $table) {
            // Add resume_id to the contact_informations table
            $table->unsignedBigInteger('resume_id')->nullable();

            // Define the foreign key relationship
            $table->foreign('resume_id')
                  ->references('id')
                  ->on('resumes')
                  ->onDelete('cascade');
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
            // Drop the foreign key and column if rolling back the migration
            $table->dropForeign(['resume_id']);
            $table->dropColumn('resume_id');
        });
    }
};
