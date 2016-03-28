<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Translation
 * @package App
 *
 * @property $body
 */
class Translation extends Model
{
    protected $fillable = ['body'];

    public function word()
    {
        return $this->belongsToMany(Word::class)->withTimestamps();
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withTimestamps();
    }
}
