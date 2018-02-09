<?php

namespace jocoonopa\LINEBot;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use jocoonopa\LINEBot\LINEBot as JaLINEBot;

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
            __DIR__.'/../config/linebot.php' => config_path('linebot.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfigurations();

        $this->app->bind(HTTPClient::class, function($app) {
            return new CurlHTTPClient(config('linebot.channel_access_token'));
        });

        $this->app->bind(LINEBot::class, function($app) {
            return new LINEBot(
                $app->make(HTTPClient::class), 

                [
                    'channelSecret' => config('linebot.channel_secret'),
                ]
            );
        });

        $this->app->singleton('linebot', function ($app) {
            return new JaLINEBot(
                $app->make(LINEBot::class)
            );
        });
    }

    public function provides()
    {
        return [
            'linebot',
        ];
    }

    /**
     * Register the package configurations
     * @return void
     */
    protected function registerConfigurations()
    {
        $this->mergeConfigFrom(
            $this->packagePath('config/linebot.php'), 'linebot'
        );
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