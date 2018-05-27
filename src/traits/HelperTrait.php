<?php

namespace DevsWebDev\DevTube\Traits;

trait HelperTrait
{
    public function asc_by_quality($val_a, $val_b)
    {
        $a = $val_a['pref'];
        $b = $val_b['pref'];
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : +1;
    }

    public function desc_by_quality($val_a, $val_b)
    {
        $a = $val_a['pref'];
        $b = $val_b['pref'];
        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? -1 : +1;
    }
}
