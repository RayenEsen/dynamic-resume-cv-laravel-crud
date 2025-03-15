<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $primaryKey = 'resume_id'; // Explicitly set the primary key
    public $incrementing = true; // Ensure it's auto-incremented
    protected $keyType = 'int'; // Set the key type
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class, 'resume_id', 'resume_id');
    }

    public function education()
    {
        return $this->hasMany(Education::class, 'resume_id', 'resume_id');
    }

    public function experience()
    {
        return $this->hasMany(Experience::class, 'resume_id', 'resume_id');
    }

    public function skills()
    {
        return $this->hasMany(Skills::class, 'resume_id', 'resume_id');
    }

    public function projects()
    {
        return $this->hasMany(Projects::class, 'resume_id', 'resume_id');
    }

    public function interests()
    {
        return $this->hasMany(Interests::class, 'resume_id', 'resume_id');
    }

    public function languages()
    {
        return $this->hasMany(Languages::class, 'resume_id', 'resume_id');
    }

    public function contactInformation()
    {
        return $this->hasOne(ContactInformation::class, 'resume_id', 'resume_id');
    }
}
