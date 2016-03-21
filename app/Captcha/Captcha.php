<?php

namespace App\Captcha;

use Exception;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Carbon\Carbon;

class Captcha extends \Mews\Captcha\Captcha
{
    /**
     * It calculates out to minutes.
     *
     * @var int
     */
    protected $expirationTime = 10;

    /**
     * @var CacheRepository
     */
    protected $store;

    public function __construct(
        Filesystem $files,
        Repository $config,
        ImageManager $imageManager,
        CacheRepository $store,
        Str $str
    ) {
        $this->files = $files;
        $this->config = $config;
        $this->imageManager = $imageManager;
        $this->store = $store;
        $this->str = $str;
    }

    protected function generate()
    {
        $characters = str_split($this->characters);

        $bag = '';
        for($i = 0; $i < $this->length; $i++) {
            $bag .= $characters[rand(0, count($characters) - 1)];
        }

        $captchaStore = $this->store->get('captcha', new CaptchaStore());

        $captchaStore->add(
            $this->sensitive,
            $this->sensitive ? $bag : $this->str->lower($bag),
            Carbon::now()->addMinutes($this->expirationTime)
        );

        $this->store->forever('captcha', $captchaStore);

        return $bag;
    }

    public function check($value)
    {
        $result = false;

        if ($this->store->has('captcha')) {
            $captchaStore = $this->store->get('captcha');

            $result = $captchaStore->check($value);

            $this->store->forever('captcha', $captchaStore);
        }

        return $result;
    }
}