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
        return $this->belongsToMany(Translation::class)->withTimestamps();
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
