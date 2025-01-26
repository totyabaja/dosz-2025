<?php

namespace App\Filament\Admin\Resources\Event\CustomFormResource\Pages;

use App\Filament\Admin\Resources\Event\CustomFormResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomForm extends CreateRecord
{
    protected static string $resource = CustomFormResource::class;
}
