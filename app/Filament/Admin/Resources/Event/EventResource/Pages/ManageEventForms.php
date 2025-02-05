<?php

namespace App\Filament\Admin\Resources\Event\EventResource\Pages;

use App\Filament\Admin\Resources\Event\EventResource;
use App\Models\Event\CustomForm;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageEventForms extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function getNavigationLabel(): string
    {
        return 'Event Forms';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_reg_form_id')
                    ->label('Regisztrációs form')
                    ->options(
                        fn() => CustomForm::all()
                            ->mapWithKeys(fn($item) => [$item->id => $item->name])
                    )
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('event_feedback_form_id')
                    ->label('Visszajelzés form')
                    ->options(
                        fn() => CustomForm::all()
                            ->mapWithKeys(fn($item) => [$item->id => $item->name])
                    )
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
