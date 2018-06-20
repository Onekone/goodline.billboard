<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = ['title', 'content', 'contact','user_id','image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
