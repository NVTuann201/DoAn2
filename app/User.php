<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;
use Optix\Media\HasMedia;

class User extends Authenticatable
{
    use Notifiable, HasMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'birthday', 'phone', 'avatar_url', 'cover_url', 'inactive', 'role_id', 'organization_id', 'email_token', 'updated_at', 'isApprove', 'created_at', 'coso_id', 'coso_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarUrlAttribute()
    {
        if (empty($this->attributes['avatar_url'])) {
            return "/images/avatar/defaults/avatar.png";
        }

        return $this->attributes['avatar_url'];
    }

    public function role()
    {
        return $this->belongsTo('App\Role')->withDefault();
    }

    public function company()
    {
        return $this->belongsTo('App\Company')->withDefault();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->userLogs()->delete();
        });
    }

    public function setUserNameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function getUserNameAttribute($value)
    {
        return strtolower($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $info = request()->all();
        $this->notify(new ResetPasswordNotification($token, $info['email']));
    }

    public function userLogs()
    {
        return $this->hasMany('App\UserLog');
    }

    public function mediable()
    {
        return $this->hasOne('App\Mediable', 'mediable_id')->where('mediable_type', 'App\User')->withDefault();
    }
    public function provinces()
    {
        return $this->belongsToMany(\App\Province::class);
    }
    public function coso()
    {
        return $this->morphTo();
    }
}
