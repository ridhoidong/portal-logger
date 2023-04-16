<?php
namespace KejaksaanDev\PortalLogger\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use KejaksaanDev\PortalLogger\Http\Traits\UserDetails;
use KejaksaanDev\PortalLogger\Http\Traits\IpAddressDetails;
use KejaksaanDev\PortalLogger\Http\Traits\UserAgentDetails;

class WriterLogger
{
    public static function writeLog(Request $request, $statusCode = null, $level = null, $username = null, $exceptionContext = null, $description = null)
    {
        try {
            $sessionId = session()->getId();
            if ($request->route() != null) {
                $actionName = $request->route()->getActionName();
            } else {
                $actionName = 'Closure';
            }
            $method = strtoupper($request->getMethod());
            $uri = $request->path();
            $feature = self::getFeatureName($actionName, $uri, $method);
            $featureName = $feature['featureName'] ?? '';
            $featureActionName = $feature['featureActionName'] ?? '';
            $featureActionContext = $feature['featureActionContext'] ?? '';
            $ip = IpAddressDetails::getIP();
            $agent = UserAgentDetails::details();
            $agentFormatted = "{$agent['platform']};{$agent['type']};{$agent['renderer']};{$agent['browser']};{$agent['version']}";
            $user = UserDetails::getUser();
    
            $message = [
                'appId' => config('portal-logger.logger.app_id'),
                'sessionId' => $sessionId,
                'featureName' => $featureName,
                'featureActionName' => $featureActionName,
                'featureActionContext' => $featureActionContext,
                'exceptionContext' => $exceptionContext,
                'description' => $description,
                'method' => $method,
                'url' => $uri,
                'agent' => $agentFormatted,
                'ip' => $ip,
                'userType' => empty($user['userUsername']) ? 'GUEST' : 'REGISTERED',
                'userSatkerId' => $user['userSatkerId'],
                'userNip' => $user['userNip'],
                'userUsername' => empty($username) ? $user['userUsername'] : $username,
                'userName' => $user['userName'],
                'statusCode' => $statusCode,
                'level' => $level
            ];
    
            Log::channel(config('portal-logger.http_logger.channel'))->log($level, json_encode($message));
        }
        catch (\Exception $ex) {
            $message = [
                'appId' => config('portal-logger.logger.app_id'),
                'sessionId' => session()->getId(),
                'featureName' => null,
                'description' => "{$ex->getMessage()} -> lines {$ex->getLine()}",
                'statusCode' => '501',
                'level' => 'error'
            ];

            Log::channel(config('portal-logger.http_logger.channel'))->log('error', json_encode($message));
        }
    }

    public static function getFeatureName($actionName, $path, $method)
    {
        try {
            $explode = explode('@', class_basename($actionName));
            $result = [];
            if (count($explode) > 1) {
                $result = [
                    'featureName' => $explode[0],
                    'featureActionName' => strtolower(self::getVerb($method)." ({$explode[1]}) {$path}"),
                    'featureActionContext' => $actionName
                ];
            } else {
                $result = [
                    'featureName' => rtrim(array_pop(explode('/',$path)), '/'),
                    'featureActionName' => strtolower(self::getVerb($method).' '.$path),
                    'featureActionContext' => $actionName
                ];
            }
    
            return $result;
        }
        catch(\Exception $ex) {
            return [
                'featureName' => $actionName,
                'featureActionName' => strtolower(self::getVerb($method).' '.$path),
                'featureActionContext' => $actionName
            ];
        }
    }

    public static function getVerb($method) {
        switch (strtolower($method)) {
            case 'post':
                $verb = 'Created';
                break;

            case 'patch':
            case 'put':
                $verb = 'Edited';
                break;

            case 'delete':
                $verb = 'Deleted';
                break;

            case 'get':
            default:
                $verb = 'Viewed';
                break;
        }

        return $verb;
    }
}

