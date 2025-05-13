<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class post_schools_view extends Model
{
    protected $fillable = [
        'ps_id',
        'id',
        'post_no',
        'category_id',
        'section_id',
        'user_id',
        'name',
        'code',
        'situation',
        'title',
        'content',
        'signed_user_id',
        'signed_at',
        'created_at',
        'updated_at',
        'views',
    ];
}
