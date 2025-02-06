<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Widgets;

use App\Models\Event\Event;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Widgets\ChartWidget;

class EventRegFormStatistics extends ChartWidget
{

    protected static ?string $heading = 'Chart';

    protected static ?string $pollingInterval = '10s';

    protected static ?string $maxHeight = '500px';

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = true;

    public ?Event $record;

    public ?string $filter;

    protected static ?array $options = [
        'indexAxis' => 'y',
    ];

    protected function getFilters(): ?array
    {
        $array = collect($this->record->reg_form->content)
            ->filter(fn($item) => $item['type'] != 'text_input')
            ->mapWithKeys(fn($item) => [$item['data']['id'] => $item['data']['title']])
            ->toArray();
        //dd($array);
        return $array;
    }

    protected function getFirstFilterKey()
    {
        return array_key_first(self::getFilters());
    }
    protected function getFilterLabel()
    {
        return self::getFilters()[$this->filter ?? self::getFirstFilterKey()];
    }


    protected function getData(): array
    {
        $activeFilter = $this->filter ?? self::getFirstFilterKey();
        $labels = [];
        $data = [];
        $label = '';

        $values = $this->record->event_registrations;

        $aggregatedData = $values
            ->map(function ($response) use ($activeFilter) {
                $decodedResponses = $response->reg_form_response;
                //dd($this->filter, $decodedResponses);
                return $decodedResponses[$activeFilter] ?? null;
            })
            ->filter() // Üres (null) értékek kiszűrése
            ->countBy()
            ->toArray();


        //dd($aggregatedData);

        $label = self::getFilterLabel(); // TODO, a title kell, nem az id
        $labels = array_keys($aggregatedData);
        $data = array_values($aggregatedData);

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
        return 'bar';
    }
}
