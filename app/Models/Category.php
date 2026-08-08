<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';
    protected $primaryKey = 'cat_serial';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'cat_name',
        'cat_code',
    ];
    public $timestamps = false;

    public function courses() : BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'categories_courses', 'fk_categories', 'fk_courses', 'cat_serial', 'cou_id');
    }
}
