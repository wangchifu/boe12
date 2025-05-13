<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use Notifiable;
    use Impersonate;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'openid',
        'edu_key',
        'uid',
        'group_id',
        'name',
        'email',
        'telephone',
        'password',
        'code',
        'other_code',
        'school',
        'school_id',
        'kind',
        'title',
        'section_id',
        'my_section_id',
        'login_type',
        'admin',
        'disable',
        'logined_at',
        'disabled_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function  userpowers() {
        return $this->hasMany(UserPower::class);
    }
}
