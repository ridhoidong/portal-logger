<?php
namespace KejaksaanDev\PortalLogger;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;

class PortalLoggerHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        $level = strtolower(Logger::getLevelName($record['level']));

        PortalLogger::log((array)json_decode($record['message']), $level)->send();
    }
}