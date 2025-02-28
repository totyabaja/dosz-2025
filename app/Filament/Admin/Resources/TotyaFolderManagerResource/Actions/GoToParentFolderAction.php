<?php

namespace App\Filament\Admin\Resources\TotyaFolderManagerResource\Actions;

use App\Filament\Admin\Resources\TotyaFolderManagerResource;
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
            /*->mountUsing(function () use ($parent_folder_id) {
                session()->put('parent_folder_id', $parent_folder_id);
            })*/
            ->color('info')
            ->hiddenLabel()
            ->tooltip('Vissza a szülő mappába')
            ->label('Vissza a szülő mappába')
            ->icon('fas-folder-tree')
            ->action(function () use ($parent_folder_id) {
                if ($parent_folder_id)
                    return redirect()->to(TotyaFolderManagerResource::getUrl('index', ['parent_id' => $parent_folder_id]));
                else
                    return redirect()->to(TotyaFolderManagerResource::getUrl('index'));
            });
    }
}
