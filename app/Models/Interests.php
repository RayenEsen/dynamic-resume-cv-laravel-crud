<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interests extends Model
{
    use HasFactory;

    protected $fillable = [
        'interest', // Assuming you want to store the interest name
        'resume_id', // Reference to the associated resume
    ];

    /**
     * Define the inverse relationship with Resume.
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
