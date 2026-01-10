<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Classroom;
use App\Models\Submission;
use App\Models\Comment;

class Assignment extends Model
{
    protected $fillable = [
        'classroom_id',
        'title',
        'description',
        'file_path',
        'file_name',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
