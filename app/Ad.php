<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    //protected $connection = 'sphinx';

    protected  $table = 'ads';
    protected $fillable = ['title', 'content', 'contact','user_id','image_url'];
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
