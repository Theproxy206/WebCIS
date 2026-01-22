<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rule extends Model
{
    protected $table = 'rules';
    protected $keyType = 'integer';
    protected $primaryKey = 'rule_id';
    protected $fillable = [
        'rule_name',
    ];

    public function users() : HasMany
    {
        return $this->hasMany(User::class, 'fk_rules', 'rule_id')->with('granted');
    }
}
