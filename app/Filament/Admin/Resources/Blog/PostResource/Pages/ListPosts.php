<?php

namespace App\Filament\Admin\Resources\Blog\PostResource\Pages;

use App\Filament\Admin\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use JoseEspinal\RecordNavigation\Traits\HasRecordsList;

class ListPosts extends ListRecords
{
    use HasRecordsList;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
