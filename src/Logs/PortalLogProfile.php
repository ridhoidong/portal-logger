<?php
namespace KejaksaanDev\PortalLogger\Logs;

use Illuminate\Http\Request;
use KejaksaanDev\PortalLogger\Interfaces\LogProfile;

class PortalLogProfile implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        $checkMethod = in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete', 'get']);
        $checkIsAjax = $request->ajax();
        $configIsAjax = config('portal-logger.logger.is_ajax_log');
        $isEnable = config('portal-logger.logger.is_enable');

        return $isEnable && $checkMethod && (($checkIsAjax && $configIsAjax) || (!$checkIsAjax && !$configIsAjax));
    }
}
