<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int|null $category_id
 * @property int|null $user_id
 * @property int $is_featured
 * @property int $status
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $date
 * @property string|null $image
 * @property string|null $description
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Post findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;
    use Sluggable;

    const STATUS_IS_DRAFT = 0;
    const STATUS_IS_PUBLIC = 1;
    const IS_FEATURED = 1;
    const IS_STANDART = 0;
    protected $fillable = ['title', 'content', 'date', 'description'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = Auth::user()->id;
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->tags()->detach();
        $this->delete();
    }

    public function removeImage()
    {
        if ($this->image != null) {

            Storage::delete('uploads/' . $this->image);
        }
    }

    public function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $this->removeImage();
        $filename = Str::random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        if ($this->image == null) {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->image;
    }

    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if ($ids == null) {
            return;
        }

        $this->tags()->sync($ids);
    }

    public function setDraft()
    {
        $this->status = Post::STATUS_IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = Post::STATUS_IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value)
    {
        if ($value == null) {
            return $this->setDraft();
        }
        return $this->setPublic();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }

    public function setStandart()
    {
        $this->is_featured = Post::IS_STANDART;
        $this->save();
    }

    public function setFeatured()
    {
        $this->is_featured = Post::IS_FEATURED;
        $this->save();
    }

    public function toggleFeature($value)
    {
        if ($value == null) {
            return $this->setStandart();
        }
        return $this->setFeatured();
    }

    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $date;
    }

    public function getCategoryTitle()
    {
        return ($this->category != null)
            ? $this->category->title
            : null;
    }

    public function getCategoryID()
    {
        return ($this->category != null)
            ? $this->category->id
            : 'Нет категории';
    }

    public function getTagsTitles()
    {
        return (!$this->tags->isEmpty())
            ? implode(', ', $this->tags->pluck('title')->all())
            : 'Нет тегов';
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }

    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious(); //ID
        return self::find($postID);
    }

    public function hasNext()
    {
       return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext(); //ID
        return self::find($postID);
    }

    public function related()
    {
        return self::all()->except($this->id);
    }

    public function hasCategory()
    {
        return $this->category != null ? true : false;
    }

    public static function getPopularPosts()
    {
       return self::orderBy('views', 'desc')->take(3)->get();
    }

    public static function getFeaturePosts()
    {
        return self::where('is_featured', 1)->take(3)->get();

    }

    public static function getRecentPosts()
    {
       return self::orderBy('date', 'desc')->take(4)->get();
    }
}

