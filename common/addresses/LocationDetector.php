<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/26/2015
 * Time: 2:15 AM
 */

namespace qeywork;


class LocationDetector {
    public function getCurrentLocation($server) {
        $baseUrl = new Url(array(q\Url::getCurrentDomain($server), self::APP_NAME));
        $basePath = new Path(__DIR__);
    }
}