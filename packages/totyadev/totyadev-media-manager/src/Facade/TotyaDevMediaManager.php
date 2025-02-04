<?php

namespace TotyaDev\TotyaDevMediaManager\Facade;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static void register(MediaManagerType|array $type)
 * @method static array getTypes()
 */
class TotyaDevMediaManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'totyadev-media-manager';
    }
}
