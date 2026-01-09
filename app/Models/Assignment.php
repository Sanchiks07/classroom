<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Submission;

class Assignment extends Model
{
    protected $fillable = [
        'classroom_id',
        'title',
        'description',
        'file_path',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
