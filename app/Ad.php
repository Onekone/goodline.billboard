<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Ad extends \Fobia\Database\SphinxConnection\Eloquent\Model
{
    use SoftDeletes;
    use Searchable;
    protected $table = 'ads';
    protected $fillable = ['title', 'content', 'contact','user_id','image_url'];
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toSearchableArray() {
        return $this->only('id','title','content','contact');
    }

    public function searchableAs() {
        return 'ads_index';
    }

    protected $dates = ['deleted_at'];
}
