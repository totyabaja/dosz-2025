<?php

namespace App\Filament\Admin\Resources\Position;

use App\Filament\Admin\Resources\Position\PositionResource\Pages;
use App\Models\Position\Position;
use App\Models\Position\PositionSubtype;
use App\Models\Position\PositionType;
use App\Models\Scientific\ScientificDepartment;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('position');
    }

    public static function getPluralModelLabel(): string
    {
        return __('positions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->options(fn() => User::all()->mapWithKeys(fn($item) => [$item->id => "$item->name ($item->email)"]))
                    /*->options(function () {
                        return User::all()->mapWithKeys(function ($item) {
                            $img = $item->getFilamentAvatarUrl();

                            return [
                                $item->id => <<<PHP
                                    <div class="relative flex rounded-md">
                                        <div class="flex">
                                            <div class="px-2 py-3">
                                                <div class="w-10 h-10">
                                                    <img src="$img" alt="{$item->name}" role="img" class="object-cover w-full h-full overflow-hidden rounded-full shadow" />
                                                </div>
                                            </div>

                                            <div class="flex flex-col justify-center py-2 pl-3">
                                                <p class="pb-1 text-sm font-bold">{$item->name}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>

                                    </div>
                                PHP
                            ];
                        });
                    })*/
                    ->searchable()
                    ->preload()
                    // ->allowHtml()
                    ->required(),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('position_types')
                            ->native(false)
                            ->options(
                                fn() => PositionType::all()
                                    ->mapWithKeys(fn($item) => [
                                        $item->id => $item->name,
                                    ])
                            )
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Az egyetem kiválasztása után töröljük a doktori iskola mezőt
                                $set('position_subtype_id', null);
                            })
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                            ])
                            ->createOptionUsing(function ($data) {
                                return PositionType::create($data);
                            }),
                        Forms\Components\Select::make('position_subtype_id')
                            ->relationship(name: 'position_subtype')
                            ->native(false)
                            ->options(function (callable $get) {
                                $posiTypeId = $get('position_types'); // Kiválasztott egyetem ID-je
                                if (! $posiTypeId) {
                                    return PositionSubtype::all()
                                        ->mapWithKeys(fn($item) => [
                                            $item->id => $item->name['hu'] ?? 'N/A', // Ha nincs 'hu', akkor 'N/A'
                                        ]);
                                }

                                return PositionSubtype::query()
                                    ->where('position_type_id', $posiTypeId)
                                    ->get()
                                    ->mapWithKeys(fn($item) => [
                                        $item->id => $item->name['hu'] ?? 'N/A', // Ha nincs 'hu', akkor 'N/A'
                                    ]);
                            })
                            ->live()
                            ->preload()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // A doktori iskola kiválasztása után frissítjük az egyetemet
                                $posiTypeId = PositionSubtype::find($state)?->position_type_id;
                                $set('position_types', $posiTypeId);
                            })
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $universityId = PositionSubtype::find($state)?->position_type_id;
                                $set('position_types', $universityId);
                            })
                            ->createOptionForm([
                                Forms\Components\Select::make('position_type_id')
                                    ->options(fn() => PositionType::orderBy('name')->pluck('name', 'id')->toArray()),
                                Forms\Components\Section::make()->schema([
                                    Forms\Components\TextInput::make('name.hu')
                                        ->required(),
                                    Forms\Components\TextInput::make('name.en')
                                        ->required(),
                                ])
                                    ->columnSpanFull()
                                    ->columns(2),
                            ])
                            ->createOptionUsing(function ($data) {
                                $data['order'] = PositionSubtype::where('position_type_id', $data['position_type_id'])->count() + 1;

                                return PositionSubtype::create($data);
                            }),
                        Forms\Components\Select::make('scientific_department_id')
                            ->nullable()
                            ->options(function () {
                                return ScientificDepartment::all()
                                    ->mapWithKeys(fn($item) => [
                                        $item->id => $item->name['hu'] ?? 'N/A', // Ha nincs 'hu', akkor 'N/A'
                                    ]);
                            })
                            ->hidden(function (callable $get) {
                                $posiTypeId = $get('position_type_id');

                                return PositionType::find($posiTypeId)?->name !== 'TO elnökség';
                            })
                            ->required(function (callable $get) {
                                $posiTypeId = $get('position_type_id');

                                return PositionType::find($posiTypeId)?->name === 'TO elnökség';
                            })
                            ->native(false),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Forms\Components\TextInput::make('notes')
                    ->maxLength(200),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->nullable(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(200),
                Forms\Components\Textarea::make('areas')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('position_subtype.name')
                    ->listWithLineBreaks()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('scientific_department.short_name.hu')
                    ->numeric()
                    ->sortable(),
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
                Filter::make('is_active')
                    ->form([
                        Toggle::make('posi_active')
                            ->label('Active positions')
                            ->default(true),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['posi_active'],
                                fn($query, $date) => $query->where(function ($query) {
                                    return $query
                                        ->whereNull('end_date')
                                        ->orWhereDate('end_date', '>=', Carbon::now());
                                }),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['posi_active']) {
                            return null;
                        }

                        return 'Only active positions';
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('default');
    }

    public static function getNavigationSort(): ?int
    {
        return 0;
    }
}
