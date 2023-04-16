<?php
namespace KejaksaanDev\PortalLogger\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;
use KejaksaanDev\PortalLogger\Http\Traits\WriterLogger;

class LogFailedLogin
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
     * @param Failed $event
     *
     * @return void
     */
    public function handle(Failed $event)
    {
        try {
            if (isset($event->credentials['username'])) {
                $username = $event->credentials['username'];
            } else if (isset($event->credentials['email'])) {
                $username = $event->credentials['email'];
            } else {
                $username = null;
            }

            WriterLogger::writeLog(request(), '401', 'error', $username);
        }
        catch (\Exception $ex) {
            Log::error("Failed log login failed cause : {$ex->getMessage()}");
        }
    }
}
