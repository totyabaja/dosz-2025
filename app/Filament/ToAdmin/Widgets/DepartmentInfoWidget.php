<?php

namespace App\Filament\ToAdmin\Widgets;

use Filament\Widgets\Widget;

class DepartmentInfoWidget extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    protected static string $view = 'filament.to-admin.widgets.department-info-widget';
}
