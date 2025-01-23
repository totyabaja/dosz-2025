<?php

namespace App\Livewire;

use App\Models\DoctoralSchool;
use App\Models\LegalAid;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LegalAidForm extends Component
{
    #[Validate('nullable|string|max:255')]
    public $first_name;

    #[Validate('nullable|string|max:255')]
    public $last_name;

    #[Validate('nullable|email|max:255')]
    public $email;

    #[Validate('nullable|exists:universities,id')]
    public $university_id;

    #[Validate('nullable|exists:doctoral_schools,id')]
    public $doctoral_school_id;

    #[Validate('required|string')]
    public $question;

    #[Validate('accepted')]
    public $confirm_1 = false;

    #[Validate('accepted')]
    public $confirm_2 = false;

    public function submit()
    {
        $validatedData = $this->validate();

        LegalAid::create($validatedData);

        $this->reset(); // Mezők törlése a mentés után

        session()->flash('message', 'Jogsegélykérés sikeresen mentve!');
    }

    public function updated($value, $name)
    {
        $this->validateOnly($name);
    }

    public function updatedDoctoralSchoolId($value)
    {
        $this->university_id = DoctoralSchool::find($value)?->university_id ?? null;
    }

    public function render()
    {
        return view('livewire.legal-aid-form', [
            'universities' => \App\Models\University::query()->get(),
            'doctoral_schools' => \App\Models\DoctoralSchool::query()
                ->when($this->university_id, function ($query) {
                    $query->where('university_id', $this->university_id);
                })
                ->get(),
        ]);
    }
}
