<?php

namespace App\Filament\Event\Resources\EventRegistrationResource\Widgets;

use App\Filament\Event\Resources\EventRegistrationResource;
use App\Models\Event\EventRegistration;
use Filament\Tables\Actions\Concerns\InteractsWithRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class EventRegistrationTable extends TableWidget
{
    use InteractsWithRecords;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -1;

    //protected static string $view = 'filament.event.resources.event-registration-resource.widgets.event-registration-table';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                EventRegistration::query()
                    ->where('user_id', Auth::id()) // aktív rendezvényhez kötött regisztrációk
            )
            ->columns([
                Tables\Columns\TextColumn::make('event.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => EventRegistrationResource::getUrl('view', ['record' => $record->id])),
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => EventRegistrationResource::getUrl('edit', ['record' => $record->id]))
                    ->visible(fn($record): bool => $record->event->event_registration_editable),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
