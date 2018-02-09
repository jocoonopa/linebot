<?php

namespace jocoonopa\LINEBot;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINEBot as JaLINEBot;

class LINEBotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/linebot.php' => config_path('linebot.php'),
        ]);

        $this->app->bind(HTTPClient::class, function($app) {
            return new CurlHTTPClient(config('jocoonopa.channel_access_token'));
        });

        $this->app->bind(LINEBot::class, function($app) {
            return new LINEBot(
                $app->make(HTTPClient::class), 

                [
                    'channelSecret' => config('jocoonopa.channel_secret'),
                ]
            );
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JaLINEBot::class, function ($app) {
            return $app->make(JaLINEBot::class);
        });

        $this->app->alias(JaLINEBot::class, 'linebot');
    }

    /**
     * Register the package configurations
     * @return void
     */
    protected function registerConfigurations()
    {
        $this->mergeConfigFrom(
            $this->packagePath('config/linebot.php'), 'jocoonopa.linebot'
        );

        $this->publishes([
            $this->packagePath('config/linebot.php') => config_path('jocoonopa/linebot.php'),
        ], 'config');
    }

    public function provides()
    {
        return ['linebot'];
    }

    /**
     * Loads a path relative to the package base directory
     * @param string $path
     * @return string
     */
    protected function packagePath($path = '')
    {
        return sprintf("%s/../%s", __DIR__, $path);
    }
}