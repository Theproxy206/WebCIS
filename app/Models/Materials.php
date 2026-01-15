<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materials extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'mat_serial';
    protected $fillable = [
        'mat_title',
        'mat_publication_date',
        'mat_code',
        'mat_description'
    ];
    protected $keyType = 'unsignedInteger';

    /**
     * Function that represents the one-to-many relationship with files
     *
     * @return HasMany
     * @class Materials
     */
    public function files() : HasMany
    {
        return $this->hasMany(Files::Class, 'fk_material_file', 'mat_serial');
    }

    /**
     * Function that represents the ownership of users over materials
     *
     * @return BelongsTo
     * @class Materials
     */
    public function users() : BelongsTo
    {
        return $this->belongsTo(User::Class, 'fk_material_users', 'user_id');
    }
}
