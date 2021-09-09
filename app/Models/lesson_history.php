<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lesson_history extends Model
{
    protected $fillable = [
        'student_id','lesson_id','status_lesson','course_id','teacher_id','date','time','zoom_link','coin'
    ];
}
