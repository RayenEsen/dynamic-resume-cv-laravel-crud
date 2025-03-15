<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 
        'last_name', 
        'image_path', 
        'profile_title', 
        'about_me',
        'resume_id'  // Removed 'user_id'
    ];

    /**
     * Define the inverse relationship with Resume (assuming 'resume_id' is present).
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
