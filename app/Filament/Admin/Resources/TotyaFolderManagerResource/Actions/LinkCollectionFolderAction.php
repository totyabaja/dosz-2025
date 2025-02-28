<?php

namespace App\Filament\Admin\Resources\TotyaFolderManagerResource\Actions;

use Illuminate\Support\Str;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TotyaDev\TotyaDevMediaManager\Models\Folder;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

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
                Forms\Components\Select::make('folder_id')
                    ->label('name')
                    ->columnSpanFull()
                    ->options(fn() => Folder::query()
                        ->whereNull('parent_id')
                        ->get()
                        ->mapWithKeys(fn($item) => [$item->id => $item->name])
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->action(function (array $data) use ($folder_id) {
                dd($data);
                $folder = Folder::find($folder_id);
                if ($folder) {
                    $data['user_id'] = Auth::user()->id;
                    $data['user_type'] = \App\Models\User::class;
                    $data['model_id'] = $folder_id;
                    $data['model_type'] = Folder::class;
                    $data['parent_id'] = $folder_id;
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
