<?php

namespace App\Filament\Admin\Resources\Position\PositionResource\Pages;

use App\Filament\Admin\Resources\Position\PositionResource;
use App\Models\Position\PositionType;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPositions extends ListRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [null => Tab::make('All')];

        $positionTypes = PositionType::all(); // Vagy használj szűrést, ha szükséges

        foreach ($positionTypes as $positionType) {
            $tabs[$positionType->name] = Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('id', '=', $positionType->id));
        }

        return $tabs;
    }
}
