<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\Actions;

use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

class EditCurrentFolderAction
{
    public static function make(string $folder_id): Actions\Action
    {
        $form = Folder::query()->where('id', $folder_id)->with('users')->first()?->toArray();
        $form['users'] = collect($form['users'])->pluck('id')->toArray();

        return Actions\Action::make('edit_current_folder')
            ->hiddenLabel()
            ->mountUsing(function () use ($folder_id) {
                session()->put('folder_id', $folder_id);
            })
            ->tooltip(trans('totyadev-media-manager::messages.media.actions.edit.label'))
            ->label(trans('totyadev-media-manager::messages.media.actions.edit.label'))
            ->icon('heroicon-o-pencil-square')
            ->color('warning')
            ->form(function () {
                return [
                    Grid::make([
                        "sm" => 1,
                        "md" => 2
                    ])
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label(trans('totyadev-media-manager::messages.folders.columns.name'))
                                ->columnSpanFull()
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
                                ->confirmed()
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
                                ->maxLength(255),
                            Forms\Components\Toggle::make('is_public')
                                ->visible(filament('totyadev-media-manager')->allowUserAccess)
                                ->label(trans('totyadev-media-manager::messages.folders.columns.is_public'))
                                ->live()
                                ->columnSpanFull(),
                            Forms\Components\Toggle::make('has_user_access')
                                ->visible(filament('totyadev-media-manager')->allowUserAccess)
                                ->hidden(fn(Forms\Get $get) => $get('is_public'))
                                ->label(trans('totyadev-media-manager::messages.folders.columns.has_user_access'))
                                ->live()
                                ->columnSpanFull(),
                            Forms\Components\Select::make('users')
                                ->required()
                                ->visible(filament('totyadev-media-manager')->allowUserAccess)
                                ->hidden(fn(Forms\Get $get) => !$get('has_user_access'))
                                ->label(trans('totyadev-media-manager::messages.folders.columns.users'))
                                ->searchable()
                                ->multiple()
                                ->options(User::query()->where('id', '!=', auth()->user()->id)->pluck(config('totyadev-media-manager.user.column_name'), 'id')->toArray())
                        ])
                ];
            })
            ->fillForm($form)
            ->action(function (array $data) use ($folder_id) {
                $folder = Folder::find($folder_id);
                $folder->update($data);

                if (isset($data['users'])) {
                    $folder->users()->sync($data['users']);
                }

                Notification::make()->title(trans('totyadev-media-manager::messages.media.notifications.edit-folder'))->send();
            });
    }
}
