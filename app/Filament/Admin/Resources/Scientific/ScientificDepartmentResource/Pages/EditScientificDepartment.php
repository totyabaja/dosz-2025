<?php

namespace App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource\Pages;

use App\Filament\Admin\Resources\Scientific\ScientificDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use JoseEspinal\RecordNavigation\Traits\HasRecordNavigation;

class EditScientificDepartment extends EditRecord
{

    protected static string $resource = ScientificDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string|Htmlable
    {
        $title = $this->record->filament_name;

        return new HtmlString("
            <div class='flex items-center space-x-2'>
                <div>$title</div>
            </div>
        ");
    }
}
