<?php

namespace App\Livewire;

use BezhanSalleh\FilamentLanguageSwitch\Events\LocaleChanged;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class LanguageSwitch extends Component
{
    //public \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch $languageSwitch;

    public function changeLocale($locale)
    {
        \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::trigger(locale: $locale);
    }

    public function render():  View
    {
        $languageSwitch = \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::make();
        return view('livewire.language-switch', [
            'languageSwitch' => $languageSwitch
        ]);
    }
}
