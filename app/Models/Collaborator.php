<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    protected $table = 'Collaborators';
    protected $primaryKey = 'col_email';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'col_email',
        'col_description',
        'col_path_profile_picture',
        'col_url_linkedin',
        'col_url_website',
        'col_names',
        'col_surname',
        'col_second_surname',
    ];
    public $timestamps = false;
}
