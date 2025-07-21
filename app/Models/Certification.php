<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
        'certificat'
    ];
    /**
     * Get the user that owns the certification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the course that owns the certification.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    /**
     * Get the certification's creation date formatted.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

}
