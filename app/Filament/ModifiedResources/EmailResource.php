<?php

namespace App\Filament\ModifiedResources;

use RickDBCN\FilamentEmail\Filament\Resources\EmailResource as BaseEmailResource;

class EmailResource extends BaseEmailResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.activities');
    }
}
