<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'category', 'tags', 'status', 'user_id'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
