<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\Actions;

use Illuminate\Support\Str;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TotyaDev\TotyaDevMediaManager\Models\Folder;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;

class CreateSubFolderAction
{
    public static function make(string $folder_id): Actions\Action
    {
        return Actions\Action::make('create_sub_folder')
            ->hidden(fn() => !filament('totyadev-media-manager')->allowSubFolders)
            ->mountUsing(function () use ($folder_id) {
                session()->put('folder_id', $folder_id);
            })
            ->color('info')
            ->hiddenLabel()
            ->tooltip(trans('totyadev-media-manager::messages.media.actions.sub_folder.label'))
            ->label(trans('totyadev-media-manager::messages.media.actions.sub_folder.label'))
            ->icon('heroicon-o-folder-minus')
            ->form([
                Forms\Components\TextInput::make('name')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.name'))
                    ->columnSpanFull()
                    ->lazy()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        $set('collection', Str::slug($get('name')));
                    })
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('collection')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.collection'))
                    ->columnSpanFull()
                    ->unique(Folder::class)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.description'))
                    ->columnSpanFull()
                    ->maxLength(255),
                IconPicker::make('icon')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.icon')),
                Forms\Components\ColorPicker::make('color')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.color')),
                Forms\Components\Toggle::make('is_protected')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.is_protected'))
                    ->live()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('password')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.password'))
                    ->hidden(fn(Forms\Get $get) => !$get('is_protected'))
                    ->password()
                    ->revealable()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label(trans('totyadev-media-manager::messages.folders.columns.password_confirmation'))
                    ->hidden(fn(Forms\Get $get) => !$get('is_protected'))
                    ->password()
                    ->required()
                    ->revealable()
                    ->maxLength(255)
            ])
            ->action(function (array $data) use ($folder_id) {
                $folder = Folder::find($folder_id);
                if ($folder) {
                    $data['user_id'] = auth()->user()->id;
                    $data['user_type'] = get_class(auth()->user());
                    $data['model_id'] = $folder_id;
                    $data['model_type'] = Folder::class;
                    Folder::query()->create($data);
                }

                Notification::make()
                    ->title('Folder Created')
                    ->body('Folder Created Successfully')
                    ->success()
                    ->send();
            });
    }
}
