<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'belong',
        'type',
        'name',
        'link',
        'order_by',
        'target',
        'path',
    ];

    /**
    public function setOrderByAttribute($value)
    {
        if($value == null) {
           $this->attributes['order_by'] = 99;
        }
    }
     */
}
