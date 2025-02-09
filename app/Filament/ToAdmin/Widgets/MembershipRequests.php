<?php

namespace App\Filament\ToAdmin\Widgets;

use App\Models\Scientific\ScientificDepartmentUser;
use App\Notifications\MembershipRequestAccepted;
use App\Notifications\MembershipRequestRejected;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MembershipRequests extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ScientificDepartmentUser::query()
                ->where('accepted', false)
                ->with(['user', 'scientific_department']))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Felhasználó')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('scientific_department.filament_name')
                    ->label('Tudományos osztály')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('request_datetime')
                    ->label('Kérelem dátuma')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label('Elfogadás')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(function (ScientificDepartmentUser $record) {
                        $record->update([
                            'accepted' => true,
                            'acceptance_datetime' => now(),
                        ]);

                        $record->user->notify(new MembershipRequestAccepted($record->scientific_department->filament_name));

                        Notification::make()
                            ->title('Kérelem elfogadva')
                            ->body("A(z) {$record->user->name} tagsági kérelme a(z) {$record->scientific_department->filament_name} osztályba elfogadva.")
                            ->success()
                            ->send();
                    }),

                // Elutasítás akció
                Tables\Actions\Action::make('reject')
                    ->label('Elutasítás')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(function (ScientificDepartmentUser $record) {
                        $departmentName = $record->scientific_department->filament_name;
                        $record->delete();

                        $record->user->notify(new MembershipRequestRejected($departmentName));

                        Notification::make()
                            ->title('Kérelem elutasítva')
                            ->body("A(z) {$record->user->name} tagsági kérelme a(z) {$record->scientific_department->filament_name} osztályba elutasítva.")
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}
