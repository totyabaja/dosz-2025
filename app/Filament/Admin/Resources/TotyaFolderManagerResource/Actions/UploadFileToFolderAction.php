<?php

namespace App\Filament\Admin\Resources\TotyaFolderManagerResource\Actions;

use Illuminate\Support\Str;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TotyaDev\TotyaDevMediaManager\Models\Folder;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use TotyaDev\TotyaDevMediaManager\Form\MediaManagerInput;

class UploadFileToFolderAction
{
    public static function make(string $folder_id): Actions\Action
    {
        return Actions\Action::make('upload_files')
            ->mountUsing(function () use ($folder_id) {
                session()->put('folder_id', $folder_id);
            })
            ->color('info')
            ->hiddenLabel()
            ->tooltip('Fájlok feltöltése')
            ->label('Fájlok feltöltése')
            ->icon('fas-file-arrow-up')
            ->form([
                Forms\Components\FileUpload::make('file')
                    ->label('Fájl kiválasztása')
                    ->required()
                    ->maxSize('100000')
                    ->storeFiles(false)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('label.hu')
                    ->label(__('resource.components.name_hungarian'))
                    ->required(),
                Forms\Components\TextInput::make('label.en')
                    ->label(__('resource.components.name_english')),
            ])
            ->action(function (array $data) use ($folder_id) {
                $folder = Folder::find($folder_id);
                // TODO: ez mit is csinál????
                if ($folder->model) {
                    $folder->model->addMedia($data['file'])
                        ->withCustomProperties([
                            'title' => $data['label'],
                        ])
                        ->toMediaCollection($folder->collection);
                } else {
                    $folder->addMedia($data['file'])
                        ->withCustomProperties([
                            'title' => $data['label'],
                            'description' => $data['description']
                        ])
                        ->toMediaCollection($folder->collection);
                }

                Notification::make()
                    ->title(trans('totyadev-media-manager::messages.media.notifications.create-media'))
                    ->send();
            });
    }
}
