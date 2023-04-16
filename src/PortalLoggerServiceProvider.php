<?php
namespace KejaksaanDev\PortalLogger;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use KejaksaanDev\PortalLogger\Interfaces\LogWriter;
use KejaksaanDev\PortalLogger\Logs\PortalLogWriter;
use KejaksaanDev\PortalLogger\Interfaces\LogProfile;
use KejaksaanDev\PortalLogger\Logs\PortalLogProfile;

class PortalLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    protected $listeners = [
        'Illuminate\Auth\Events\Login' => [
            'KejaksaanDev\PortalLogger\Listeners\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Failed' => [
            'KejaksaanDev\PortalLogger\Listeners\LogFailedLogin',
        ],
    ];

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
              __DIR__.'/config/portal-logger.php' => config_path('portal-logger.php'),
            ], 'portal-logger-config');
        }
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/portal-logger.php', 'portal-logger');

        $this->app->bind('portal-logger', function () {

            $url = config('portal-logger.logger.url');
            $timeout = config('portal-logger.logger.request_timeout');

            $client = new Client([
                'base_uri' => $url,
                'timeout'  => $timeout,
                'verify' => false
            ]);

            return new PortalLogger($client);
        });

        $this->app->singleton(LogProfile::class, config('portal-logger.http_logger.log_profile'));
        $this->app->singleton(LogWriter::class, config('portal-logger.http_logger.log_writer'));

        $this->registerEventListeners();
    }

    private function registerEventListeners()
    {
        foreach ($this->listeners as $listenerKey => $listenerValues) {
            foreach ($listenerValues as $listenerValue) {
                Event::listen(
                    $listenerKey,
                    $listenerValue
                );
            }
        }
    }
}