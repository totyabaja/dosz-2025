<div class="py-6">
    <h3 class="text-lg font-medium filament-breezy-grid-title">További adatok</h3>
    <p class="mt-1 mb-3 text-sm text-gray-500 filament-breezy-grid-description">
        További érdekes adatok.
    </p>
    <form wire:submit.prevent="submit" class="mt-3 space-y-6">

        {{ $this->form }}

        <div class="text-right">
            <x-filament::button type="submit" form="submit" class="align-right">
                Update
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
