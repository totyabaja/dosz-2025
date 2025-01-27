<?php

namespace App\Livewire;

use App\Models\Scientific\ScientificDepartment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ScientificDepartmentSwitch extends Component
{
    public $selected_department;

    public function mount()
    {
        $this->selected_department = session()->get('sd_selected', Auth::user()->scientific_departments?->first()->id ?? null);
        session(['sd_selected' => $this->selected_department]);
    }

    public function updatedSelectedDepartment($value)
    {
        session(['sd_selected' => $value]);

        // TODO
        $this->js('window.location.reload()');
    }

    public function render()
    {
        $departments = Auth::user()->scientific_departments;

        return view('livewire.scientific-department-switch', [
            'departments' => $departments
        ]);
    }
}
