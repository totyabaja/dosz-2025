<?php

namespace App\Filament\ModifiedResources;

use Z3d0X\FilamentLogger\Resources\ActivityResource as BaseActivityResource;

class ActivityResource extends BaseActivityResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.activities');
    }
}
