<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Field;

class ApprovedMembershipsComponent extends Field
{
    protected string $view = 'filament.components.approved-membership-component';

    protected array $approvedDepartments = [];

    // Adatok átadása saját metódussal
    public function approvedDepartments(array $departments): static
    {
        $this->approvedDepartments = $departments;
        return $this;
    }

    // Az adatok elérhetősége a Blade fájlban
    public function getApprovedDepartments(): array
    {
        return $this->approvedDepartments;
    }
}
