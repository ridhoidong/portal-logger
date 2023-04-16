<?php
namespace KejaksaanDev\PortalLogger\Logs;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use KejaksaanDev\PortalLogger\Interfaces\LogWriter;
use KejaksaanDev\PortalLogger\Http\Traits\UserDetails;
use KejaksaanDev\PortalLogger\Http\Traits\WriterLogger;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use KejaksaanDev\PortalLogger\Http\Traits\IpAddressDetails;
use KejaksaanDev\PortalLogger\Http\Traits\UserAgentDetails;

class PortalLogWriter implements LogWriter
{
    use WriterLogger;
    
    public function logRequest(Request $request)
    {
        try {
            $statusCode = config('portal-logger.logger.default_status_code');
            $level = config('portal-logger.http_logger.level');

            WriterLogger::writeLog($request, $statusCode, $level);
        }
        catch (\Exception $ex) {
            Log::error("Failed log request cause : {$ex->getMessage()}");
        }
    }
}
