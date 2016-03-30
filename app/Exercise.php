<?php

namespace App;

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
        return $this->belongsToMany(Translation::class)->withTimestamps();
    }

    public function setNewStatus()
    {
        $this->status = 'new';
    }

    public function setOldStatus()
    {
        $this->status = 'old';
    }
}
