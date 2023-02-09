<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    public function category()
    {
        return $this->hasOne(Category::class);
    }
    public function author()
    {
        return $this->hasOne(User::class);
    }
    public function tags()
    {
        return $this->belongsToMany(

            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
