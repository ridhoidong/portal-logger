<?php
namespace KejaksaanDev\PortalLogger\Interfaces;

use Illuminate\Http\Request;

interface LogWriter
{
    public function logRequest(Request $request);
}
