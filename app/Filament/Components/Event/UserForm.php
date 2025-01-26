<?php

namespace App\Filament\Components\Event;

use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Forms\Components\{Select, TextInput, Section, Fieldset, Grid, Placeholder};
use Illuminate\Support\Facades\Auth;

class UserForm
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
                    TextInput::make('regisztralo_name')
                        ->readOnly()
                        ->label('Teljes név'),

                    TextInput::make('notification_email')
                        ->readOnly()
                        ->label('Értesítési e-mail cím'),
                ]),

            Fieldset::make()
                ->label('Intézményi adatok')
                ->disabled()
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])->schema([
                        Select::make('universities')
                            ->label(__('University'))
                            ->options(fn() => University::pluck('full_name', 'id')),

                        Select::make('doctoral_school_id')
                            ->label(__('Doctoral School'))
                            ->options(function (callable $get) {
                                $universityId = $get('universities'); // Kiválasztott egyetem ID-je
                                if (! $universityId) {
                                    return DoctoralSchool::pluck('full_name', 'id'); // Alapértelmezett lista, ha nincs szűrés
                                }

                                // Szűkített lista az adott egyetem alapján
                                return DoctoralSchool::where('university_id', $universityId)->pluck('full_name', 'id');
                            })
                            ->live()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // A doktori iskola kiválasztása után frissítjük az egyetemet
                                $universityId = DoctoralSchool::find($state)?->university_id;
                                $set('universities', $universityId);
                            })
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $universityId = DoctoralSchool::find($state)?->university_id;
                                $set('universities', $universityId);
                            }),

                        Placeholder::make('scientific_department_member')
                            ->content(fn(): string => Auth::user()->scientific_department_member ? __('igen') : __('nem'))
                            ->inlineLabel()
                            ->columnSpanFull(),

                    ]),
                ]),
        ];
    }
}
