<?php

namespace App\Filament\Resources;

use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;
use Z3d0X\FilamentLogger\Resources\ActivityResource as BaseActivityResource;
use Illuminate\Support\Str;

class ActivityResource extends BaseActivityResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.activities');
    }
}
