<?php

namespace App\Filament\ToAdmin\Resources\Blog\PostResource\Pages;

use App\Filament\ToAdmin\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
