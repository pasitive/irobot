<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 25.02.11
 */

class Signature {

    public static function generate($params) {

        ksort($params);

        $signature = '';
        unset($params['sig']);

        foreach ($params as $index => $param) {
            $signature .= $index . '=' . $param;
        }
        $signature = md5($signature . Yii::app()->params['secret']);
        return $signature;
    }

    public static function check($sig, $params) {
        $serverSign = self::generate($params);
        return ($serverSign === $sig);
    }

}
