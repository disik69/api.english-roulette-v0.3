<?php

namespace App\YandexDictionary;

class YaDictionary extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'yandex_dictionary';
    }
}