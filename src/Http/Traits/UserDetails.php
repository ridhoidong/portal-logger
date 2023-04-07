<?php
namespace KejaksaanDev\PortalLogger\Http\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

trait UserDetails
{
    public static function getUser()
    {
        $fetchUser = [
            'userSatkerId' => '',
            'userNip' => '',
            'userUsername' => '',
            'userName' => ''
        ];
        try {
            $guards = explode(',', config('portal-logger.logger.user_guard'));
            foreach ($guards as $guard) {
                $fetchUser = self::fetchUser($guard);
                if (!empty($fetchUser['userUsername']))
                    break;
            }

            return $fetchUser;
        }
        catch(\Exception $ex) {
            return $fetchUser;
        }
    }

    public static function fetchUser($guard) {
        $result = [
            'userSatkerId' => '',
            'userNip' => '',
            'userUsername' => '',
            'userName' => ''
        ];
        try {
            if (Auth::guard($guard)->check()) {
                $result = self::retrieveUser($guard);
            }

            return $result;
        }
        catch(\Exception $ex) {
            return $result;
        }
    }

    public static function retrieveUser($guard)
    {
        $userData = [];
        try {
            $fieldUserId = config('portal-logger.logger.user_id');
            $user = Auth::guard($guard)->user();
            $userId = $user->{$fieldUserId} ?? '0';

            $key = "cache_user_{$guard}_{$userId}";
            $ttl = config('portal-logger.logger.cache_user');

            $userData = Cache::remember($key, $ttl, function() use ($user) {
                Log::info("fetch from cache");
                
                $fieldSatkerId = config('portal-logger.logger.user_satker_var');
                $fieldNip = config('portal-logger.logger.user_nip_var');
                $fieldUsername = config('portal-logger.logger.user_username_var');
                $fieldName = config('portal-logger.logger.user_name_var');

                $userSatkerId = self::nestedPropertyHandle($user, explode("->", $fieldSatkerId));
                $userNip = self::nestedPropertyHandle($user, explode("->", $fieldNip));
                $userUsername = self::nestedPropertyHandle($user, explode("->", $fieldUsername));
                $userName = self::nestedPropertyHandle($user, explode("->", $fieldName));

                return [
                    'userSatkerId' => $userSatkerId,
                    'userNip' => $userNip,
                    'userUsername' => $userUsername,
                    'userName' => $userName
                ];
            });

            return $userData; 
        }
        catch(\Exception $ex) {
            return $result = [
                'userSatkerId' => '',
                'userNip' => '',
                'userUsername' => '',
                'userName' => ''
            ];
        }
    }

    public static function nestedPropertyHandle($user, array $property)
    {
        $size = count($property);

        switch ($size) {
            case 1 :
                return $user->{$property[0]} ?? '';
                break;
            case 2 :
                return $user->{$property[0]}->{$property[1]} ?? '';
                break;
            case 3 :
                return $user->{$property[0]}->{$property[1]}->{$property[2]} ?? '';
                break;
            case 4 :
                return $user->{$property[0]}->{$property[1]}->{$property[2]}->{$property[3]} ?? '';
                break;
            case 5 :
                return $user->{$property[0]}->{$property[1]}->{$property[2]}->{$property[3]}->{$property[5]} ?? '';
                break;
            default :
                return $user->{$property[0]} ?? '';
                break; 
        }
    }
}
