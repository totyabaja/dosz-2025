<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Widgets;

use App\Models\Event\Event;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Widgets\ChartWidget;

class EventStatistics extends ChartWidget
{

    protected static ?string $heading = 'Chart';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = true;

    public ?Event $record;

    protected static ?array $options = [
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'stepSize' => 1,
                    'min' => 0,
                ],
            ],
        ],
    ];

    protected function getData(): array
    {
        $labels = [];
        $data = [];
        $label = '';

        $label = "Regisztrálók száma";
        $registrations = $this->record->event_registrations()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $registrations->pluck('date')->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))->toArray();
        $data = $registrations->pluck('count')->toArray();



        return [
            'datasets' => [
                [
                    'label' => $label,
                    'data' => $data,
                    'borderColor' => 'blue',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
            ],
            'labels' => $labels,

        ];
    }

    protected function getType(): string
    {
        return $this->filter = 'line';
    }
}
