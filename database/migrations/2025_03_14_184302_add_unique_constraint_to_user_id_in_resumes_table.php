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
        Schema::table('resumes', function (Blueprint $table) {
            // Drop the existing foreign key (if any)
            $table->dropForeign(['user_id']);
    
            // Add a unique constraint to the user_id column
            $table->unsignedBigInteger('user_id')->unique()->change();
    
            // Re-add the foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('resumes', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['user_id']);
    
            // Revert to a non-unique foreign key
            $table->unsignedBigInteger('user_id')->change();
        });
    }
};
