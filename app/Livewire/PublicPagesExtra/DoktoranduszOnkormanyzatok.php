<?php

namespace App\Livewire\PublicPagesExtra;

use App\Models\Scientific\University;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class DoktoranduszOnkormanyzatok extends Component
{

    public $search = '';

    public function render()
    {
        $universities = University::query()
            ->when($this->search !== '', function ($query) {
                $query->whereRaw("LOWER(JSON_UNQUOTE(full_name->'$.hu')) LIKE ?", ['%' . strtolower($this->search) . '%']);
            })
            ->get()
            ->sortBy('filament_full_name');

        return view('livewire.public-pages-extra.doktorandusz-onkormanyzatok', [
            'universities' => $universities
        ]);
    }
}
