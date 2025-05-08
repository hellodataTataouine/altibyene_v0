<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursSession extends Model
{
    use HasFactory;
    protected $table='cours_sessions';
    protected $fillable = [
        'title',
        'max_enrollments',
        'enrolled_students',
        'cours_id',
        'start_date',
        'end_date',
        'type',
    ];
    public function cours(){
        return $this->belongsTo(Course::class,'cours_id','id');
    }
}
