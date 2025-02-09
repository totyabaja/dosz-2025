<?php

namespace App\Livewire\PublicPagesExtra;

use App\Models\Scientific\ScientificDepartment;
use Livewire\Component;

class ScientificDepartments extends Component
{
    public $search = '';

    public function render()
    {
        $tos = ScientificDepartment::active()
            ->when($this->search != '', function ($query) {
                $query->whereRaw("LOWER(JSON_UNQUOTE(name->'$.hu')) LIKE ?", ['%' . strtolower($this->search) . '%']);
            })
            ->get()
            ->sortBy('filament_name');

        return view('livewire.public-pages-extra.scientific-departments', compact('tos'));
    }
}
