<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Drop foreign keys in related tables
        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['resume_id']);
        });

        Schema::table('resumes', function (Blueprint $table) {
            $table->renameColumn('id', 'resume_id'); 
        });

        // Re-add foreign keys with updated column name
        Schema::table('skills', function (Blueprint $table) {
            $table->foreign('resume_id')->references('resume_id')->on('resumes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['resume_id']);
        });

        Schema::table('resumes', function (Blueprint $table) {
            $table->renameColumn('resume_id', 'id');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });
    }
};
