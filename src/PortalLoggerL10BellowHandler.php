<?php
namespace KejaksaanDev\PortalLogger;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class PortalLoggerL10BellowHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        $level = strtolower(Logger::getLevelName($record['level']));

        PortalLogger::log((array)json_decode($record['message']), $level)->send();
    }
}