<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInformation extends Model
{
    use HasFactory;

    protected $table = 'contact_informations';

    protected $fillable = [
        'resume_id',
        'email',
        'phone_number',
        'website',
        'linkedin_link',
        'github_link',
        'twitter',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}


