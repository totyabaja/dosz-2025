<?php

namespace App\Filament\Components\Event;

use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Infolists\Components\{Select, TextEntry, Section, Fieldset, Grid, IconEntry, Placeholder, Toggle};
use Illuminate\Support\Facades\Auth;

class BillingInfolist
{
    public static function schema(): array
    {
        return [
            Fieldset::make('s')
                ->label(__('resource.tabs.billing_address'))
                ->columns(6)
                ->schema([
                    IconEntry::make('event_invoice_address.personal_or_industrial')
                        ->label(__('resource.components.personal_or_industrial'))
                        ->boolean()
                        ->inlineLabel()
                        ->columnSpan([
                            'default' => 1,
                            'md' => 3,
                        ]),
                    TextEntry::make('event_invoice_address.tax_number')
                        ->label(__('resource.components.tax_number'))
                        ->inlineLabel()
                        ->columnSpan([
                            'default' => 1,
                            'md' => 3,
                        ])
                        ->hidden(fn($record) => !($record->event_invoice_address['personal_or_industrial'] ?? false)),

                    TextEntry::make('event_invoice_address.billing_name')
                        ->label(__('resource.components.billing_name'))
                        ->inlineLabel()
                        ->columnSpanFull(),

                    TextEntry::make('event_invoice_address.address_zip')
                        ->label(__('resource.components.zip'))
                        ->columnSpan([
                            'default' => 1,
                            'md' => 1,
                        ]),
                    TextEntry::make('event_invoice_address.country')
                        ->label(__('resource.components.country'))
                        ->columnSpan([
                            'default' => 1,
                            'md' => 2,
                        ]),
                    TextEntry::make('event_invoice_address.address_city')
                        ->label(__('resource.components.city'))
                        ->columnSpan([
                            'default' => 1,
                            'md' => 3,
                        ]),
                    TextEntry::make('event_invoice_address.address_address')
                        ->label('address')
                        ->columnSpanFull(),

                ]),
        ];
    }
}
