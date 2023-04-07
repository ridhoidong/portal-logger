<?php
namespace KejaksaanDev\PortalLogger\Interface;

use Illuminate\Http\Request;

interface LogWriter
{
    public function logRequest(Request $request);
}
