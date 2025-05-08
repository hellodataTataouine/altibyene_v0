<?php

namespace Modules\Order\app\Models;

use App\Models\Course;
use App\Models\CoursSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Database\factories\EnrollmentFactory;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id' => 'order_id',
        'user_id' => 'user_id',
        'course_id' => 'course_id',
        'has_access' => 'has_access',
        'session_id' =>'session_id',
    ];

    function course() : BelongsTo{
       return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    public function session(): BelongsTo{
        return $this->belongsTo(CoursSession::class,'session_id','id');
    }

}
