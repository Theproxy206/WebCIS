<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class subjects extends Model
{
    protected $table = 'subjects';

    protected $primaryKey = 'sub_serial';

    // Fields that can be filled
    protected $fillable = [
        'sub_code',
        'sub_name',
    ];

    /**
     * Relacion: Un subject pertenece a muchos cursos.
     * Conecta a través de la tabla pivote 'subjects_courses'.
     */

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(courses::class, 'subjects_courses', 'fk_subjects', 'fk_courses');
    }

     /**
     * Relacion: Un subject tiene muchos materiales.
     * Conecta a través de la tabla pivote 'subjects_materials'.
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(materials::class, 'subjects_materials', 'fk_subjects', 'fk_materials');
    }
}