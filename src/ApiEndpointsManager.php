<?php

namespace AaPanelSDK;

class ApiEndpointsManager
{

    private static $endpoints = [
        'getSystemTotal' => '/system?action=GetSystemTotal',
        'getDiskInfo' => '/system?action=GetDiskInfo',
        'getNetwork' => '/system?action=GetNetWork',
        'checkUpdate' => '/ajax?action=UpdatePanel',
        'getSites' => '/data?action=getData&table=sites',
        'createSite' => '/site?action=AddSite',
        'deleteSite' => '/site?action=DeleteSite',
    ];

    public static function getURL($key)
    {
        if (array_key_exists($key, self::$endpoints)) {
            return self::$endpoints[$key];
        }

        throw new \InvalidArgumentException("The endpoint key '{$key}' does not exist.");
    }
}
