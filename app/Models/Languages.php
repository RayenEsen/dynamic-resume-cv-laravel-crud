<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    use HasFactory;

    // Define the table name explicitly if it differs from the default
    protected $table = 'languages';

    // Define the fillable attributes
    protected $fillable = [
        'language',     // Name of the language
        'language_level', // Proficiency level of the language
        'resume_id',    // Foreign key linking the language to the resume
    ];

    // Define the relationship with the Resume model
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
