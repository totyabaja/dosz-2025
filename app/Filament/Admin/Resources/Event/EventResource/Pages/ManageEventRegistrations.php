<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use App\Filament\Admin\Resources\UserResource;
use App\Filament\Components\Event\CustomFormInfolist;
use App\Filament\Components\Event\PublicationForm;
use App\Filament\Components\Event\PublicationInfolist;
use Filament\Actions;
use Filament\Infolists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageEventRegistrations extends ManageRelatedRecords
{
    protected static string $resource = EventResource::class;

    protected static string $relationship = 'event_registrations';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return 'Event Registrations';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make()
                    ->schema([
                        Infolists\Components\Tabs\Tab::make('Regisztráló adatai')
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label(__('resource.components.name'))
                                    ->inlineLabel(),
                                Infolists\Components\TextEntry::make('user.email')
                                    ->label(__('resource.components.email'))
                                    ->inlineLabel()
                                    ->copyable(true),
                                Infolists\Components\TextEntry::make('user.phone')
                                    ->label(__('resource.components.phone'))
                                    ->inlineLabel(),
                                Infolists\Components\TextEntry::make('user.doctoral_school.filament_full_name')
                                    ->label(__('resource.components.doctoral_school'))
                                    ->columnStart(1),
                                Infolists\Components\TextEntry::make('user.doctoral_school.university.filament_full_name')
                                    ->label(__('resource.components.university'))
                                    ->columnStart(1),
                            ]),
                        Infolists\Components\Tabs\Tab::make('Számlázási adatok')
                            ->columns([
                                'default' => 1,
                                'md' => 6,
                            ])
                            ->schema([
                                Infolists\Components\TextEntry::make('event_invoice_address.personal_or_industrial')
                                    ->label(__('resource.components.personal_or_industrial'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                    ]),
                                Infolists\Components\TextEntry::make('event_invoice_address.tax_number')
                                    ->label(__('resource.components.tax_number'))
                                    ->inlineLabel()
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                    ]),
                                Infolists\Components\TextEntry::make('event_invoice_address.billing_name')
                                    ->label(__('resource.components.billing_name'))
                                    ->inlineLabel()
                                    ->columnSpanFull(),

                                Infolists\Components\TextEntry::make('event_invoice_address.address_zip')
                                    ->label(__('resource.components.zip'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                    ]),
                                Infolists\Components\TextEntry::make('event_invoice_address.country')
                                    ->label(__('resource.components.country'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                    ]),
                                Infolists\Components\TextEntry::make('event_invoice_address.address_city')
                                    ->label(__('resource.components.city'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 3,
                                    ]),
                                Infolists\Components\TextEntry::make('event_invoice_address.address_address')
                                    ->label('address')
                                    ->columnSpanFull(),
                            ]),
                        Infolists\Components\Tabs\Tab::make('Publikációk')
                            ->schema([
                                ...PublicationInfolist::schema(),
                            ]),
                        ...self::extraForm(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function extraForm(): array
    {
        //dd(request()->eventslug, \App\Models\Event\Event::where('slug', request()->eventslug)->get());
        $custom_form = self::getRecord()?->reg_form->custom_form ?? null;
        $response = self::$component->event_form_response->responses ?? null;

        return
            $custom_form
            ? [Infolists\Components\Tabs\Tab::make('További kérdések')
                ->schema([
                    ...CustomFormInfolist::schema($custom_form, $response),
                ])]
            : [];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
