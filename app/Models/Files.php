<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Infrastructure model that represents the files uploaded contained within the materials
 *
 * @extends Model
 */
class Files extends Model
{
    protected $table = 'files';
    protected $primaryKey = 'fil_serial';
    protected $keyType = 'unsignedInteger';
    protected $fillable = [
        'fil_name',
        'fil_path',
    ];
}
