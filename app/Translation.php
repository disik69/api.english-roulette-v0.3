<?php

namespace App;

/**
 * Class Translation
 * @package App
 *
 * @property $body
 */
class Translation extends Model
{
    protected $fillable = ['body'];

    public function words()
    {
        return $this->belongsToMany(Word::class)->withTimestamps();
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withTimestamps();
    }
}
