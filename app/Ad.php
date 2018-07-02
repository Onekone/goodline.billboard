<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends \Fobia\Database\SphinxConnection\Eloquent\Model
{
    use SoftDeletes;
    protected $connection = 'sphinx';

    protected $fillable = ['title', 'content', 'contact','user_id','image_url'];
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $dates = ['deleted_at'];
}
