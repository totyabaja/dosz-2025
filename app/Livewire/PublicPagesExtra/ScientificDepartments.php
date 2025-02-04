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
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->get()
            ->sortBy('filament_name');

        return view('livewire.public-pages-extra.scientific-departments', compact('tos'));
    }
}
