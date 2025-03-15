<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    // Specify the attributes that can be mass assigned
    protected $fillable = [
        'project_title',
        'project_description',
        'resume_id', // Only resume_id is necessary for the relationship
    ];

    /**
     * Define the inverse relationship with Resume.
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
