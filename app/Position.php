<?php

namespace App;

/**
 * Class Position
 * @package App
 *
 * @property $body
 */
class Position extends Model
{
    protected $fillable = ['body'];

    public function words()
    {
        return $this->hasMany(Word::class);
    }
}
