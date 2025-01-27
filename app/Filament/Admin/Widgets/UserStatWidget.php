<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected ?string $heading = 'Felhasználói statisztikák';

    protected int | string | array $columnSpan = 1;

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $all = User::count();
        $ma = User::whereDate('created_at', now())->count();
        $heten = User::whereDate('created_at', '>=', now()->addWeek(-1))->count();

        return [
            Stat::make('Felhasználók száma', User::count())
                ->description(round($ma / $all * 100, 1) . '% növekedés')
                ->descriptionIcon($ma > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down'),

            Stat::make('A héten regisztráltak', $heten)
                ->description(round($heten / $all * 100, 1) . '% növekedés')
                ->descriptionIcon($heten > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down'),
        ];
    }
}
