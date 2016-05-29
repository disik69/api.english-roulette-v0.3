<?php

namespace App;

use \App\User;
use Carbon\Carbon;

/**
 * Class Exercise
 * @package App
 *
 * @property $status
 * @property $reading
 * @property $memory
 * @property $check_at
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

    public function setNewStatus(User $user)
    {
        $this->status = 'new';
        $this->reading = $user->reading_count;
        $this->memory = $user->memory_count;
        $this->check_at = null;
    }

    public function setOldStatus(User $user)
    {
        $this->status = 'old';
        $this->reading = 0;
        $this->memory = 0;
        $this->check_at = Carbon::now()->addDays($user->repeat_term)->startOfDay();
    }

    public function getReading()
    {
        return (int) $this->reading;
    }

    public function getMemory()
    {
        return (int) $this->memory;
    }

    public function view()
    {
        $view['id'] = $this->getId();
        $view['word'] = $this->word->body;
        $view['ts'] = $this->word->ts;
        $view['position'] = $this->word->position ? $this->word->position->body : null;
        $view['reading'] = $this->getReading();
        $view['memory'] = $this->getMemory();
        $view['status'] = $this->status;
        $view['check_at'] = $this->getAtomCheckAt();
        $view['translations'] = [];

        $exerciseTranslations = $this->translations;
        $wordTranslations = $this->word->translations;

        foreach ($wordTranslations as $key => $translation) {
            $view['translations'][$key]['id'] = $translation->getId();
            $view['translations'][$key]['body'] = $translation->body;
            $view['translations'][$key]['used'] = $exerciseTranslations->contains('id', $translation->id);
        }

        return $view;
    }

    public function getAtomCheckAt()
    {
        if ($this->check_at) {
            $checkAt = new Carbon($this->check_at);

            $result = $checkAt->toAtomString();
        } else {
            $result = null;
        }

        return $result;
    }
}
