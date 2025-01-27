<?php

namespace App\Filament\Admin\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Spatie\Activitylog\Models\Activity;

class UserActivityStatWidget extends ChartWidget
{
    protected static ?string $heading = 'Felhasználói aktivitások';

    protected int | string | array $columnSpan = 1;

    public ?string $filter = 'day';

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Elmúlt 24 óra',
            'week' => 'Elmúlt egy hét',
            'month' => 'Elmúlt egy hónap',
            'year' => 'Elmúlt egy év',
            'all' => 'Összes',
        ];
    }

    protected function applyFilters($query): void
    {
        $filter = $this->filter;

        if ($filter === 'day') {
            $query->where('created_at', '>=', Carbon::now()->subDay());
        } elseif ($filter === 'week') {
            $query->where('created_at', '>=', Carbon::now()->subWeek());
        } elseif ($filter === 'month') {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        } elseif ($filter === 'year') {
            $query->where('created_at', '>=', Carbon::now()->subYear());
        }
    }

    protected function getData(): array
    {
        $filter = $this->filter;
        $activitiesQuery = Activity::query();

        if ($filter === 'day') {
            // Ha az elmúlt 24 óra van kiválasztva, órás bontás kell
            $activitiesQuery->selectRaw('HOUR(created_at) as time_unit, event, COUNT(*) as count')
                ->groupBy('time_unit', 'event')
                ->orderBy('time_unit');
        } else {
            // Egyéb esetekben napi bontás
            $activitiesQuery->selectRaw('DATE(created_at) as time_unit, event, COUNT(*) as count')
                ->groupBy('time_unit', 'event')
                ->orderBy('time_unit');
        }

        // Szűrés alkalmazása
        $this->applyFilters($activitiesQuery);

        $activities = $activitiesQuery->get();

        $groupedData = [];
        $labels = [];

        foreach ($activities as $activity) {
            if ($filter === 'day') {
                $timeUnit = str_pad($activity->time_unit, 2, '0', STR_PAD_LEFT) . ':00'; // Pl. "08:00"
            } else {
                $timeUnit = Carbon::parse($activity->time_unit)->format('Y-m-d');
            }

            $event = $activity->event;
            $labels[$timeUnit] = $timeUnit;
            $groupedData[$event][$timeUnit] = $activity->count;
        }

        $datasets = [];
        $colors = ['red', 'blue', 'green', 'orange', 'purple'];
        $eventNames = array_keys($groupedData);

        foreach ($eventNames as $index => $event) {
            $datasets[] = [
                'label' => $event,
                'data' => array_map(fn($time) => $groupedData[$event][$time] ?? 0, array_values($labels)),
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => 'rgba(0, 0, 0, 0)',
            ];
        }

        return [
            'labels' => array_values($labels),
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
