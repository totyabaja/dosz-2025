<?php

namespace App\Filament\ToAdmin\Resources\TotyaFolderManagerResource\Pages;

use App\Filament\ToAdmin\Resources\TotyaFolderManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Notifications\Notification;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

class ListTotyaFolderManagers extends ListRecords
{
    protected static string $resource = TotyaFolderManagerResource::class;

    public function getTitle(): string
    {
        return Folder::find(static::getResource()::$folder_id)->name ?? 'Fájlok';
    }

    public function getBreadcrumb(): ?string
    {
        return Folder::find(static::getResource()::$folder_id)->name ?? 'N/A mappa';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_folder')
                ->modal()
                ->form(fn($form) => TotyaFolderManagerResource::form($form))
                ->action(function ($record, $data) {
                    Folder::create([
                        'parent_id' => static::getResource()::$folder_id,
                        'name' => $data['name'],
                        'model_type' => Folder::class,
                        'model_id' => static::getResource()::$folder_id,
                    ]);
                }),
        ];
    }

    public function folderAction(?Folder $item = null)
    {
        return Actions\Action::make('folderAction')
            ->requiresConfirmation(function (array $arguments) {
                if ($arguments['folder_id']['is_protected']) {
                    return true;
                } else {
                    return false;
                }
            })
            ->form(function (array $arguments) {
                if ($arguments['folder_id']['is_protected']) {
                    return [
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),
                    ];
                } else {
                    return null;
                }
            })
            ->action(function (array $arguments, array $data) {
                if ($arguments['folder_id']['is_protected']) {
                    if ($arguments['relder_id']['password'] != $data['password']) {
                        Notification::make()
                            ->title('Password is incorrect')
                            ->danger()
                            ->send();

                        return;
                    } else {
                        session()->put('folder_password', $data['password']);
                    }
                }

                return redirect()->to(TotyaFolderManagerResource::getUrl('index', ['folder_id' => $arguments['folder_id']['id']]));

                /*
                if (!$arguments['record']['model_type']) {
                    if (filament()->getTenant()) {
                        return redirect()->to(url(filament()->getCurrentPanel()->getId() . '/' . filament()->getTenant()->id . '/media?folder_id=' . $arguments['record']['id']));
                    } else {
                        return redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.resources.media.index', ['folder_id' => $arguments['record']['id']]);
                    }
                }
                if (!$arguments['record']['model_id'] && !$arguments['record']['collection']) {
                    if (filament()->getTenant()) {
                        return redirect()->to(url(filament()->getCurrentPanel()->getId() . '/' . filament()->getTenant()->id . '/folders?model_type=' . $arguments['record']['model_type']));
                    } else {
                        return redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.resources.folders.index', ['model_type' => $arguments['record']['model_type']]);
                    }
                } else if (!$arguments['record']['model_id']) {
                    if (filament()->getTenant()) {
                        return redirect()->to(url(filament()->getCurrentPanel()->getId() . '/' . filament()->getTenant()->id . '/folders?model_type=' . $arguments['record']['model_type'] . '&collection=' . $arguments['record']['collection']));
                    } else {
                        return redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.resources.folders.index', ['model_type' => $arguments['record']['model_type'], 'collection' => $arguments['record']['collection']]);
                    }
                } else {
                    if (filament()->getTenant()) {
                        return redirect()->to(url(filament()->getCurrentPanel()->getId() . '/' . filament()->getTenant()->id . '/media?folder_id=' . $arguments['record']['id']));
                    } else {
                        return redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.resources.media.index', ['folder_id' => $arguments['record']['id']]);
                    }
                }
                    */
            })
            ->view('totyadev-media-manager::pages.folder-action', ['item' => $item]);
    }
}
