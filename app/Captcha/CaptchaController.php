<?php

namespace App\Captcha;

use Illuminate\Routing\Controller;

class CaptchaController extends Controller
{

    /**
     * get CAPTCHA
     *
     * @param \App\Captcha\Captcha $captcha
     * @param string $config
     * @return \Intervention\Image\ImageManager->response
     */
    public function getCaptcha(Captcha $captcha, $config = 'default')
    {
        return $captcha->create($config);
    }

}
