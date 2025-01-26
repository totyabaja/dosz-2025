<?php

namespace App\Filament\Components\Event;

use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Forms\Components\{Select, TextInput, Section, Fieldset, Grid, Placeholder, Toggle};
use Illuminate\Support\Facades\Auth;

class BillingForm
{
    public static function schema(): array
    {
        return [
            Fieldset::make()
                ->label(__('resource.tabs.billing_address'))
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 6,
                    ])->schema([

                        Toggle::make('event_invoice_address.personal_or_industrial')
                            ->label(__('resource.components.personal_or_industrial'))
                            ->columnSpan([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->live()
                            ->required(),
                        TextInput::make('event_invoice_address.tax_number')
                            ->label(__('resource.components.tax_number'))
                            ->inlineLabel()
                            ->columnSpan([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->mask('99999999-9-99')
                            ->placeholder('12345678-1-23')
                            ->required(fn($get) => $get('event_invoice_address')['personal_or_industrial'])
                            ->disabled(fn($get) => ! $get('event_invoice_address')['personal_or_industrial']),

                        TextInput::make('event_invoice_address.billing_name')
                            ->label(__('resource.components.billing_name'))
                            ->inlineLabel()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('event_invoice_address.zip')
                            ->label(__('resource.components.zip'))
                            ->columnSpan([
                                'default' => 1,
                                'md' => 1,
                            ])
                            ->required(),
                        TextInput::make('event_invoice_address.country')
                            ->label(__('resource.components.country'))
                            ->datalist([
                                'Magyarország',
                            ])
                            ->columnSpan([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->default('Magyarország')
                            ->required(),
                        TextInput::make('event_invoice_address.city')
                            ->label(__('resource.components.city'))
                            ->columnSpan([
                                'default' => 1,
                                'md' => 3,
                            ])
                            ->required(),
                        TextInput::make('event_invoice_address.address')
                            ->label('address')
                            ->columnSpanFull()
                            ->required(),

                    ]),
                ]),
        ];
    }
}
