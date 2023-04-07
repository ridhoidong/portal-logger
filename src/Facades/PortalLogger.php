<?php

namespace KejaksaanDev\PortalLogger\Facades;

use Illuminate\Support\Facades\Facade;

class PortalLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'portal-logger';
    }
}