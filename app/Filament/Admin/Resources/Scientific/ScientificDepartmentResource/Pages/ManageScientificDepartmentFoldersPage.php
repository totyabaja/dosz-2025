<?php

namespace App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource\Pages;

use App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

class ManageScientificDepartmentFoldersPage extends ManageRelatedRecords
{
    protected static string $resource = ScientificDepartmentResource::class;

    protected static string $relationship = 'folders';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Folders';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->defaultSort('folder_has_models.created_at', 'asc')
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->requiresConfirmation()
                    ->action(function ($livewire) {
                        $parent = $livewire->getOwnerRecord();

                        $parent_folder = Folder::where('collection', 'to-publikus-allomanyok')->first();
                        // Új mappa létrehozása
                        $folder = Folder::create([
                            'model_id' => $parent_folder->id,
                            'model_type' => Folder::class,
                            'name' => "{$parent->slug} root folder",
                            'collection' =>  "{$parent->slug}_root_folder",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        //$parent_folder->model()->save($folder);

                        // A mappát a saját kapcsolatában mentjük
                        $parent->folders()->save($folder);
                    })
                    ->createAnother(false)
                    ->hidden(fn($livewire) => $livewire->getOwnerRecord()->folders()->count()),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
