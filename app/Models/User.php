<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const IS_ADMIN = 1;
    const IS_NORMAL = 0;
    const IS_ACTIVE = 0;
    const IS_BANNED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->password = bcrypt($fields['password']);
        $this->save();
    }

    public function remove()
    {
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if ($image == null) {
            return;
        }

        Storage::delete('uploads/' . $this->image);
        $filename = Str::random(10) . '.' . $image->extention();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getAvatar()
    {
        if($this->image == null)
        {
            return 'img\no-user-image.png';
        }
        return '/uploads/' . $this->image;
    }

    public function makeAdmin()
    {
        $this->is_admin = USER::IS_ADMIN;
        $this->save();
    }

    public function makeNormal()
    {
        $this->is_normal = USER::IS_NORMAL;
        $this->save();
    }

    public function toggleAdmin($value)
    {
        if($value == null)
        {
            return $this->makeNormal();
        }
        return $this->makeAdmin();
    }

    public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }
    public function unban()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleBan($value)
    {
        if($value == null)
        {
            return $this->unban();
        }
        return $this->ban();
    }
}

