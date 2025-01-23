<?php

namespace App\Livewire\PublicPagesExtra;

use App\Models\University;
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

class DoktoranduszOnkormanyzatok extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(University::query()->orderBy('full_name'))
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    SpatieMediaLibraryImageColumn::make('media')
                        ->collection('university-images')
                        ->height(150)
                        ->wrap()
                        ->extraImgAttributes([
                            'class' => 'rounded-md mx-auto',
                        ])
                        ->alignment('center'),
                    Tables\Columns\TextColumn::make('full_name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->alignment('center')
                        ->extraAttributes([
                            'class' => 'space-y-2',
                        ]),
                ]),
            ])
            ->contentGrid([
                'default' => 2,
                'md' => 4,
            ])
            ->recordUrl(false)
            ->paginationPageOptions([12, 24, 36]);
    }

    public function render()
    {
        $universities = University::query()
            ->orderBy('full_name' . (session()->get('locale', 'hu') == 'hu' ? '' : '_en'))
            ->get();

        return view('livewire.public-pages-extra.doktorandusz-onkormanyzatok');
    }
}
