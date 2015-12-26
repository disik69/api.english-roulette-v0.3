<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Collocation
 * @package App
 *
 * @property $body
 * @property $ts
 */
class Word extends Model
{
    protected $fillable = ['body', 'ts'];

    public function translations()
    {
        return $this->hasMany(Translation::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
