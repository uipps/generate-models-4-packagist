<?php

/**
 * Created by Cristian.
 * Date: 12/10/16 12:30 AM.
 */

namespace Uipps\GenerateModels4Packagist\Database\Eloquent;

trait BlamableBehavior
{
    /**
     * Boot Blamable Behaviour trait for a model.
     */
    public static function bootBlamableBehavior()
    {
        static::observe(WhoDidIt::class);
    }
}
