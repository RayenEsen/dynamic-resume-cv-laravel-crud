<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'degree_title', 
        'institute', 
        'edu_start_date', 
        'edu_end_date', 
        'education_description', 
        'resume_id', // Foreign key linking education to resume
    ];

    /**
     * Define the inverse relationship with Resume.
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
