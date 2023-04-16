<?php
namespace KejaksaanDev\PortalLogger\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use KejaksaanDev\PortalLogger\Http\Traits\WriterLogger;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Login $event
     *
     * @return void
     */
    public function handle(Login $event)
    {
        try {
            WriterLogger::writeLog(request(), '200', 'info');
        }
        catch (\Exception $ex) {
            Log::error("Failed log login success cause : {$ex->getMessage()}");
        }
    }
}
