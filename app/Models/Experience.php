<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_title', 
        'organization', 
        'job_start_date', 
        'job_end_date', 
        'job_description', 
        'resume_id', // Add this if you want to fill resume_id directly
    ];

    /**
     * Define the inverse relationship with Resume.
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
