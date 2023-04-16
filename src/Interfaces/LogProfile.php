<?php
namespace KejaksaanDev\PortalLogger\Interfaces;

use Illuminate\Http\Request;

interface LogProfile
{
    public function shouldLogRequest(Request $request): bool;
}
