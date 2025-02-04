<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\Actions;

use Illuminate\Support\Str;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TotyaDev\TotyaDevMediaManager\Models\Folder;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;

class GoToParentFolderAction
{
    // TODO: ha lehet feljebb menni, mert ugye TO esetében nem szeretném.

    public static function make(string $folder_id): Actions\Action
    {
        $parent_folder_id = Folder::query()->find($folder_id)->parent ?? null;

        return Actions\Action::make('goto_parent_folder')
            ->hidden(fn() => !filament('totyadev-media-manager')->allowSubFolders)
            ->mountUsing(function () use ($parent_folder_id) {
                session()->put('parent_folder_id', $parent_folder_id);
            })
            ->color('info')
            ->hiddenLabel()
            ->tooltip(trans('totyadev-media-manager::messages.media.actions.goto_parent.label'))
            ->label(trans('totyadev-media-manager::messages.media.actions.goto_parent.label'))
            ->icon('heroicon-o-folder-arrow-down')
            ->action(function () use ($parent_folder_id) {
                if ($parent_folder_id)
                    return redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.resources.media.index', ['folder_id' => $parent_folder_id]);
                else
                    return redirect()->route('filament.' . filament()->getCurrentPanel()->getId() . '.resources.folders.index');
            });
    }
}
