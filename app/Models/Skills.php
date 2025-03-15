<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    use HasFactory;

    // Specify the attributes that can be mass assigned
    protected $fillable = [
        'skill_name',
        'skill_percentage',
        'resume_id',
    ];

    // Define the relationship with the Resume model
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
