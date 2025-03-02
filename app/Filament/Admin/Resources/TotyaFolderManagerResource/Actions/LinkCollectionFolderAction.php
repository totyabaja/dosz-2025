<?php

namespace App\Filament\Admin\Resources\TotyaFolderManagerResource\Actions;

use Illuminate\Support\Str;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TotyaDev\TotyaDevMediaManager\Models\Folder;
use Filament\Forms;
use Filament\Actions;
use Filament\Notifications\Notification;

class LinkCollectionFolderAction
{
    public static function make(string $folder_id): Actions\Action
    {
        return Actions\Action::make('link_collection_folder')
            ->mountUsing(function () use ($folder_id) {
                session()->put('folder_id', $folder_id);
            })
            ->color('info')
            ->hiddenLabel()
            ->tooltip('Könyvtár linkelése')
            ->label('Könyvtár linkelése')
            ->icon('fas-link')
            ->form([
                Forms\Components\Select::make('parent_id')
                    ->label('parent')
                    ->options(
                        fn() => Folder::query()
                            ->whereNull('parent_id')
                            ->orWhere('parent_id', '-1')
                            ->get()
                            ->mapWithKeys(fn($item) => [$item->id => $item->name])
                            ->toArray()
                    )->preload()
                    ->required(),

            ])
            ->action(function ($data) use ($folder_id) {
                if ($folder = Folder::find($data['parent_id'])) {
                    $folder->update(['parent_id' => $folder_id]);

                    Notification::make()
                        ->title('Folder Linked')
                        ->body('Folder Linked Successfully')
                        ->success()
                        ->send();
                }
            });
    }
}
