<?php

namespace App\Filament\Admin\Resources\Blog\PostResource\Pages;

use App\Filament\Admin\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
