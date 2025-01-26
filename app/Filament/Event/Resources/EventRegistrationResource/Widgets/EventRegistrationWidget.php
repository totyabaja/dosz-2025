<?php

namespace App\Filament\Event\Resources\EventRegistrationResource\Widgets;

use App\Models\Event\Event;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class EventRegistrationWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::regIsActive()
            )
            ->columns([
                Tables\Columns\Layout\Grid::make()
                    ->columns(1)
                    ->schema([
                        tables\Columns\Layout\Split::make([
                            Tables\Columns\Layout\Grid::make()
                                ->columns(1)
                                ->schema([
                                    SpatieMediaLibraryImageColumn::make('media')
                                        ->collection('event-images')
                                        ->height(150)
                                        ->wrap()
                                        ->extraImgAttributes([
                                            'class' => 'rounded-md',
                                        ]),
                                ])
                                ->grow(false),
                            Tables\Columns\Layout\Stack::make([
                                Tables\Columns\TextColumn::make('eventName')
                                    ->weight(FontWeight::Medium),
                                Tables\Columns\TextColumn::make('event_start_date'),
                                Tables\Columns\TextColumn::make('reg')
                                    ->default(fn($record) => new HtmlString(
                                        Blade::render(
                                            '<x-filament::button
                                                href="' . route('filament.event.resources.event-registrations.create', [$record->slug]) . '"
                                                tag="a"
                                            >
                                                ' . __(mb_ucfirst(__('registration'))) . '
                                            </x-filament::button>'
                                        )
                                    )),
                            ])
                                ->extraAttributes([
                                    'class' => 'space-y-2',
                                ])
                                ->grow(),
                        ]),
                    ]),
            ])
            ->contentGrid([
                'default' => 1,
                'md' => 2,
            ])
            ->recordUrl(false)
            ->paginationPageOptions([4, 10, 20]);
    }
}
