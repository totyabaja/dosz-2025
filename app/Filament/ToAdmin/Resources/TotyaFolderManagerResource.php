<?php

namespace App\Filament\ToAdmin\Resources;

use App\Filament\Admin\Resources\TotyaFolderManagerResource as BaseTotyaFolderManagerResource;
use App\Filament\ToAdmin\Resources\TotyaFolderManagerResource\Pages;
use App\Filament\ToAdmin\Resources\TotyaFolderManagerResource\RelationManagers;
use App\Models\TotyaFolderMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Scientific;
use Illuminate\Support\Facades\Auth;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

class TotyaFolderManagerResource extends Resource
{
    public static ?string $main_folder_collection = null;
    public static ?string $folder_id = null;

    public static ?string $model = Folder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function setMainFolderCollection(): void
    {
        $folder = Auth::user()->currentDepartment()?->folder();

        if ($folder) {
            static::$main_folder_collection = $folder->collection;
        }

        static::$main_folder_collection = self::$main_folder_collection ?? null; // TODO: be kell midnig állítani valamit
        static::$folder_id =
            request()->get('folder_id')
            ?? Folder::firstWhere('collection', static::$main_folder_collection)?->id;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        static::setMainFolderCollection();

        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('parent_id', static::$folder_id);
            })
            ->content(function () {
                return view('filament.folder.folder', ['folder_id' => static::$folder_id]);
            })
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->label(trans('totyadev-media-manager::messages.media.columns.model'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('collection')
                        ->label(trans('totyadev-media-manager::messages.media.columns.collection_name'))
                        ->badge()
                        ->icon('heroicon-o-folder')
                        ->searchable(),
                ]),
            ])
            //->recordUrl(fn($record) => "?folder_id={$record->id}")
            // https://dosz-2025.test/admin/totya-folder-managers?folder_id=9e1d82b5-1ceb-4633-88f4-8f4f1a8a2695
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->defaultSort('name', 'asc')
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->defaultPaginationPageOption(12)
            ->paginationPageOptions([
                "12",
                "24",
                "48",
                "96",
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
            'index' => Pages\ListTotyaFolderManagers::route('/{record}'),
            'create' => Pages\CreateTotyaFolderManager::route('/create'),
            'edit' => Pages\EditTotyaFolderManager::route('/{record}/edit'),
        ];
    }
}
