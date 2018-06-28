<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Ad extends Model
{
    use SoftDeletes;
   // use Searchable;
    protected $fillable = ['title', 'content', 'contact','user_id','image_url'];
    protected $primaryKey = 'id';

    public function searchableAs()
    {
        return 'title';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $dates = ['deleted_at'];
}
