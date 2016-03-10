<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package App
 *
 * @property $key
 * @property $value
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    static public function getList()
    {
        return static::all()->lists('value', 'key')->toArray();
    }
}
