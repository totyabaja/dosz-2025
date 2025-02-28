<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TotyaFolderManagerResource\Pages;
use App\Filament\Admin\Resources\TotyaFolderManagerResource\RelationManagers;
use App\Models\TotyaFolderManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use TotyaDev\TotyaDevMediaManager\Models\Folder;
use TotyaDev\TotyaDevMediaManager\Models\Media;
use App\Models\Scientific;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TotyaFolderManagerResource extends Resource
{
    public static ?string $model = Folder::class;

    protected static ?string $navigationIcon = 'fas-folder-open';

    public static function getModelLabel(): string
    {
        return __('resource.title.media');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.title.medium');
    }

    public static function table(Table $table): Table
    {
        $folder_id = request()->query('folder_id') ?? session()->get('folder_id', Folder::firstWhere('collection', 'publikus-allomanyok')->id);

        return $table
            ->content(function () use ($folder_id) {
                return view('filament.folder.folder', ['folder_id' => $folder_id]);
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
            ->recordUrl(fn($record) => TotyaFolderManagerResource::getUrl('index', ['folder_id' => $record->id]))
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('name', 'asc')
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

    public static function getEloquentQuery(): Builder
    {
        $folder_id = request()->query('folder_id') ?? session('folder_id');

        if (request()->has('folder_id')) {
            session(['folder_id' => $folder_id]);
        }

        // Ha még mindig nincs érték, akkor visszaállítjuk az alapértelmezett mappára
        if (!$folder_id) {
            $folder_id = Folder::firstWhere('collection', 'publikus-allomanyok')?->id;
            session(['folder_id' => $folder_id]);
        }

        $folder = Folder::find($folder_id);

        $foldersQuery =  static::getModel()::query()
            ->where('parent_id', $folder_id)
            ->select([
                'id',
                'name',
                'collection',
                'parent_id',
                DB::raw("'folder' as type") // Megkülönböztetéshez, hogy mappa vagy fájl
            ]);
        $mediaQuery = Media::query()
            ->where('collection_name', Folder::find($folder_id)->collection)
            ->select([
                'id',
                'name',
                'collection_name as collection',
                DB::raw('NULL as parent_id'), // Mert a Media táblában nincs parent_id
                DB::raw("'media' as type") // Megkülönböztetéshez
            ]);

        return $foldersQuery
            ->union($mediaQuery)
            ->orderByRaw("FIELD(type, 'folder', 'media')")
            ->orderBy('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTotyaFolderManagers::route('/'),
        ];
    }
}
