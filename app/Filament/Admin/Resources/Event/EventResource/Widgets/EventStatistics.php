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

    public ?string $filter = 'reg-num';

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

    protected function getFilters(): ?array
    {
        return [
            'reg-num' => 'Regisztráltak',
            ...collect($this->record->reg_form->custom_form->content)
                ->mapWithKeys(fn($item) => [$item['data']['id'] => $item['data']['title']])
                ->toArray()
        ];
    }

    protected function getConfig(): array
    {
        return [
            'type' => $this->getType(), // Így biztosítod a dinamikus frissítést
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $labels = [];
        $data = [];
        $label = '';

        switch ($activeFilter) {
            case 'reg-num':
                $label = "Regisztrálók száma";
                $registrations = $this->record->event_registrations()
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                $labels = $registrations->pluck('date')->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))->toArray();
                $data = $registrations->pluck('count')->toArray();
                break;

            default:
                $values = $this->record->reg_form->custom_form->event_form_responses;

                $aggregatedData = $values
                    ->map(function ($response) use ($activeFilter) {
                        $decodedResponses = $response->responses;
                        return $decodedResponses[$activeFilter] ?? null;
                    })
                    ->filter() // Üres (null) értékek kiszűrése
                    ->countBy()
                    ->toArray();

                //dd($aggregatedData);

                $label = $activeFilter; // TODO, a title kell, nem az id
                $labels = array_keys($aggregatedData);
                $data = array_values($aggregatedData);
                break;
        }

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
        $activeFilter = $this->filter;

        switch ($activeFilter) {
            case 'reg-num':
                return 'line';
            default:
                return 'pie';
        }
    }
}
