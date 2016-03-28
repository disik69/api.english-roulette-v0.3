<?php

namespace App\YandexDictionary;

use Illuminate\Support\ServiceProvider;

class YandexDictionaryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('yandex_dictionary', function ($app) {
            $config = $app['config']->get('yandex_dictionary');

            $dictionaryClient = new \Yandex\Dictionary\DictionaryClient($config['api_key']);
            $dictionaryClient->setTranslateFrom($config['translate_from']);
            $dictionaryClient->setTranslateTo($config['translate_to']);

            return $dictionaryClient;
        });
    }
}
