<?php

namespace TotyaDev\TotyaDevMediaManager\Resources\MediaResource\Pages;

use TotyaDev\TotyaDevMediaManager\Resources\MediaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;
}
