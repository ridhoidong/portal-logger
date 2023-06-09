<?php
namespace KejaksaanDev\PortalLogger\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use KejaksaanDev\PortalLogger\Interfaces\LogWriter;
use KejaksaanDev\PortalLogger\Interfaces\LogProfile;

class PortalLogger
{
    protected $logProfile;

    protected $logWriter;

    public function __construct(LogProfile $logProfile, LogWriter $logWriter)
    {
        $this->logProfile = $logProfile;
        $this->logWriter = $logWriter;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->logProfile->shouldLogRequest($request)) {
            $this->logWriter->logRequest($request);
        }

        return $next($request);
    }
}
