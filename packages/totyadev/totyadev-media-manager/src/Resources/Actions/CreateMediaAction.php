<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\Actions;

use TotyaDev\TotyaDevMediaManager\Models\Folder;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;

class CreateMediaAction
{
    public static function make(string $folder_id): Actions\Action
    {
        return Actions\Action::make('create_media')
            ->mountUsing(function () use ($folder_id) {
                session()->put('folder_id', $folder_id);
            })
            ->label(trans('totyadev-media-manager::messages.media.actions.create.label'))
            ->icon('heroicon-o-plus')
            ->form([
                Forms\Components\FileUpload::make('file')
                    ->label(trans('totyadev-media-manager::messages.media.actions.create.form.file'))
                    ->maxSize('100000')
                    ->columnSpanFull()
                    ->required()
                    ->storeFiles(false),
                Forms\Components\TextInput::make('title')
                    ->label(trans('totyadev-media-manager::messages.media.actions.create.form.title'))
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label(trans('totyadev-media-manager::messages.media.actions.create.form.description'))
                    ->columnSpanFull(),
            ])
            ->action(function (array $data) use ($folder_id) {
                $folder = Folder::find($folder_id);
                if ($folder) {
                    if ($folder->model) {
                        $folder->model->addMedia($data['file'])
                            ->withCustomProperties([
                                'title' => $data['title'],
                                'description' => $data['description']
                            ])
                            ->toMediaCollection($folder->collection);
                    } else {
                        $folder->addMedia($data['file'])
                            ->withCustomProperties([
                                'title' => $data['title'],
                                'description' => $data['description']
                            ])
                            ->toMediaCollection($folder->collection);
                    }
                }

                Notification::make()->title(trans('totyadev-media-manager::messages.media.notifications.create-media'))->send();
            });
    }
}
