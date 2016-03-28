<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Exercise
 * @package App
 *
 * @property $status
 * @property $reading
 * @property $memory
 * @property $checked_at
 */
class Exercise extends Model
{
    protected $fillable = ['status', 'reading', 'memory'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function word()
    {
        return $this->belongsTo(Word::class);
    }

    public function translations()
    {
        return $this->belongsToMane(Translation::class);
    }
}
