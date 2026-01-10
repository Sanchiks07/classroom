<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActionLog extends Model
{ 
    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
