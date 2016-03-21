<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Faker\Generator as FakerGenerator;
use App\User;
use App\Word;
use App\Translation;
use App\Exercise;

class SandboxController extends Controller
{
    /**
     * @var FakerGenerator
     */
    private $faker;

    public function __construct(FakerGenerator $faker)
    {
        $this->faker = $faker;
    }

    public function createCollocations()
    {
        $translations = Translation::all();

        if (! count($translations)) {
            foreach (range(1, 10) as $i) {
                $word = new Word();

                $word->body = $this->faker->word;
                $word->ts = $this->faker->word;

                $word->save();

                echo('Word id: ' . $word->id . '<br>');

                foreach (range(1, 3) as $j) {
                    $translation = new Translation();

                    $translation->body = $this->faker->word;

                    $word->translations()->save($translation);

                    echo('->Translation id: ' . $translation->id . '<br>');
                }
            }
        } else {
            echo 'Collocations already exist.<br>';
        }
    }

    public function createExercise()
    {
        $users = User::all();
        $words = Word::all();

        foreach (range(1, 5) as $i) {
            $user = $users->random();
            $word = $words->random();

            $translation = $word->translations->random();

            $exercise = new Exercise();

            $exercise->status = 'new';
            $exercise->reading = 10;
            $exercise->memory = 10;

            $exercise->user()->associate($user);
            $exercise->word()->associate($word);
            $exercise->translation()->associate($translation);

            $exercise->save();

            echo 'Exercise id: ' . $exercise->id . '<br>';
        }
    }

    public function getUserExercises($id = 0)
    {
        \DB::enableQueryLog();

        $user = User::find($id);

        if ($user) {
            $exercises = $user->exercises->load('word', 'translation');

            foreach ($exercises as $exercise) {
                echo "{$exercise->word->body} [{$exercise->word->ts}]: {$exercise->translation->body}<br>";
            }
        } else {
            echo 'User not found.<br>';
        }

        dd(\DB::getQueryLog());
    }

    public function checkCaptcha()
    {
        $captchaStore = new \App\Captcha\CaptchaStore();

        $captchaStore->add(false, 'abc', \Carbon\Carbon::now()->subMinute(1));
        $captchaStore->add(false, 'abc', \Carbon\Carbon::now()->addMinute(1));
        $captchaStore->add(true, 'aBc', \Carbon\Carbon::now()->addMinute(1));

        $serializedCaptchaStore = serialize($captchaStore);

        $captchaStore = unserialize($serializedCaptchaStore);

        dd(
            \Cache::get('captcha'),
            captcha_check(\Request::get('captcha')),
            \Cache::get('captcha')
        );
    }

    public function testUser()
    {
        $user = \Auth::user();

        return response()->json(compact('user'));
    }

    public function testAdmin()
    {
        $user = \Auth::user();

        return response()->json(compact('user'));
    }
}