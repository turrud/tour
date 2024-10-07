<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['path', 'post_id'];

    protected $searchableFields = ['*'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
