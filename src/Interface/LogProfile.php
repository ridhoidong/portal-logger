<?php
namespace KejaksaanDev\PortalLogger\Interface;

use Illuminate\Http\Request;

interface LogProfile
{
    public function shouldLogRequest(Request $request): bool;
}
