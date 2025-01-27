<?php

namespace App\Filament\ToAdmin\Resources\Blog\PostResource\Pages;

use App\Filament\ToAdmin\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterFill(): void
    {
        // Automatikusan kitölti az event_id mezőt a formban
        $this->form->fill([
            'scientific_department_id' => Auth::user()->currentDepartment()->id,
        ]);
    }
}
