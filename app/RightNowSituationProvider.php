<?php

namespace App;

class RightNowSituationProvider
{
    public function getListener()
    {
        return NowSituation::class;
    }

    public function getSituationProvider()
    {
        return NowSituation::class;
    }

    public function getForgetKey()
    {
        return 'now';
    }

    public function getMethods()
    {
        return [
            'here',
        ];
    }

    public static function getForgetMethods()
    {
        return ['_'];
    }

    public static function getForgetArgs($method, $args)
    {
        return [$args];
    }
}