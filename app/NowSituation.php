<?php

namespace App;

use Imanghafoori\HeyMan\Contracts\HeymanSentinel;

class NowSituation implements HeymanSentinel
{
    public function normalize($method, $params)
    {
        return [['']];
    }

    public function startWatching($chainData)
    {
        foreach ($chainData as $callbacks) {
            foreach ($callbacks as $cb) {
                $cb[0]();
            }
        }
    }

}
