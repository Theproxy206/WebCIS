<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'sub_serial';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'sub_code',
        'sub_name',
    ];
    public $timestamps = false;

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'subjects_courses', 'fk_subjects', 'fk_courses', 'sub_serial', 'cou_id');
    }
}
