<?php

namespace App\Filament\Components\Event;

use App\Models\Event\EventRegistration;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Infolists\Components\{Select, Section, Fieldset, Grid, Placeholder, TextEntry};
use Illuminate\Support\Facades\Auth;

class UserInfolist
{
    public static function schema(): array
    {
        return [
            Section::make(__('Regisztráló adatai'))
                ->description('Az itt szereplő adatok a Profilomon fülön módosíthatóak.')
                ->aside()
                ->columns([
                    'default' => 1,
                    'lg' => 2,
                ])
                ->schema([
                    TextEntry::make('user.name')
                        ->label('Teljes név'),

                    TextEntry::make('notification_email')
                        ->label('Értesítési e-mail cím'),

                    TextEntry::make('doctoral_school.filament_full_name')
                        ->label('Doktori iskola'),

                    TextEntry::make('scientific_department_member')
                        ->getStateUsing(fn($record): string => $record->user->scientific_department_member ? __('igen') : __('nem'))
                        ->inlineLabel()
                        ->columnSpanFull(),

                ]),

        ];
    }
}
