<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\Actions;

use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use TotyaDev\TotyaDevMediaManager\Models\Folder;

class DeleteFolderAction
{
    public static function make(string $folder_id): Actions\Action
    {
        return Actions\Action::make('delete_folder')
            ->mountUsing(function () use ($folder_id) {
                session()->put('folder_id', $folder_id);
            })
            ->hiddenLabel()
            ->requiresConfirmation()
            ->tooltip(trans('totyadev-media-manager::messages.media.actions.delete.label'))
            ->label(trans('totyadev-media-manager::messages.media.actions.delete.label'))
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->action(function () use ($folder_id) {
                $folder = Folder::find($folder_id);
                $folder->delete();
                session()->forget('folder_id');

                Notification::make()->title(trans('totyadev-media-manager::messages.media.notifications.delete-folder'))->send();
                return redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.resources.folders.index');
            });
    }
}
