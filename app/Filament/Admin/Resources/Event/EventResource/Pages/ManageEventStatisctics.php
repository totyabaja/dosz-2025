<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use App\Filament\Admin\Resources\Event\EventResource\Widgets\EventStatistics;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ManageEventStatisctics extends Page
{
    use InteractsWithRecord;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.admin.resources.event.event-resource.pages.manage-event-statisctics';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EventStatistics::make([
                'record' => $this->record,
            ])
        ];
    }
}
