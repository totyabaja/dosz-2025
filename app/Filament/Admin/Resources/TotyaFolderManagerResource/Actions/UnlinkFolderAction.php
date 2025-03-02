<?php

namespace App\Filament\Admin\Resources\TotyaFolderManagerResource\Actions;

use App\Filament\Admin\Resources\TotyaFolderManagerResource;
use Illuminate\Support\Str;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TotyaDev\TotyaDevMediaManager\Models\Folder;
use Filament\Forms;
use Filament\Actions;
use Filament\Notifications\Notification;

class UnlinkFolderAction
{
    public static function make(string $folder_id): Actions\Action
    {
        $parent_folder_id = Folder::query()->find($folder_id)->parent ?? null;

        return Actions\Action::make('unlink_collection_folder')
            ->mountUsing(function () use ($folder_id) {
                session()->put('folder_id', $folder_id);
            })
            ->color('danger')
            ->hiddenLabel()
            ->tooltip('Könyvtár linkelésének megszüntetése')
            ->label('Könyvtár linkelésének megszüntetése')
            ->icon('fas-link-slash')
            ->requiresConfirmation(true)
            ->action(function () use ($folder_id, $parent_folder_id) {

                Folder::find($folder_id)->update(['parent_id' => null]);

                Notification::make()
                    ->title('Folder Unlinked')
                    ->body('Folder Unlinked Successfully')
                    ->success()
                    ->send();

                if ($parent_folder_id)
                    return redirect()->to(TotyaFolderManagerResource::getUrl('index', ['parent_id' => $parent_folder_id]));
                else
                    return redirect()->to(TotyaFolderManagerResource::getUrl('index'));
            });
    }
}
