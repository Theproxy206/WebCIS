<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'Categories';
    protected $primaryKey = 'cat_serial';
    public $incrementing = true;
    protected $keyType = unsignedInteger;
    protected $fillable = [
        'cat_name'
    ];
    public $timestamps = false;
}
