<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Check if resume_id column exists, if not, add it
            if (!Schema::hasColumn('projects', 'resume_id')) {
                $table->unsignedBigInteger('resume_id');
                $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop the foreign key constraint and the resume_id column
            $table->dropForeign(['resume_id']);
            $table->dropColumn('resume_id');
        });
    }
}
