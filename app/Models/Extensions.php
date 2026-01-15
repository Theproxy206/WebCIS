<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Infrastructure model that represents the table extensions
 *
 * @extends Model
 * @author Sergio E.
 */
class Extensions extends Model
{
    protected $table = 'extensions';
    protected $primaryKey = 'ext_serial';
    protected $keyType = 'unsignedInteger';


    /**
     * Function that represents the one-to-many relationship with files
     *
     * @return HasMany
     * @class Extensions
     */
    public function files() : HasMany
    {
        return $this->hasMany(Files::Class, 'fk_extension_type', 'ext_serial');
    }
}
