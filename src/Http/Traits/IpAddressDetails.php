<?php
namespace KejaksaanDev\PortalLogger\Http\Traits;

use Illuminate\Support\Facades\Request;

trait IpAddressDetails
{
    public static function getIP()
    {
        try {
            if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = Request::ip();
            }
    
            return $ip;
        }
        catch(\Exception $ex) {
            return '';
        }
    }
}
