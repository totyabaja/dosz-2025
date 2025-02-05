<?php

namespace App\Filament\ModifiedResources;

use BezhanSalleh\FilamentExceptions\Resources\ExceptionResource as BaseExceptionResource;


class ExceptionResource extends BaseExceptionResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.activities');
    }
}
